<?php
/**
 * Adopt Me Trade - Aba de Coleção de Itens / Enciclopédia de Valores amvgg.com
 */
?>
<section id="tab-content-collection" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 hidden fade-in">
    <!-- Cabeçalho da Coleção -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6 bg-slate-900/60 p-5 rounded-3xl border border-slate-800">
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-boxes-stacked text-indigo-400"></i>
                <?= __t('tab_collection') ?>
            </h1>
            <p class="text-xs text-slate-400 mt-1">
                Explore todos os pets, ovos e itens com valores calibrados em tempo real com amvgg.com.
            </p>
        </div>

        <button onclick="switchMainTab('trade')" class="px-5 py-2.5 bg-sky-600 hover:bg-sky-500 text-white text-xs font-bold rounded-2xl transition shadow-md shadow-sky-600/20 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            <span>Voltar para Janela de Troca</span>
        </button>
    </div>

    <!-- Barra de Busca Visual com Auto-Complete e Filtros de Categoria -->
    <div class="bg-slate-900/80 rounded-3xl p-5 border border-slate-800 shadow-xl mb-6">
        <div class="flex flex-col md:flex-row gap-4 mb-4">
            <!-- Input de Busca -->
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input 
                    type="text" 
                    id="collection-search-input" 
                    oninput="CollectionApp.filterItems()" 
                    placeholder="<?= __t('search_placeholder') ?>" 
                    class="w-full bg-slate-950/80 border border-slate-700/80 rounded-2xl pl-11 pr-4 py-3 text-sm text-white placeholder-slate-400 focus:outline-none focus:border-sky-500 transition shadow-inner"
                >
            </div>

            <!-- Filtro de Raridade -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0 scrollbar-none">
                <button onclick="CollectionApp.setRarity('all', this)" class="rarity-pill active px-3.5 py-2 rounded-xl text-xs font-bold bg-sky-600 text-white transition whitespace-nowrap">
                    <?= __t('filter_all') ?>
                </button>
                <button onclick="CollectionApp.setRarity('Legendary', this)" class="rarity-pill px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-800 text-amber-400 hover:bg-slate-700 transition whitespace-nowrap">
                    <?= __t('filter_legendary') ?>
                </button>
                <button onclick="CollectionApp.setRarity('Ultra-Rare', this)" class="rarity-pill px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-800 text-purple-400 hover:bg-slate-700 transition whitespace-nowrap">
                    <?= __t('filter_ultra_rare') ?>
                </button>
                <button onclick="CollectionApp.setRarity('Rare', this)" class="rarity-pill px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-800 text-sky-400 hover:bg-slate-700 transition whitespace-nowrap">
                    <?= __t('filter_rare') ?>
                </button>
                <button onclick="CollectionApp.setRarity('Uncommon', this)" class="rarity-pill px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-800 text-emerald-400 hover:bg-slate-700 transition whitespace-nowrap">
                    <?= __t('filter_uncommon') ?>
                </button>
                <button onclick="CollectionApp.setRarity('Common', this)" class="rarity-pill px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-800 text-slate-400 hover:bg-slate-700 transition whitespace-nowrap">
                    <?= __t('filter_common') ?>
                </button>
            </div>
        </div>

        <!-- Abas de Categorias: Pets, Ovos, Veículos, Brinquedos -->
        <div class="flex items-center gap-2 border-t border-slate-800 pt-3">
            <button onclick="CollectionApp.setCategory('pets', this)" class="cat-pill active px-4 py-1.5 rounded-xl text-xs font-bold bg-indigo-600 text-white transition flex items-center gap-1.5">
                <i class="fas fa-paw"></i> Pets
            </button>
            <button onclick="CollectionApp.setCategory('eggs', this)" class="cat-pill px-4 py-1.5 rounded-xl text-xs font-bold bg-slate-800 text-slate-300 hover:text-white transition flex items-center gap-1.5">
                <i class="fas fa-egg"></i> Ovos
            </button>
            <button onclick="CollectionApp.setCategory('vehicles', this)" class="cat-pill px-4 py-1.5 rounded-xl text-xs font-bold bg-slate-800 text-slate-300 hover:text-white transition flex items-center gap-1.5">
                <i class="fas fa-car"></i> Veículos
            </button>
            <button onclick="CollectionApp.setCategory('toys', this)" class="cat-pill px-4 py-1.5 rounded-xl text-xs font-bold bg-slate-800 text-slate-300 hover:text-white transition flex items-center gap-1.5">
                <i class="fas fa-gamepad"></i> Brinquedos
            </button>
        </div>
    </div>

    <!-- Grid de Cards de Pets / Itens da Coleção -->
    <div id="collection-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        <!-- Renderizado dinamicamente via JS -->
    </div>
</section>

