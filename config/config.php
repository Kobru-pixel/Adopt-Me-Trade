<?php
/**
 * Adopt Me Trade - Configurações Gerais da Aplicação
 */

// Iniciar sessão nativa caso ainda não esteja ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configurações do Banco de Dados MySQL
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'adopt_me_trade');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

// Configurações da Aplicação
define('APP_NAME', 'Adopt Me Trade');
define('APP_VERSION', '2.0.0');
define('AMVGG_URL', 'https://amvgg.com/');
define('TRADE_LOCK_SECONDS', 5);

// Idioma padrão e suportados
$supported_languages = ['pt-BR', 'en-US', 'es-ES'];
if (isset($_GET['lang']) && in_array($_GET['lang'], $supported_languages)) {
    $_SESSION['app_lang'] = $_GET['lang'];
} elseif (!isset($_SESSION['app_lang'])) {
    $_SESSION['app_lang'] = 'pt-BR';
}
define('APP_LANG', $_SESSION['app_lang']);
