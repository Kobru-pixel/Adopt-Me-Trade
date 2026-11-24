<?php
/**
 * Adopt Me Trade - API de Listagem e Filtragem de Itens
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../database/bd.php';

try {
    $category = isset($_GET['category']) ? trim($_GET['category']) : '';
    $rarity   = isset($_GET['rarity']) ? trim($_GET['rarity']) : '';
    $search   = isset($_GET['q']) ? trim($_GET['q']) : '';
    $sort     = isset($_GET['sort']) ? trim($_GET['sort']) : 'value_desc';

    $sql = "SELECT i.*, c.name_pt as category_name_pt, c.name_en as category_name_en, c.slug as category_slug 
            FROM items i 
            JOIN categories c ON i.category_id = c.id 
            WHERE 1=1";
    $params = [];

    if ($category !== '' && $category !== 'all') {
        $sql .= " AND (c.slug = ? OR c.id = ?)";
        $params[] = $category;
        $params[] = $category;
    }

    if ($rarity !== '' && $rarity !== 'all') {
        $sql .= " AND i.rarity = ?";
        $params[] = $rarity;
    }

    if ($search !== '') {
        $sql .= " AND i.name LIKE ?";
        $params[] = '%' . $search . '%';
    }

    // Ordenação
    switch ($sort) {
        case 'value_asc':
            $sql .= " ORDER BY i.base_value ASC";
            break;
        case 'name_asc':
            $sql .= " ORDER BY i.name ASC";
            break;
        case 'value_desc':
        default:
            $sql .= " ORDER BY i.base_value DESC";
            break;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $items = $stmt->fetchAll();

    echo json_encode([
        'status' => 'success',
        'count'  => count($items),
        'data'   => $items
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Erro ao carregar itens da base: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
