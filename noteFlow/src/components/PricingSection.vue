<script setup lang="ts">
</script>

<template>
  <!-- PRICING -->
  <section id="pricing" class="py-20 px-6 bg-surface">
    <div class="max-w-6xl mx-auto">
      <div class="section-label text-on-surface-variant font-label text-xs uppercase tracking-wider mb-3 text-left">Tarifs</div>
      <h2 class="section-title font-headline font-bold text-4xl md:text-5xl text-on-background mb-4 text-left">
        Commence gratuit,<br /><em class="italic">évolue selon tes besoins</em>
      </h2>
      <p class="section-sub font-body text-on-surface-variant max-w-2xl mb-12 text-left">
        NoteFlow est gratuit pour un usage personnel. Le SaaS permet équipes et
        workspaces partagés.
      </p>
      <div class="pricing-grid grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="price-card bg-surface-container border border-outline rounded-xl p-8 relative">
          <div class="price-name font-label text-xs text-on-surface-variant uppercase tracking-wider mb-4">Gratuit</div>
          <div class="mb-4">
            <span class="price-amount font-headline text-4xl text-on-background">0€</span>
            <span class="price-period font-body text-sm text-on-surface-variant">/mois</span>
          </div>
          <div class="price-desc font-body text-sm text-on-surface-variant mb-6">
            Pour découvrir et usage personnel illimité.
          </div>
          <ul class="price-features space-y-3 mb-8">
            <li class="font-body text-sm text-on-surface-variant flex items-start gap-2">
              <span class="text-primary">→</span>
              Notes illimitées
            </li>
            <li class="font-body text-sm text-on-surface-variant flex items-start gap-2">
              <span class="text-primary">→</span>
              Graphe mental
            </li>
            <li class="font-body text-sm text-on-surface-variant flex items-start gap-2">
              <span class="text-primary">→</span>
              Note quotidienne
            </li>
            <li class="font-body text-sm text-on-surface-variant flex items-start gap-2">
              <span class="text-primary">→</span>
              Sync 1 appareil
            </li>
            <li class="font-body text-sm text-on-surface-variant flex items-start gap-2">
              <span class="text-primary">→</span>
              Markdown complet
            </li>
          </ul>
          <RouterLink
            to="/signup"
            class="price-btn w-full bg-primary text-on-primary font-headline font-semibold py-3 rounded-lg hover:bg-primary-container transition-colors inline-block text-center"
          >
            Commencer
          </RouterLink>
        </div>
        <div class="price-card bg-surface-container border-2 border-primary rounded-xl p-8 relative">
          <div class="popular-badge absolute -top-3 right-6 bg-primary text-on-primary text-xs font-label px-3 py-1 rounded-full">★ Populaire</div>
          <div class="price-name font-label text-xs text-on-surface-variant uppercase tracking-wider mb-4">Pro</div>
          <div class="mb-4">
            <span class="price-amount font-headline text-4xl text-on-background">7€</span>
            <span class="price-period font-body text-sm text-on-surface-variant">/mois</span>
          </div>
          <div class="price-desc font-body text-sm text-on-surface-variant mb-6">
            Pour les power users et créateurs de contenu.
          </div>
          <ul class="price-features space-y-3 mb-8">
            <li class="font-body text-sm text-on-surface-variant flex items-start gap-2">
              <span class="text-primary">→</span>
              Tout du gratuit
            </li>
            <li class="font-body text-sm text-on-surface-variant flex items-start gap-2">
              <span class="text-primary">→</span>
              Sync multi-appareils
            </li>
            <li class="font-body text-sm text-on-surface-variant flex items-start gap-2">
              <span class="text-primary">→</span>
              Canvas toile
            </li>
            <li class="font-body text-sm text-on-surface-variant flex items-start gap-2">
              <span class="text-primary">→</span>
              Export PDF / Markdown
            </li>
            <li class="font-body text-sm text-on-surface-variant flex items-start gap-2">
              <span class="text-primary">→</span>
              Historique versions
            </li>
            <li class="font-body text-sm text-on-surface-variant flex items-start gap-2">
              <span class="text-primary">→</span>
              Thèmes personnalisés
            </li>
          </ul>
          <RouterLink
            to="/signup"
            class="price-btn w-full bg-primary text-on-primary font-headline font-semibold py-3 rounded-lg hover:bg-primary-container transition-colors inline-block text-center"
          >
            Essai 14 jours gratuit
          </RouterLink>
        </div>
        <div class="price-card bg-surface-container border border-outline rounded-xl p-8 relative">
          <div class="price-name font-label text-xs text-on-surface-variant uppercase tracking-wider mb-4">Équipe</div>
          <div class="mb-4">
            <span class="price-amount font-headline text-4xl text-on-background">19€</span>
            <span class="price-period font-body text-sm text-on-surface-variant">/utilisateur/mois</span>
          </div>
          <div class="price-desc font-body text-sm text-on-surface-variant mb-6">
            Workspaces partagés, notes collaboratives en temps réel.
          </div>
          <ul class="price-features space-y-3 mb-8">
            <li class="font-body text-sm text-on-surface-variant flex items-start gap-2">
              <span class="text-primary">→</span>
              Tout du Pro
            </li>
            <li class="font-body text-sm text-on-surface-variant flex items-start gap-2">
              <span class="text-primary">→</span>
              Workspace partagé
            </li>
            <li class="font-body text-sm text-on-surface-variant flex items-start gap-2">
              <span class="text-primary">→</span>
              Collaboration temps réel
            </li>
            <li class="font-body text-sm text-on-surface-variant flex items-start gap-2">
              <span class="text-primary">→</span>
              Admin + permissions
            </li>
            <li class="font-body text-sm text-on-surface-variant flex items-start gap-2">
              <span class="text-primary">→</span>
              SSO / SAML
            </li>
            <li class="font-body text-sm text-on-surface-variant flex items-start gap-2">
              <span class="text-primary">→</span>
              Support prioritaire
            </li>
          </ul>
          <button class="price-btn w-full bg-surface border border-outline text-on-background font-headline font-semibold py-3 rounded-lg hover:bg-surface-container transition-colors">
            Contacter l'équipe
          </button>
        </div>
      </div>
    </div>
  </section>
</template>
