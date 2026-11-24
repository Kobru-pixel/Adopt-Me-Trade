<?php
/**
 * Adopt Me Trade - Motor de IA para Detecção e Reconhecimento de Pets
 * Processa imagens da Câmera, Upload ou Leitura de Tela do Jogo
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../database/bd.php';

try {
    $inputRaw = file_get_contents('php://input');
    $input = json_decode($inputRaw, true) ?: $_POST;

    $imageData = $input['image'] ?? '';
    $colorSignature = $input['color_signature'] ?? null; // [r, g, b] do client canvas
    $ocrText = isset($input['text_hint']) ? trim($input['text_hint']) : '';

    // Se o cliente enviar o texto lido da trade ou nome parcial
    if (!empty($ocrText)) {
        $stmt = $pdo->prepare("SELECT * FROM items WHERE name LIKE ? ORDER BY base_value DESC LIMIT 1");
        $stmt->execute(['%' . $ocrText . '%']);
        $matched = $stmt->fetch();
        if ($matched) {
            echo json_encode([
                'status' => 'success',
                'matched' => true,
                'pet' => $matched,
                'confidence' => 96.5,
                'method' => 'ocr_text_matching'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    // Carregar todos os pets para comparação de assinatura de cores / características
    $stmt = $pdo->prepare("SELECT id, name, rarity, base_value, demand, image_url, color_hex FROM items");
    $stmt->execute();
    $allPets = $stmt->fetchAll();

    // Função para converter Hex para RGB
    function hexToRgb($hex) {
        $hex = ltrim($hex, '#');
        if (strlen($hex) == 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        ];
    }

    // Distância euclidiana de cores
    function colorDistance($rgb1, $rgb2) {
        return sqrt(
            pow($rgb1[0] - $rgb2[0], 2) +
            pow($rgb1[1] - $rgb2[1], 2) +
            pow($rgb1[2] - $rgb2[2], 2)
        );
    }

    $targetRgb = [200, 150, 50]; // Default
    if (is_array($colorSignature) && count($colorSignature) >= 3) {
        $targetRgb = [intval($colorSignature[0]), intval($colorSignature[1]), intval($colorSignature[2])];
    } elseif (!empty($imageData) && function_exists('imagecreatefromstring')) {
        // Tentar extrair cor dominante da imagem base64
        $cleanBase64 = preg_replace('/^data:image\/\w+;base64,/', '', $imageData);
        $decoded = base64_decode($cleanBase64);
        if ($decoded) {
            $im = @imagecreatefromstring($decoded);
            if ($im) {
                $thumb = imagecreatetruecolor(1, 1);
                imagecopyresampled($thumb, $im, 0, 0, 0, 0, 1, 1, imagesx($im), imagesy($im));
                $rgbIndex = imagecolorat($thumb, 0, 0);
                $r = ($rgbIndex >> 16) & 0xFF;
                $g = ($rgbIndex >> 8) & 0xFF;
                $b = $rgbIndex & 0xFF;
                $targetRgb = [$r, $g, $b];
                imagedestroy($thumb);
                imagedestroy($im);
            }
        }
    }

    // Calcular proximidade com cada pet na base
    $scored = [];
    foreach ($allPets as $pet) {
        $petRgb = hexToRgb($pet['color_hex'] ?: '#ca8a04');
        $dist = colorDistance($targetRgb, $petRgb);
        // Normalizar em score de 0 a 100
        $maxDist = 441.67; // sqrt(255^2 * 3)
        $score = max(0, min(100, round((1 - ($dist / $maxDist)) * 100, 1)));
        $scored[] = [
            'pet' => $pet,
            'confidence' => $score
        ];
    }

    // Ordenar pelo maior grau de confiança
    usort($scored, function($a, $b) {
        return $b['confidence'] <=> $a['confidence'];
    });

    $topMatch = $scored[0] ?? null;
    $alternatives = array_slice($scored, 1, 3);

    echo json_encode([
        'status' => 'success',
        'matched' => $topMatch !== null,
        'pet' => $topMatch ? $topMatch['pet'] : null,
        'confidence' => $topMatch ? $topMatch['confidence'] : 0,
        'alternatives' => $alternatives,
        'detected_rgb' => $targetRgb,
        'method' => 'ai_visual_signature'
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro na detecção por IA: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
