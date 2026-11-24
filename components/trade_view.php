<?php
/**
 * Adopt Me Trade - Janela de Troca Principal (Trade Window)
 */
?>
<section id="tab-content-trade" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 fade-in">
    <!-- Barra Superior de Controle e Scanner Rápido -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 bg-slate-900/60 p-4 rounded-3xl border border-slate-800">
        <div>
            <h1 class="text-xl md:text-2xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-handshake text-sky-400"></i>
                <?= __t('tab_trade') ?>
            </h1>
            <p class="text-xs text-slate-400">
                <?= __t('scan_desc') ?>
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <button onclick="PetScanner.openModal('your')" class="px-3.5 py-2 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-md shadow-sky-600/20">
                <i class="fas fa-camera"></i>
                <span><?= __t('scan_camera') ?></span>
            </button>
            <button onclick="PetScanner.startScreenCapture(); PetScanner.openModal('their');" class="px-3.5 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-md shadow-pink-600/20">
                <i class="fas fa-desktop"></i>
                <span><?= __t('scan_screen') ?></span>
            </button>
            <button onclick="TradeApp.clearAll()" class="px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl text-xs font-semibold transition border border-slate-700">
                <i class="fas fa-trash-can mr-1"></i>
                <?= __t('clear_all') ?>
            </button>
        </div>
    </div>

    <!-- Área de Troca: Grade de Dois Lados (Você x Eles) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Lado 1: Sua Oferta (Your Offer) -->
        <div class="bg-slate-900/80 rounded-3xl p-5 border border-slate-800 shadow-xl flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center font-bold text-sm">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white"><?= __t('your_offer') ?></h2>
                            <span class="text-xs text-slate-400">9 slots disponíveis</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-400 block"><?= __t('total_value') ?></span>
                        <span id="your-total-value" class="text-xl font-black text-sky-400">0.00</span>
                    </div>
                </div>

                <!-- 9 Slots Grid -->
                <div id="your-slots-container" class="grid grid-cols-3 gap-3">
                    <!-- Gerado dinamicamente via trade.js -->
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between">
                <button onclick="TradeApp.openItemPicker('your', 0)" class="text-xs font-bold text-sky-400 hover:text-sky-300 transition flex items-center gap-1">
                    <i class="fas fa-plus-circle"></i> <?= __t('add_item') ?>
                </button>
                <button onclick="PetScanner.openModal('your')" class="text-xs text-slate-400 hover:text-white transition flex items-center gap-1">
                    <i class="fas fa-camera text-sky-400"></i> Escanear Pet
                </button>
            </div>
        </div>

        <!-- Lado 2: Oferta Deles (Their Offer) -->
        <div class="bg-slate-900/80 rounded-3xl p-5 border border-slate-800 shadow-xl flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold text-sm">
                            <i class="fas fa-user-group"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white"><?= __t('their_offer') ?></h2>
                            <span class="text-xs text-slate-400">9 slots disponíveis</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-400 block"><?= __t('total_value') ?></span>
                        <span id="their-total-value" class="text-xl font-black text-purple-400">0.00</span>
                    </div>
                </div>

                <!-- 9 Slots Grid -->
                <div id="their-slots-container" class="grid grid-cols-3 gap-3">
                    <!-- Gerado dinamicamente via trade.js -->
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between">
                <button onclick="TradeApp.openItemPicker('their', 0)" class="text-xs font-bold text-purple-400 hover:text-purple-300 transition flex items-center gap-1">
                    <i class="fas fa-plus-circle"></i> <?= __t('add_item') ?>
                </button>
                <button onclick="PetScanner.openModal('their')" class="text-xs text-slate-400 hover:text-white transition flex items-center gap-1">
                    <i class="fas fa-camera text-purple-400"></i> Escanear Pet Deles
                </button>
            </div>
        </div>
    </div>

    <!-- Painel de Justiça da Troca (W/F/L Fairness Meter) -->
    <div class="bg-slate-900/90 rounded-3xl p-6 border border-slate-800 shadow-2xl mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block mb-1">
                    <?= __t('fairness_meter') ?>
                </span>
                <div id="trade-verdict-label" class="text-2xl font-black text-amber-400 tracking-wide">
                    ⚖️ Fair (Troca Justa)
                </div>
            </div>

            <div class="text-center md:text-right">
                <span class="text-xs text-slate-400 block"><?= __t('difference') ?></span>
                <span id="trade-difference" class="text-2xl font-black text-amber-400">0.00 pts</span>
            </div>
        </div>

        <!-- Barra Gradiente W/F/L -->
        <div class="wfl-meter mb-3">
            <div id="wfl-indicator" class="wfl-indicator" style="left: 50%;"></div>
        </div>

        <!-- Legenda do Barômetro -->
        <div class="flex justify-between text-[11px] font-bold text-slate-400 px-1">
            <span class="text-rose-400">💀 Big Lose (-20%+)</span>
            <span class="text-rose-300">Lose</span>
            <span class="text-amber-300">⚖️ Fair</span>
            <span class="text-emerald-300">Win</span>
            <span class="text-emerald-400">🏆 Big Win (+20%+)</span>
        </div>
    </div>

    <!-- Barra de Decisão do Jogador (Timer de 5 Segundos Obrigatório) -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950/40 to-slate-900 rounded-3xl p-6 border border-slate-800 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <div id="trade-lock-countdown" class="text-sm font-semibold text-slate-300 flex items-center gap-2 mb-1">
                <i class="fas fa-lock text-amber-400"></i>
                <?= __t('lock_countdown', ['s' => '5']) ?>
            </div>
            <p class="text-xs text-slate-400">
                Regra Adopt Me: Após adicionar ou alterar pets, a trava de 5 segundos é ativada para proteger contra trocas acidentais.
            </p>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
            <button id="btn-decline-trade" onclick="TradeApp.declineTrade()" disabled class="flex-1 md:flex-initial px-6 py-3 bg-rose-600 hover:bg-rose-500 disabled:opacity-40 disabled:hover:bg-rose-600 text-white font-bold rounded-2xl transition shadow-lg shadow-rose-600/20 flex items-center justify-center gap-2 text-sm">
                <i class="fas fa-xmark"></i>
                <span><?= __t('decline') ?></span>
            </button>
            <button id="btn-accept-trade" onclick="TradeApp.acceptTrade()" disabled class="flex-1 md:flex-initial px-8 py-3 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-40 disabled:hover:bg-emerald-600 text-white font-black rounded-2xl transition shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 text-sm">
                <i class="fas fa-check"></i>
                <span><?= __t('accept') ?></span>
            </button>
        </div>
    </div>
</section>
