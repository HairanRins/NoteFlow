<script setup>
import { ref } from 'vue'

const navLinks = [
  { label: 'Features', href: '#features', active: true },
  { label: 'Pricing',  href: '#pricing',  active: false },
]
</script>

<template>
  <nav
    class="fixed top-0 w-full z-50 flex justify-between items-center px-8 h-16
           bg-surface transition-colors duration-300"
  >
    <!-- Left: Logo + Links -->
    <div class="flex items-center gap-8">
      <span class="text-xl font-black text-primary tracking-tighter font-headline">
        NoteFlow
      </span>

      <div class="hidden md:flex gap-6">
        <a
          v-for="link in navLinks"
          :key="link.label"
          :href="link.href"
          :class="[
            'font-headline text-sm tracking-tight transition-colors',
            link.active
              ? 'text-primary border-b-2 border-primary pb-1'
              : 'text-outline-variant hover:text-primary',
          ]"
        >
          {{ link.label }}
        </a>
      </div>
    </div>

    <!-- Right: Search + Actions -->
    <div class="flex items-center gap-6">
      <!-- Fake search bar -->
      <div
        class="hidden md:flex items-center bg-surface-container-low px-3 py-1.5
               rounded-xl border border-outline-variant/20 gap-2 cursor-pointer"
      >
        <span class="material-symbols-outlined text-outline" style="font-size:16px">search</span>
        <span class="text-on-surface-variant text-xs">Search...</span>
        <kbd
          class="ml-3 text-[10px] bg-surface-variant px-1.5 py-0.5 rounded
                 text-outline-variant font-mono"
        >⌘K</kbd>
      </div>

      <span
        class="material-symbols-outlined text-primary cursor-pointer
               hover:bg-surface-variant/50 transition-all p-2 rounded-full"
      >
        settings
      </span>

      <button
        class="primary-gradient text-on-primary font-bold px-5 py-2 rounded-md
               scale-95 active:scale-90 transition-transform"
      >
        Get Started
      </button>
    </div>
  </nav>
</template>