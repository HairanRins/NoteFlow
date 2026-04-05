<script setup>
import { ref } from 'vue'
import TheFooter from '@/components/layout/Footer.vue'

const form = ref({
  email:    '',
  password: '',
})

function handleSubmit() {
  // TODO: brancher sur l'API d'authentification
  console.log('Connexion :', form.value)
}
</script>

<template>
  <div
    class="bg-background text-on-background font-body min-h-screen flex flex-col
           selection:bg-primary-container selection:text-on-primary-container"
  >
    <!-- ─── En-tête ─────────────────────────────────────── -->
    <header class="bg-[#0b1326] w-full top-0 z-50">
      <nav
        class="flex justify-between items-center px-6 py-4 w-full max-w-7xl mx-auto"
      >
        <RouterLink
          to="/"
          class="text-xl font-bold tracking-tighter text-primary font-headline"
        >
          NoteFlow
        </RouterLink>
        <a
          href="#"
          class="text-sm font-medium tracking-tight text-outline-variant
                 hover:text-primary transition-colors duration-200"
        >
          Aide
        </a>
      </nav>
    </header>

    <!-- ─── Contenu principal ─────────────────────────────── -->
    <main
      class="flex-grow flex items-center justify-center relative overflow-hidden
             bg-architect-mesh px-6 py-12"
    >
      <!-- Halos ambiance -->
      <div
        class="absolute top-1/4 -left-20 w-96 h-96 bg-primary/10 rounded-full
               blur-[120px] pointer-events-none"
      />
      <div
        class="absolute bottom-1/4 -right-20 w-96 h-96 bg-tertiary/5 rounded-full
               blur-[120px] pointer-events-none"
      />

      <div class="w-full max-w-md mx-auto relative z-10">
        <div class="bg-surface-container-low p-8 md:p-10 rounded-xl">
          <div class="mb-8">
            <h2 class="font-headline text-2xl font-bold text-on-surface">
              Connexion
            </h2>
            <p class="text-on-surface-variant text-sm mt-2 font-body">
              Retrouvez votre espace de travail.
            </p>
          </div>

          <form class="space-y-6" @submit.prevent="handleSubmit">
            <!-- Adresse e-mail -->
            <div class="space-y-2">
              <label
                class="font-label text-[0.6875rem] uppercase tracking-widest
                       text-on-surface-variant font-medium ml-1"
              >
                Adresse e-mail
              </label>
              <div class="relative group">
                <div
                  class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-0
                         bg-primary group-focus-within:h-3/4
                         transition-all duration-300"
                />
                <input
                  v-model="form.email"
                  type="email"
                  placeholder="nom@entreprise.com"
                  autocomplete="email"
                  class="w-full bg-surface-container-lowest border-none
                         text-on-surface py-3 px-4 rounded-md focus:ring-0
                         placeholder:text-on-surface-variant/30 text-sm font-body"
                />
              </div>
            </div>

            <!-- Mot de passe -->
            <div class="space-y-2">
              <label
                class="font-label text-[0.6875rem] uppercase tracking-widest
                       text-on-surface-variant font-medium ml-1"
              >
                Mot de passe
              </label>
              <div class="relative group">
                <div
                  class="absolute left-0 top-1/2 -translate-y-1/2 w-[2px] h-0
                         bg-primary group-focus-within:h-3/4
                         transition-all duration-300"
                />
                <input
                  v-model="form.password"
                  type="password"
                  placeholder="••••••••"
                  autocomplete="current-password"
                  class="w-full bg-surface-container-lowest border-none
                         text-on-surface py-3 px-4 rounded-md focus:ring-0
                         placeholder:text-on-surface-variant/30 text-sm font-body"
                />
              </div>
            </div>

            <!-- Bouton CTA -->
            <div class="pt-4">
              <button
                type="submit"
                class="w-full py-4 rounded-lg bg-gradient-to-br from-primary
                       to-primary-container text-on-primary font-headline
                       font-bold text-sm tracking-wide shadow-lg shadow-primary/10
                       active:scale-[0.98] transition-all duration-150"
              >
                Se connecter
              </button>
            </div>
          </form>

          <!-- Lien inscription -->
          <div class="mt-8 pt-8 flex flex-col items-center gap-4">
            <div
              class="flex items-center gap-2 text-on-surface-variant text-xs"
            >
              <span class="w-8 h-px bg-outline-variant/30" />
              <span>Nouveau ici ?</span>
              <span class="w-8 h-px bg-outline-variant/30" />
            </div>
            <RouterLink
              to="/signup"
              class="text-primary font-headline text-sm font-semibold
                     hover:underline decoration-2 underline-offset-4 transition-all"
            >
              Créer un compte
            </RouterLink>
          </div>
        </div>
      </div>
    </main>

    <!-- ─── Pied de page ──────────────────────────────────── -->
    <TheFooter />
  </div>
</template>

<style scoped>
.bg-architect-mesh {
  background-image: radial-gradient(
    circle at 2px 2px,
    rgba(69, 70, 82, 0.15) 1px,
    transparent 0
  );
  background-size: 32px 32px;
}
</style>
