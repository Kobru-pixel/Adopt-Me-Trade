<?php
/**
 * Adopt Me Trade - Componente Header & Barra de Navegação
 */
?>
<header class="bg-slate-900/80 backdrop-blur-md border-b border-slate-800 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <!-- Logo e Título -->
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-sky-500/20">
                <i class="fas fa-arrows-rotate text-xl"></i>
            </div>
            <div>
                <a href="index.php" class="brand-font text-xl md:text-2xl font-bold bg-gradient-to-r from-sky-400 via-indigo-300 to-pink-400 bg-clip-text text-transparent">
                    Adopt Me Trade
                </a>
                <span class="hidden sm:inline-block ml-2 px-2 py-0.5 text-[10px] uppercase font-black bg-indigo-900/60 text-indigo-300 border border-indigo-700/50 rounded-full">
                    amvgg.com
                </span>
            </div>
        </div>

        <!-- Abas de Navegação -->
        <nav class="hidden md:flex items-center gap-2 bg-slate-950/60 p-1.5 rounded-2xl border border-slate-800">
            <button id="nav-btn-trade" onclick="switchMainTab('trade')" class="px-4 py-1.5 rounded-xl text-sm font-bold transition flex items-center gap-2 bg-sky-600 text-white shadow">
                <i class="fas fa-scale-balanced"></i>
                <span><?= __t('tab_trade') ?></span>
            </button>
            <button id="nav-btn-collection" onclick="switchMainTab('collection')" class="px-4 py-1.5 rounded-xl text-sm font-bold transition flex items-center gap-2 text-slate-300 hover:text-white hover:bg-slate-800">
                <i class="fas fa-boxes-stacked"></i>
                <span><?= __t('tab_collection') ?></span>
            </button>
            <button id="nav-btn-scanner" onclick="PetScanner.openModal('your')" class="px-4 py-1.5 rounded-xl text-sm font-bold transition flex items-center gap-2 text-slate-300 hover:text-white hover:bg-slate-800">
                <i class="fas fa-wand-magic-sparkles text-amber-400"></i>
                <span><?= __t('tab_scanner') ?></span>
            </button>
        </nav>

        <!-- Ações do Header (Sincronização + Idiomas) -->
        <div class="flex items-center gap-3">
            <!-- Botão Sincronizar amvgg -->
            <button id="btn-sync-amvgg" onclick="TradeApp.syncAmvgg()" title="<?= __t('sync_status') ?>" class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-sky-400 border border-slate-700 rounded-xl text-xs font-bold transition shadow-sm">
                <i class="fas fa-sync-alt"></i>
                <span>amvgg.com</span>
            </button>

            <!-- Seletor de Idioma -->
            <div class="relative">
                <select id="lang-selector" onchange="I18N.setLanguage(this.value)" class="bg-slate-800 border border-slate-700 text-slate-200 text-xs rounded-xl px-2.5 py-1.5 focus:ring-2 focus:ring-sky-500 outline-none cursor-pointer">
                    <option value="pt-BR" <?= APP_LANG === 'pt-BR' ? 'selected' : '' ?>>🇧🇷 PT</option>
                    <option value="en-US" <?= APP_LANG === 'en-US' ? 'selected' : '' ?>>🇺🇸 EN</option>
                    <option value="es-ES" <?= APP_LANG === 'es-ES' ? 'selected' : '' ?>>🇪🇸 ES</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Navegação Mobile (Inferior ou Sub-header) -->
    <div class="md:hidden flex items-center justify-around bg-slate-950/90 border-t border-slate-800 py-2 px-2">
        <button onclick="switchMainTab('trade')" class="text-xs font-bold text-sky-400 flex flex-col items-center gap-1">
            <i class="fas fa-scale-balanced text-base"></i>
            <span><?= __t('tab_trade') ?></span>
        </button>
        <button onclick="switchMainTab('collection')" class="text-xs font-bold text-slate-400 hover:text-white flex flex-col items-center gap-1">
            <i class="fas fa-boxes-stacked text-base"></i>
            <span><?= __t('tab_collection') ?></span>
        </button>
        <button onclick="PetScanner.openModal('your')" class="text-xs font-bold text-amber-400 flex flex-col items-center gap-1">
            <i class="fas fa-camera text-base"></i>
            <span><?= __t('tab_scanner') ?></span>
        </button>
    </div>
</header>