<script>
const CollectionApp = {
    currentCategory: 'pets',
    currentRarity: 'all',

    init() {
        this.loadItems();
    },

    setCategory(cat, btn) {
        this.currentCategory = cat;
        document.querySelectorAll('.cat-pill').forEach(b => {
            b.className = 'cat-pill px-4 py-1.5 rounded-xl text-xs font-bold bg-slate-800 text-slate-300 hover:text-white transition flex items-center gap-1.5';
        });
        btn.className = 'cat-pill active px-4 py-1.5 rounded-xl text-xs font-bold bg-indigo-600 text-white transition flex items-center gap-1.5';
        this.loadItems();
    },

    setRarity(rarity, btn) {
        this.currentRarity = rarity;
        document.querySelectorAll('.rarity-pill').forEach(b => {
            b.className = 'rarity-pill px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-800 text-slate-300 hover:bg-slate-700 transition whitespace-nowrap';
        });
        btn.className = 'rarity-pill active px-3.5 py-2 rounded-xl text-xs font-bold bg-sky-600 text-white transition whitespace-nowrap';
        this.loadItems();
    },

    filterItems() {
        this.loadItems();
    },

    async loadItems() {
        const query = document.getElementById('collection-search-input')?.value || '';
        const grid = document.getElementById('collection-grid');
        if (!grid) return;

        try {
            const url = `api/items.php?category=${this.currentCategory}&rarity=${this.currentRarity}&q=${encodeURIComponent(query)}`;
            const res = await fetch(url);
            const json = await res.json();

            grid.innerHTML = '';
            if (json.data && json.data.length > 0) {
                json.data.forEach(item => {
                    const card = document.createElement('div');
                    card.className = 'bg-slate-900/90 rounded-2xl p-4 border border-slate-800 hover:border-sky-500/80 transition-all duration-200 cursor-pointer flex flex-col items-center text-center shadow-lg hover:shadow-sky-500/10 group';
                    card.innerHTML = `
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl font-black mb-3 shadow-md group-hover:scale-105 transition" style="background-color: ${item.color_hex || '#38bdf8'}">
                            <i class="fas fa-paw text-white/95"></i>
                        </div>
                        <span class="px-2 py-0.5 text-[10px] rounded-full font-bold rarity-${item.rarity} mb-1.5">
                            ${item.rarity}
                        </span>
                        <h3 class="font-bold text-sm text-white truncate w-full mb-1">${item.name}</h3>
                        <div class="text-sm font-black text-emerald-400 mb-2">
                            ${item.base_value} pts
                        </div>
                        <span class="text-[10px] text-slate-400 font-semibold">
                            Demanda: <span class="text-slate-200">${item.demand}</span>
                        </span>
                    `;
                    card.onclick = () => this.showItemDetails(item);
                    grid.appendChild(card);
                });
            } else {
                grid.innerHTML = '<div class="col-span-full py-12 text-center text-slate-400">Nenhum item encontrado nesta categoria.</div>';
            }
        } catch (err) {
            grid.innerHTML = '<div class="col-span-full py-12 text-center text-rose-400">Falha ao carregar itens da coleção.</div>';
        }
    },

    showItemDetails(item) {
        const neonVal = (item.base_value * item.neon_multiplier).toFixed(2);
        const megaVal = (item.base_value * item.mega_multiplier).toFixed(2);
        const frVal = (item.base_value * item.fly_multiplier * item.ride_multiplier).toFixed(2);

        AppModal.show({
            title: `Detalhes de Valor: ${item.name}`,
            message: `
                <div class="text-left space-y-4">
                    <div class="flex items-center gap-4 p-3 bg-slate-800/80 rounded-2xl border border-slate-700">
                        <div class="w-16 h-16 rounded-xl flex items-center justify-center text-3xl font-black shadow" style="background-color: ${item.color_hex || '#38bdf8'}">
                            <i class="fas fa-paw text-white"></i>
                        </div>
                        <div>
                            <h4 class="text-base font-bold text-white">${item.name}</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-2 py-0.5 text-xs rounded-full font-bold rarity-${item.rarity}">${item.rarity}</span>
                                <span class="text-xs text-slate-400">Demanda: <b class="text-amber-400">${item.demand}</b></span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="p-3 bg-slate-800/50 rounded-xl border border-slate-700/60">
                            <span class="text-slate-400 block font-semibold mb-0.5">Valor Base:</span>
                            <span class="text-base font-black text-emerald-400">${item.base_value} pts</span>
                        </div>
                        <div class="p-3 bg-slate-800/50 rounded-xl border border-slate-700/60">
                            <span class="text-slate-400 block font-semibold mb-0.5">Fly + Ride (FR):</span>
                            <span class="text-base font-black text-sky-400">${frVal} pts</span>
                        </div>
                        <div class="p-3 bg-slate-800/50 rounded-xl border border-slate-700/60">
                            <span class="text-slate-400 block font-semibold mb-0.5">Neon (N):</span>
                            <span class="text-base font-black text-cyan-400">${neonVal} pts</span>
                        </div>
                        <div class="p-3 bg-slate-800/50 rounded-xl border border-slate-700/60">
                            <span class="text-slate-400 block font-semibold mb-0.5">Mega Neon (M):</span>
                            <span class="text-base font-black text-pink-400">${megaVal} pts</span>
                        </div>
                    </div>

                    <div class="p-3 bg-indigo-950/40 border border-indigo-800/50 rounded-xl text-xs text-indigo-300">
                        <i class="fas fa-info-circle mr-1"></i> Dados verificados semanalmente da base oficial <b>amvgg.com</b>.
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-2">
                        <button type="button" class="py-2.5 px-3 bg-sky-600 hover:bg-sky-500 text-white font-bold rounded-xl text-xs transition" onclick="TradeApp.addItemToSlot('your', ${JSON.stringify(item).replace(/"/g, '&quot;')}); switchMainTab('trade'); AppModal.hide();">
                            + Adicionar à Sua Oferta
                        </button>
                        <button type="button" class="py-2.5 px-3 bg-purple-600 hover:bg-purple-500 text-white font-bold rounded-xl text-xs transition" onclick="TradeApp.addItemToSlot('their', ${JSON.stringify(item).replace(/"/g, '&quot;')}); switchMainTab('trade'); AppModal.hide();">
                            + Adicionar à Oferta Deles
                        </button>
                    </div>
                </div>
            `,
            showCancel: true,
            confirmText: 'Voltar à Troca',
            cancelText: 'Fechar',
            onConfirm: () => switchMainTab('trade')
        });
    }
};

document.addEventListener('DOMContentLoaded', () => CollectionApp.init());
</script>
