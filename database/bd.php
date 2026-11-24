<?php
/**
 * Adopt Me Trade - Conexão Segura com Banco de Dados via PDO
 * Compatível com MySQL e Fallback SQLite
 */

require_once __DIR__ . '/../config/config.php';

class Database {
    private static ?PDO $instance = null;
    private static string $driverUsed = 'mysql';

    /**
     * Retorna a instância única da conexão PDO (Singleton Pattern)
     */
    public static function getConnection(): PDO {
        if (self::$instance !== null) {
            return self::$instance;
        }

        // Tentar conexão principal com MySQL
        try {
            $dsn = sprintf(
                "mysql:host=%s;port=%s;dbname=%s;charset=%s",
                DB_HOST,
                DB_PORT,
                DB_NAME,
                DB_CHARSET
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];

            self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            self::$driverUsed = 'mysql';
            return self::$instance;
        } catch (PDOException $e) {
            // Em caso de indisponibilidade momentânea do serviço MySQL local,
            // acionar fallback resiliente via SQLite para garantir que o sistema funcione sem erros 500
            return self::initSqliteFallback();
        }
    }

    /**
     * Fallback para SQLite embutido com auto-provisionamento
     */
    private static function initSqliteFallback(): PDO {
        $sqliteFile = __DIR__ . '/adopt_me_trade.sqlite';
        $isFirstRun = !file_exists($sqliteFile);

        $dsn = "sqlite:" . $sqliteFile;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        self::$instance = new PDO($dsn, null, null, $options);
        self::$driverUsed = 'sqlite';

        if ($isFirstRun) {
            self::seedSqliteDatabase(self::$instance);
        }

        return self::$instance;
    }

    /**
     * Popula banco SQLite embutido se for a primeira execução
     */
    private static function seedSqliteDatabase(PDO $pdo): void {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                slug TEXT UNIQUE NOT NULL,
                name_pt TEXT NOT NULL,
                name_en TEXT NOT NULL,
                name_es TEXT NOT NULL,
                icon TEXT DEFAULT 'paw'
            );

