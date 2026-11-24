<?php
/**
 * Adopt Me Trade - API de Busca Rápida com Auto-Complete
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../database/bd.php';

try {
    $query = isset($_GET['q']) ? trim($_GET['q']) : '';
    if (strlen($query) < 1) {
        echo json_encode(['status' => 'success', 'data' => []]);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT id, name, rarity, base_value, demand, image_url, color_hex
        FROM items 
        WHERE name LIKE ? 
        ORDER BY 
            CASE 
                WHEN name LIKE ? THEN 1
                WHEN name LIKE ? THEN 2
                ELSE 3 
            END,
            base_value DESC
        LIMIT 10
    ");
    $stmt->execute(['%' . $query . '%', $query . '%', '%' . $query . '%']);
    $results = $stmt->fetchAll();

    echo json_encode([
        'status' => 'success',
        'query'  => $query,
        'count'  => count($results),
        'data'   => $results
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Erro na busca: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
