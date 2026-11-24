<?php
/**
 * Adopt Me Trade - Modal do Scanner IA / Câmera / Leitura de Tela do Roblox
 */
?>
<div id="scanner-modal" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700 w-full max-w-xl rounded-3xl shadow-2xl flex flex-col overflow-hidden fade-in">
        <!-- Topo -->
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-500 to-pink-500 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-pink-500/20">
                    <i class="fas fa-wand-magic-sparkles"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white"><?= __t('scan_title') ?></h3>
                    <p class="text-xs text-slate-400">Reconhecimento instantâneo por Visão Computacional</p>
                </div>
            </div>
            <button onclick="PetScanner.closeModal()" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white flex items-center justify-center transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Abas do Scanner: Câmera, Upload, Tela Roblox -->
        <div class="grid grid-cols-3 border-b border-slate-800 bg-slate-950/50 p-1 gap-1 text-xs font-bold text-center">
            <button onclick="PetScanner.startCamera()" class="py-2.5 rounded-xl hover:bg-slate-800 transition text-sky-400 flex items-center justify-center gap-1.5">
                <i class="fas fa-camera"></i> Câmera / Web
            </button>
            <button onclick="document.getElementById('scanner-file-input').click()" class="py-2.5 rounded-xl hover:bg-slate-800 transition text-amber-400 flex items-center justify-center gap-1.5">
                <i class="fas fa-upload"></i> Foto / Arquivo
            </button>
            <button onclick="PetScanner.startScreenCapture()" class="py-2.5 rounded-xl hover:bg-slate-800 transition text-pink-400 flex items-center justify-center gap-1.5">
                <i class="fas fa-desktop"></i> Ler Tela Jogo
            </button>
            <input type="file" id="scanner-file-input" accept="image/*" class="hidden" onchange="PetScanner.handleFileUpload(event)">
        </div>

        <!-- Área de Visualização da Mídia / Câmera -->
        <div class="p-5 flex flex-col items-center">
            <div id="scanner-media-container" class="w-full h-64 bg-slate-950 rounded-2xl overflow-hidden relative border border-slate-800 flex items-center justify-center">
                <!-- Vídeo Stream -->
                <video id="scanner-video" autoplay playsinline class="w-full h-full object-cover"></video>
                <!-- Preview de Foto Carregada -->
                <img id="scanner-preview-img" class="w-full h-full object-contain hidden" alt="Pet Preview">

                <!-- Placeholder quando câmera inativa -->
                <div id="scanner-placeholder" class="hidden absolute inset-0 flex flex-col items-center justify-center p-6 text-center text-slate-500">
                    <i class="fas fa-video-slash text-4xl mb-2"></i>
                    <p class="text-xs">Clique nas abas acima para iniciar a Câmera, Enviar uma Foto ou Capturar a Janela do Roblox.</p>
                </div>

                <!-- Miras de Enquadramento IA -->
                <div class="absolute inset-8 border-2 border-dashed border-sky-400/50 rounded-2xl pointer-events-none flex items-center justify-center">
                    <span class="text-[10px] uppercase font-bold tracking-widest text-sky-300/70 bg-black/50 px-2 py-0.5 rounded-md">
                        Área de Foco do Pet
                    </span>
                </div>

                <!-- Loading IA -->
                <div id="scanner-loading" class="hidden absolute inset-0 bg-black/70 flex flex-col items-center justify-center text-white">
                    <i class="fas fa-circle-notch fa-spin text-3xl text-sky-400 mb-2"></i>
                    <span class="text-xs font-bold"><?= __t('analyzing') ?></span>
                </div>
            </div>

            <!-- Botão de Ação: Capturar Frame -->
            <button onclick="PetScanner.captureFrame()" class="mt-4 px-8 py-3 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white font-black text-sm rounded-2xl shadow-lg shadow-sky-600/30 transition flex items-center gap-2">
                <i class="fas fa-crosshairs"></i>
                <span>Analisar e Reconhecer Pet</span>
            </button>

            <!-- Caixa de Resultado da Detecção IA -->
            <div id="scanner-result-box" class="w-full mt-4 p-4 rounded-2xl bg-slate-800/80 border border-slate-700 hidden">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div id="detected-pet-thumb" class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl font-bold shadow bg-sky-500">
                            <i class="fas fa-paw text-white"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 id="detected-pet-name" class="font-bold text-white text-base">Shadow Dragon</h4>
                                <span id="detected-pet-rarity" class="px-2 py-0.5 text-xs rounded-full font-bold rarity-Legendary">Legendary</span>
                            </div>
                            <span class="text-xs text-slate-400">
                                Confiança da IA: <b id="detected-pet-confidence" class="text-emerald-400">96.5%</b>
                            </span>
                        </div>
                    </div>
                    <div id="detected-pet-value" class="text-lg font-black text-emerald-400">135.00 pts</div>
                </div>

                <button onclick="PetScanner.addDetectedToTrade()" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs transition flex items-center justify-center gap-2 shadow-md shadow-emerald-600/20">
                    <i class="fas fa-plus"></i>
                    <span>Adicionar à Troca</span>
                </button>
            </div>
        </div>
    </div>
</div>
