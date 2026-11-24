<?php
/**
 * Adopt Me Trade - Modal de Seleção de Itens para Slot de Troca
 */
?>
<div id="item-picker-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700 w-full max-w-2xl rounded-3xl shadow-2xl flex flex-col max-h-[90vh] overflow-hidden fade-in">
        <!-- Topo do Modal -->
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sky-500/20 text-sky-400 flex items-center justify-center font-bold text-base">
                    <i class="fas fa-paw"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white"><?= __t('select_item') ?></h3>
                    <p class="text-xs text-slate-400">Escolha o pet ou item para adicionar à troca</p>
                </div>
            </div>
            <button onclick="TradeApp.closeItemPicker()" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white flex items-center justify-center transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Opções Especiais de Pet (Fly, Ride, Neon, Mega) -->
        <div class="px-5 py-3 bg-slate-950/60 border-b border-slate-800/80">
            <span class="text-xs font-bold text-slate-400 block mb-2">Variações do Pet:</span>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs">
                <label class="flex items-center gap-2 p-2 bg-slate-800/80 hover:bg-slate-700/80 rounded-xl cursor-pointer transition border border-slate-700/50">
                    <input type="checkbox" id="picker-fly" class="w-4 h-4 accent-sky-500">
                    <span class="font-bold text-sky-400">Fly (Voar)</span>
                </label>
                <label class="flex items-center gap-2 p-2 bg-slate-800/80 hover:bg-slate-700/80 rounded-xl cursor-pointer transition border border-slate-700/50">
                    <input type="checkbox" id="picker-ride" class="w-4 h-4 accent-indigo-500">
                    <span class="font-bold text-indigo-400">Ride (Montar)</span>
                </label>
                <label class="flex items-center gap-2 p-2 bg-slate-800/80 hover:bg-slate-700/80 rounded-xl cursor-pointer transition border border-slate-700/50">
                    <input type="checkbox" id="picker-neon" class="w-4 h-4 accent-cyan-500">
                    <span class="font-bold text-cyan-400">Neon</span>
                </label>
                <label class="flex items-center gap-2 p-2 bg-slate-800/80 hover:bg-slate-700/80 rounded-xl cursor-pointer transition border border-slate-700/50">
                    <input type="checkbox" id="picker-mega" class="w-4 h-4 accent-pink-500">
                    <span class="font-bold text-pink-400">Mega Neon</span>
                </label>
            </div>
        </div>

        <!-- Barra de Busca Rápida no Modal -->
        <div class="p-4 border-b border-slate-800">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input 
                    type="text" 
                    id="picker-search-input" 
                    placeholder="Filtrar por nome do pet (ex: Shadow Dragon, Cow, Turtle)..." 
                    class="w-full bg-slate-950 border border-slate-700 rounded-xl pl-11 pr-4 py-2.5 text-sm text-white placeholder-slate-400 focus:outline-none focus:border-sky-500 transition"
                >
            </div>
        </div>

        <!-- Grade de Itens Disponíveis -->
        <div id="picker-items-grid" class="p-5 overflow-y-auto grid grid-cols-1 sm:grid-cols-2 gap-3 flex-1">
            <!-- Renderizado dinamicamente via TradeApp.filterPickerItems() -->
        </div>

        <!-- Rodapé do Modal -->
        <div class="p-4 border-t border-slate-800 flex justify-end">
            <button onclick="TradeApp.closeItemPicker()" class="px-5 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-bold transition">
                <?= __t('cancel') ?>
            </button>
        </div>
    </div>
</div>
