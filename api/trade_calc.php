<?php
/**
 * Adopt Me Trade - API de Cálculo de Valores e Avaliação W/F/L
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../database/bd.php';

try {
    $inputRaw = file_get_contents('php://input');
    $input = json_decode($inputRaw, true) ?: $_POST;

    $yourItems = $input['your_items'] ?? [];
    $theirItems = $input['their_items'] ?? [];
    $saveTrade = !empty($input['save_trade']);

    // Função interna para calcular valor de um item individual com multiplicadores amvgg
    function calculateItemValue($itemData, PDO $pdo) {
        $itemId = intval($itemData['id'] ?? 0);
        if ($itemId <= 0) return 0.0;

        $stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
        $stmt->execute([$itemId]);
        $dbItem = $stmt->fetch();
        if (!$dbItem) return 0.0;

        $base = floatval($dbItem['base_value']);
        $isFly = !empty($itemData['is_fly']);
        $isRide = !empty($itemData['is_ride']);
        $isNeon = !empty($itemData['is_neon']);
        $isMega = !empty($itemData['is_mega']);

        $multiplier = 1.0;
        if ($isMega) {
            $multiplier *= floatval($dbItem['mega_multiplier']);
        } elseif ($isNeon) {
            $multiplier *= floatval($dbItem['neon_multiplier']);
        }

        if ($isFly) {
            $multiplier *= floatval($dbItem['fly_multiplier']);
        }
        if ($isRide) {
            $multiplier *= floatval($dbItem['ride_multiplier']);
        }

        return round($base * $multiplier, 2);
    }

    $yourTotal = 0.0;
    $yourDetails = [];
    foreach ($yourItems as $item) {
        $val = calculateItemValue($item, $pdo);
        $yourTotal += $val;
        $yourDetails[] = array_merge($item, ['calculated_value' => $val]);
    }

    $theirTotal = 0.0;
    $theirDetails = [];
    foreach ($theirItems as $item) {
        $val = calculateItemValue($item, $pdo);
        $theirTotal += $val;
        $theirDetails[] = array_merge($item, ['calculated_value' => $val]);
    }

    $yourTotal = round($yourTotal, 2);
    $theirTotal = round($theirTotal, 2);
    $diff = round($theirTotal - $yourTotal, 2);

    // Avaliação da troca
    $resultKey = 'Fair';
    $fairnessPercent = 50; // 50% = perfeitamente equilibrado

    $sumTotal = $yourTotal + $theirTotal;
    if ($sumTotal > 0) {
        $ratio = $theirTotal / ($sumTotal);
        $fairnessPercent = round($ratio * 100, 1);

        if ($yourTotal == 0 && $theirTotal > 0) {
            $resultKey = 'Big Win';
        } elseif ($theirTotal == 0 && $yourTotal > 0) {
            $resultKey = 'Big Lose';
        } else {
            $percentageDiff = (($theirTotal - $yourTotal) / $yourTotal) * 100;
            if ($percentageDiff >= 20.0 || $diff >= 15.0) {
                $resultKey = 'Big Win';
            } elseif ($percentageDiff >= 5.0 || $diff >= 2.0) {
                $resultKey = 'Win';
            } elseif ($percentageDiff <= -20.0 || $diff <= -15.0) {
                $resultKey = 'Big Lose';
            } elseif ($percentageDiff <= -5.0 || $diff <= -2.0) {
                $resultKey = 'Lose';
            } else {
                $resultKey = 'Fair';
            }
        }
    }

    // Opcional: salvar no banco de dados
    $tradeId = null;
    if ($saveTrade && $sumTotal > 0) {
        $sessionId = session_id() ?: 'guest_' . uniqid();
        $status = $input['status'] ?? 'Pending';

        $stmt = $pdo->prepare("
            INSERT INTO trades (user_session, user_value, target_value, difference, result, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$sessionId, $yourTotal, $theirTotal, $diff, $resultKey, $status]);
        $tradeId = $pdo->lastInsertId();
    }

    echo json_encode([
        'status' => 'success',
        'trade_id' => $tradeId,
        'your_value' => $yourTotal,
        'their_value' => $theirTotal,
        'difference' => $diff,
        'result' => $resultKey,
        'fairness_percentage' => $fairnessPercent,
        'your_items' => $yourDetails,
        'their_items' => $theirDetails
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erro no cálculo da troca: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