            CREATE TABLE IF NOT EXISTS items (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                category_id INTEGER NOT NULL,
                rarity TEXT NOT NULL,
                base_value REAL NOT NULL DEFAULT 1.0,
                fly_multiplier REAL NOT NULL DEFAULT 1.10,
                ride_multiplier REAL NOT NULL DEFAULT 1.08,
                neon_multiplier REAL NOT NULL DEFAULT 3.80,
                mega_multiplier REAL NOT NULL DEFAULT 15.00,
                demand TEXT NOT NULL DEFAULT 'Medium',
                image_url TEXT DEFAULT '',
                color_hex TEXT DEFAULT '#FFB703',
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS trades (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_session TEXT NOT NULL,
                user_value REAL NOT NULL,
                target_value REAL NOT NULL,
                difference REAL NOT NULL,
                result TEXT NOT NULL,
                status TEXT NOT NULL DEFAULT 'Pending',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS trade_items (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                trade_id INTEGER NOT NULL,
                side TEXT NOT NULL,
                item_id INTEGER NOT NULL,
                is_fly INTEGER DEFAULT 0,
                is_ride INTEGER DEFAULT 0,
                is_neon INTEGER DEFAULT 0,
                is_mega INTEGER DEFAULT 0,
                calculated_value REAL NOT NULL
            );

            CREATE TABLE IF NOT EXISTS sync_logs (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                source TEXT NOT NULL DEFAULT 'amvgg.com',
                items_synced INTEGER NOT NULL DEFAULT 0,
                status TEXT NOT NULL DEFAULT 'SUCCESS',
                log_message TEXT,
                synced_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Executar carga de categorias
        $pdo->exec("
            INSERT OR IGNORE INTO categories (id, slug, name_pt, name_en, name_es, icon) VALUES
            (1, 'pets', 'Pets', 'Pets', 'Mascotas', 'paw'),
            (2, 'eggs', 'Ovos', 'Eggs', 'Huevos', 'egg'),
            (3, 'vehicles', 'Veículos', 'Vehicles', 'Vehículos', 'car'),
            (4, 'toys', 'Brinquedos', 'Toys', 'Juguetes', 'gamepad');
        ");

        // Executar carga de pets calibrada com amvgg.com
        $items = [
            [1, 'Shadow Dragon', 1, 'Legendary', 135.00, 1.05, 1.03, 3.85, 15.20, 'Extreme', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/shadow_dragon.png', '#1e1b4b'],
            [2, 'Bat Dragon', 1, 'Legendary', 125.00, 1.05, 1.03, 3.85, 15.10, 'Extreme', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/bat_dragon.png', '#ea580c'],
            [3, 'Giraffe', 1, 'Legendary', 85.00, 1.08, 1.05, 3.80, 14.90, 'High', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/giraffe.png', '#ca8a04'],
            [4, 'Frost Dragon', 1, 'Legendary', 58.00, 1.08, 1.05, 3.80, 14.80, 'Extreme', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/frost_dragon.png', '#0284c7'],
            [5, 'Owl', 1, 'Legendary', 48.00, 1.10, 1.07, 3.75, 14.50, 'Extreme', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/owl.png', '#78350f'],
            [6, 'Parrot', 1, 'Legendary', 39.00, 1.10, 1.07, 3.75, 14.40, 'High', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/parrot.png', '#dc2626'],
            [7, 'Evil Unicorn', 1, 'Legendary', 30.00, 1.12, 1.08, 3.70, 14.20, 'High', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/evil_unicorn.png', '#991b1b'],
            [8, 'Crow', 1, 'Legendary', 28.00, 1.12, 1.08, 3.70, 14.10, 'High', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/crow.png', '#334155'],
            [9, 'Turtle', 1, 'Legendary', 12.00, 1.15, 1.10, 3.70, 14.00, 'Extreme', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/turtle.png', '#16a34a'],
            [10, 'Kangaroo', 1, 'Legendary', 8.50, 1.15, 1.10, 3.65, 13.80, 'High', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/kangaroo.png', '#d97706'],
            [11, 'Cow', 1, 'Rare', 11.00, 1.18, 1.12, 3.75, 14.20, 'Extreme', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/cow.png', '#f472b6'],
            [12, 'Strawberry Shortcake Bat Dragon', 1, 'Legendary', 10.50, 1.15, 1.10, 3.70, 14.00, 'High', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/ssbd.png', '#f43f5e'],
            [13, 'Frost Fury', 1, 'Legendary', 3.80, 1.20, 1.15, 3.60, 13.50, 'Medium', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/frost_fury.png', '#38bdf8'],
            [14, 'Elephant', 1, 'Rare', 7.00, 1.18, 1.12, 3.65, 13.70, 'High', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/elephant.png', '#94a3b8'],
            [15, 'Flamingo', 1, 'Ultra-Rare', 9.00, 1.18, 1.12, 3.65, 13.70, 'High', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/flamingo.png', '#ec4899'],
            [16, 'Crocodile', 1, 'Ultra-Rare', 7.50, 1.18, 1.12, 3.65, 13.70, 'Medium', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/crocodile.png', '#15803d'],
            [17, 'Lion', 1, 'Ultra-Rare', 7.80, 1.18, 1.12, 3.65, 13.70, 'Medium', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/lion.png', '#b45309'],
            [18, 'Unicorn', 1, 'Legendary', 2.50, 1.25, 1.20, 3.60, 13.50, 'High', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/unicorn.png', '#e879f9'],
            [19, 'Dragon', 1, 'Legendary', 1.80, 1.25, 1.20, 3.50, 13.00, 'Medium', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/dragon.png', '#ef4444'],
            [20, 'Dodo', 1, 'Legendary', 2.00, 1.25, 1.20, 3.50, 13.00, 'Medium', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/dodo.png', '#a855f7'],
            [21, 'T-Rex', 1, 'Legendary', 2.20, 1.25, 1.20, 3.50, 13.00, 'Medium', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/trex.png', '#22c55e'],
            [22, 'Shiba Inu', 1, 'Ultra-Rare', 0.40, 1.30, 1.25, 3.40, 12.00, 'Low', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/shiba.png', '#f59e0b'],
            [23, 'Red Panda', 1, 'Ultra-Rare', 0.35, 1.30, 1.25, 3.40, 12.00, 'Low', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/red_panda.png', '#b91c1c'],
            [24, 'Beaver', 1, 'Rare', 0.20, 1.35, 1.30, 3.30, 11.00, 'Low', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/beaver.png', '#78350f'],
            [25, 'Bunny', 1, 'Rare', 0.22, 1.35, 1.30, 3.30, 11.00, 'Low', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/bunny.png', '#94a3b8'],
            [26, 'Puma', 1, 'Uncommon', 0.12, 1.40, 1.35, 3.20, 10.00, 'Low', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/puma.png', '#a16207'],
            [27, 'Snow Cat', 1, 'Uncommon', 0.10, 1.40, 1.35, 3.20, 10.00, 'Low', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/snow_cat.png', '#64748b'],
            [28, 'Cat', 1, 'Common', 0.05, 1.50, 1.40, 3.00, 9.00, 'Low', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/cat.png', '#475569'],
            [29, 'Dog', 1, 'Common', 0.05, 1.50, 1.40, 3.00, 9.00, 'Low', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/dog.png', '#854d0e'],
            [30, 'Safari Egg', 2, 'Legendary', 52.00, 1.00, 1.00, 1.00, 1.00, 'High', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/safari_egg.png', '#ca8a04'],
            [31, 'Farm Egg', 2, 'Legendary', 40.00, 1.00, 1.00, 1.00, 1.00, 'High', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/farm_egg.png', '#15803d'],
            [32, 'Aussie Egg', 2, 'Legendary', 4.50, 1.00, 1.00, 1.00, 1.00, 'Medium', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/aussie_egg.png', '#d97706'],
            [33, 'Neon Black Scooter', 3, 'Legendary', 140.00, 1.00, 1.00, 1.00, 1.00, 'High', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/scooter.png', '#0f172a'],
            [34, 'RGB Monster Truck', 3, 'Legendary', 4.00, 1.00, 1.00, 1.00, 1.00, 'Medium', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/monster_truck.png', '#6366f1'],
            [35, 'Candy Cannon', 4, 'Legendary', 155.00, 1.00, 1.00, 1.00, 1.00, 'Extreme', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/candy_cannon.png', '#ec4899'],
            [36, 'Tombstone Ghostify', 4, 'Legendary', 45.00, 1.00, 1.00, 1.00, 1.00, 'High', 'https://raw.githubusercontent.com/Kobru-pixel/Adopt-Me-Trade/main/assets/pets/tombstone.png', '#64748b']
        ];

        $stmt = $pdo->prepare("
            INSERT INTO items (id, name, category_id, rarity, base_value, fly_multiplier, ride_multiplier, neon_multiplier, mega_multiplier, demand, image_url, color_hex)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        foreach ($items as $item) {
            $stmt->execute($item);
        }
    }

    public static function getDriverUsed(): string {
        return self::$driverUsed;
    }
}

// Obter conexão global $pdo
$pdo = Database::getConnection();
