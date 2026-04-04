<script setup>
// CtaSection.vue
</script>

<template>
  <section class="py-24 px-8">
    <div
      class="max-w-4xl mx-auto glass-effect rounded-2xl p-12 text-center
             border border-outline-variant/20 relative overflow-hidden"
    >
      <!-- Top accent line -->
      <div class="absolute top-0 left-0 w-full h-1 primary-gradient" />

      <h2 class="text-4xl font-headline font-extrabold text-on-background mb-6">
        Build your second brain today.
      </h2>

      <p class="text-on-surface-variant text-lg mb-10 max-w-xl mx-auto">
        Join 20,000+ digital architects organizing their thoughts with NoteFlow.
      </p>

      <div class="flex flex-col sm:flex-row justify-center gap-4">
        <button
          class="primary-gradient text-on-primary px-8 py-4 rounded-xl font-bold
                 text-lg shadow-xl hover:shadow-primary/20 transition-all
                 active:scale-95"
        >
          Download for Desktop
        </button>
        <button
          class="bg-surface-container-highest text-on-surface border border-outline-variant/30
                 px-8 py-4 rounded-xl font-bold text-lg hover:bg-surface-bright
                 transition-all active:scale-95"
        >
          Try Web App
        </button>
      </div>
    </div>
  </section>
</template>