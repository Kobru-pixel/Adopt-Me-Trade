<?php
/**
 * Adopt Me Trade - Modal Customizado do Sistema
 * Substitui caixas de diálogo nativas alert() e confirm() por UI elegante em Tailwind
 */
?>
<div id="custom-modal-backdrop" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700 w-full max-w-md rounded-3xl shadow-2xl p-6 text-center transform transition-all duration-200">
        <!-- Ícone / Cabeçalho -->
        <div class="w-12 h-12 mx-auto rounded-2xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-2xl font-bold mb-4 shadow">
            <i class="fas fa-bell"></i>
        </div>

        <h3 id="custom-modal-title" class="text-lg font-bold text-white mb-2">
            <?= __t('modal_title') ?>
        </h3>

        <div id="custom-modal-body" class="text-sm text-slate-300 mb-6 leading-relaxed">
            <!-- Mensagem dinâmica -->
        </div>

        <!-- Botões de Ação -->
        <div class="flex items-center justify-center gap-3">
            <button id="custom-modal-cancel" type="button" class="hidden px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl text-xs font-bold transition">
                <?= __t('cancel') ?>
            </button>
            <button id="custom-modal-confirm" type="button" class="px-6 py-2.5 bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-500 hover:to-indigo-500 text-white rounded-xl text-xs font-black shadow-lg shadow-sky-600/30 transition">
                <?= __t('confirm') ?>
            </button>
        </div>
    </div>
</div>
