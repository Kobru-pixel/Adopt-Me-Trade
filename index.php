<?php
/**
 * Adopt Me Trade - Calculadora de Valores amvgg.com & Scanner IA
 * Ponto de Entrada Principal da Aplicação
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/i18n.php';
require_once __DIR__ . '/database/bd.php';
?>
<!DOCTYPE html>
<html lang="<?= substr(APP_LANG, 0, 2) ?>" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= __t('app_title') ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Estilos Customizados & Efeitos Adopt Me -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col antialiased selection:bg-sky-500 selection:text-white">

    <!-- Cabeçalho & Navegação Principal -->
    <?php include __DIR__ . '/components/header.php'; ?>

    <!-- Conteúdo Principal Dinâmico (Abas) -->
    <main class="flex-1 pb-16">
        <!-- Aba 1: Janela de Troca (Trade Window) -->
        <?php include __DIR__ . '/components/trade_view.php'; ?>

        <!-- Aba 2: Coleção de Pets & Valores amvgg.com -->
        <?php include __DIR__ . '/components/collection_view.php'; ?>
    </main>

    <!-- Modais e Componentes Globais -->
    <?php include __DIR__ . '/components/item_modal.php'; ?>
    <?php include __DIR__ . '/components/scanner_modal.php'; ?>
    <?php include __DIR__ . '/components/custom_modal.php'; ?>
    <?php include __DIR__ . '/components/toast.php'; ?>

    <!-- Scripts JavaScript Modulares -->
    <script src="assets/js/toast.js"></script>
    <script src="assets/js/modal.js"></script>
    <script src="assets/js/i18n.js"></script>
    <script src="assets/js/scanner.js"></script>
    <script src="assets/js/trade.js"></script>

    <!-- Alternador de Abas Global -->
    <script>
        function switchMainTab(tab) {
            const tradeContent = document.getElementById('tab-content-trade');
            const collectionContent = document.getElementById('tab-content-collection');
            const tradeNavBtn = document.getElementById('nav-btn-trade');
            const collectionNavBtn = document.getElementById('nav-btn-collection');

            if (tab === 'trade') {
                tradeContent?.classList.remove('hidden');
                collectionContent?.classList.add('hidden');
                if (tradeNavBtn) tradeNavBtn.className = 'px-4 py-1.5 rounded-xl text-sm font-bold transition flex items-center gap-2 bg-sky-600 text-white shadow';
                if (collectionNavBtn) collectionNavBtn.className = 'px-4 py-1.5 rounded-xl text-sm font-bold transition flex items-center gap-2 text-slate-300 hover:text-white hover:bg-slate-800';
            } else if (tab === 'collection') {
                tradeContent?.classList.add('hidden');
                collectionContent?.classList.remove('hidden');
                if (tradeNavBtn) tradeNavBtn.className = 'px-4 py-1.5 rounded-xl text-sm font-bold transition flex items-center gap-2 text-slate-300 hover:text-white hover:bg-slate-800';
                if (collectionNavBtn) collectionNavBtn.className = 'px-4 py-1.5 rounded-xl text-sm font-bold transition flex items-center gap-2 bg-indigo-600 text-white shadow';
                if (window.CollectionApp) window.CollectionApp.loadItems();
            }
        }
    </script>
</body>
</html>
