<script setup lang="ts">
</script>

<template>
  <!-- USER FLOW -->
  <section id="user-flow" class="py-20 px-6 bg-surface-container">
    <div class="max-w-6xl mx-auto">
      <div class="section-label text-on-surface-variant font-label text-xs uppercase tracking-wider mb-3 text-left">User Flow</div>
      <h2 class="section-title font-headline font-bold text-4xl md:text-5xl text-on-background mb-6 text-left">
        Du vide à la clarté,<br /><em class="italic">en quelques frappes</em>
      </h2>
      <div class="flow-steps grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-0 bg-surface border border-outline rounded-xl overflow-hidden">
        <div class="flow-step p-6 border-r border-outline">
          <div class="flow-num text-on-surface-variant font-label text-xs mb-3">01</div>
          <div class="flow-step-title font-headline font-semibold text-on-background mb-2">Arriver</div>
          <div class="flow-step-desc font-body text-sm text-on-surface-variant">
            Écran accueil avec note quotidienne pré-ouverte et dernières
            notes.
          </div>
        </div>
        <div class="flow-step p-6 border-r border-outline">
          <div class="flow-num text-on-surface-variant font-label text-xs mb-3">02</div>
          <div class="flow-step-title font-headline font-semibold text-on-background mb-2">Capturer</div>
          <div class="flow-step-desc font-body text-sm text-on-surface-variant mb-2">Raccourci global.</div>
          <div><span class="flow-kbd font-label text-xs bg-surface-container border border-outline rounded px-2 py-1">Ctrl+K</span></div>
        </div>
        <div class="flow-step p-6 border-r border-outline">
          <div class="flow-num text-on-surface-variant font-label text-xs mb-3">03</div>
          <div class="flow-step-title font-headline font-semibold text-on-background mb-2">Éditer</div>
          <div class="flow-step-desc font-body text-sm text-on-surface-variant">
            Clic sur note → éditeur → auto-save toutes les 2s.
          </div>
        </div>
        <div class="flow-step p-6 border-r border-outline">
          <div class="flow-num text-on-surface-variant font-label text-xs mb-3">04</div>
          <div class="flow-step-title font-headline font-semibold text-on-background mb-2">Lier</div>
          <div class="flow-step-desc font-body text-sm text-on-surface-variant">
            Tape <span class="font-label text-xs bg-surface-container border border-outline rounded px-2 py-1">[[</span> pour lier deux notes
            instantanément.
          </div>
        </div>
        <div class="flow-step p-6 border-r border-outline">
          <div class="flow-num text-on-surface-variant font-label text-xs mb-3">05</div>
          <div class="flow-step-title font-headline font-semibold text-on-background mb-2">Explorer</div>
          <div class="flow-step-desc font-body text-sm text-on-surface-variant">
            Vue graphe pour naviguer visuellement entre idées.
          </div>
        </div>
        <div class="flow-step p-6">
          <div class="flow-num text-on-surface-variant font-label text-xs mb-3">06</div>
          <div class="flow-step-title font-headline font-semibold text-on-background mb-2">Sync</div>
          <div class="flow-step-desc font-body text-sm text-on-surface-variant">
            Offline → actions stockées → retour en ligne → push auto.
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mt-8">
        <div class="bg-surface border border-outline rounded-xl p-6">
          <div class="feat-name font-headline font-semibold text-on-background mb-4 flex items-center gap-2">
            📅 Note quotidienne
          </div>
          <div class="bg-surface-container border border-outline rounded-lg p-4 mb-4">
            <div class="flex items-start gap-4">
              <div class="text-2xl">☀️</div>
              <div class="flex-1">
                <div class="font-label text-xs text-tertiary uppercase tracking-wider mb-1">Aujourd'hui</div>
                <div class="font-headline text-lg text-on-background">Dimanche 5 Avril 2026</div>
                <div class="text-sm text-on-surface-variant mt-1">3 notes liées · 2 tâches en attente</div>
              </div>
            </div>
          </div>
          <p class="font-body text-sm text-on-surface-variant leading-relaxed">
            Ouverte automatiquement au démarrage. Modèle personnalisable.
            Résumé des notes de la veille.
          </p>
        </div>
        <div class="bg-surface border border-outline rounded-xl p-6">
          <div class="feat-name font-headline font-semibold text-on-background mb-4 flex items-center gap-2">
            🖼️ Toile canvas
          </div>
          <div class="bg-surface-container border border-outline rounded-lg p-4 mb-4 relative h-48">
            <div class="bg-surface border border-outline rounded-lg px-3 py-2 absolute text-sm font-body" style="top: 30px; left: 40px;">
              Idée principale
            </div>
            <div class="bg-surface border border-outline rounded-lg px-3 py-2 absolute text-sm font-body" style="top: 90px; left: 160px;">
              Sous-point A
            </div>
            <div class="bg-surface border border-outline rounded-lg px-3 py-2 absolute text-sm font-body" style="top: 30px; right: 40px;">
              Référence
            </div>
            <svg width="100%" height="100%" class="absolute top-0 left-0 pointer-events-none">
              <line x1="130" y1="45" x2="165" y2="100" stroke="var(--color-outline)" stroke-width="1.5" stroke-dasharray="4" />
              <line x1="200" y1="45" x2="230" y2="100" stroke="var(--color-outline)" stroke-width="1.5" stroke-dasharray="4" />
            </svg>
            <span class="absolute bottom-3 right-4 font-label text-xs text-on-surface-variant">Espace infini →</span>
          </div>
          <p class="font-body text-sm text-on-surface-variant leading-relaxed">
            Glisse-dépose, connexions visuelles, zoom et pan. Pensée
            non-linéaire.
          </p>
        </div>
      </div>

      <div class="flex items-center gap-4 flex-wrap mt-6">
        <div class="bg-surface border border-outline rounded-lg px-4 py-2 flex items-center gap-2">
          <div class="w-2 h-2 rounded-full bg-primary animate-pulse"></div>
          <span class="font-label text-sm text-on-surface-variant">Synchronisé · il y a 12 secondes</span>
        </div>
        <div class="bg-surface border border-outline rounded-lg px-4 py-2 flex items-center gap-2">
          <div class="w-2 h-2 rounded-full bg-tertiary"></div>
          <span class="font-label text-sm text-on-surface-variant">Mode hors-ligne · 3 actions en attente</span>
        </div>
      </div>
    </div>
  </section>
</template>
