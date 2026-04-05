<script setup lang="ts">
</script>

<template>
  <!-- CTA FINAL -->
  <section class="py-20 px-6 bg-surface-container text-center">
    <div class="max-w-4xl mx-auto">
      <div class="section-label text-on-surface-variant font-label text-xs uppercase tracking-wider mb-4">Prêt à capturer tes idées ?</div>
      <h2 class="font-headline font-bold text-4xl md:text-5xl text-on-background mb-6 max-w-2xl mx-auto">
        Lance ton second cerveau
        <em class="italic text-primary">aujourd'hui</em>
      </h2>
      <p class="font-body text-on-surface-variant mb-9 max-w-lg mx-auto">
        Gratuit pour toujours. Aucune carte de crédit requise.
      </p>
      <div class="flex flex-col sm:flex-row gap-3 justify-center mb-6">
        <RouterLink
          to="/signup"
          class="primary-gradient text-on-primary font-headline font-semibold py-3 px-8 rounded-lg hover:opacity-90 transition-opacity inline-block text-center"
        >
          Créer mon espace — gratuit
        </RouterLink>
        <button class="bg-surface border border-outline text-on-background font-headline font-semibold py-3 px-8 rounded-lg hover:bg-surface-container transition-colors">
          Voir sur GitHub
        </button>
      </div>
      <p class="font-label text-xs text-on-surface-variant">
        Déployable en self-hosted · Laravel Forge · Railway · Hetzner
      </p>
    </div>
  </section>
</template>
