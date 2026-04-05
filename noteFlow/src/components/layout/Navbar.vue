<script setup>
import { ref } from 'vue'
import logoImg from '../../assets/images/NoteFlow.png'

const navLinks = [
  { label: 'Fonctionnalités', href: '#features', active: true },
  { label: 'Flux', href: '#flux', active: false },
  { label: 'Tarifs', href: '#pricing', active: false },
  { label: 'Docs', href: '#docs', active: false },
]
</script>

<template>
  <nav
    class="fixed top-0 w-full z-50 flex justify-between items-center px-8 h-16
           bg-surface transition-colors duration-300"
  >
    <!-- Logo -->
    <div class="logo flex items-center gap-3">
      <div class="logo-mark w-8 h-8 flex items-center justify-center">
        <img 
          :src="logoImg" 
          alt="NoteFlow" 
          class="w-full h-full object-contain rounded"
        />
      </div>
      <span class="logo-name text-xl font-black text-primary tracking-tighter font-headline">
        NoteFlow
      </span>
      <span class="logo-tag text-xs text-on-surface-variant font-label">
        v1.0
      </span>
    </div>

    <!-- Navigation Links -->
    <div class="nav-links hidden md:flex gap-6">
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

    <!-- CTA Button -->
    <button class="nav-cta primary-gradient text-on-primary font-headline font-bold px-5 py-2 rounded-md
                     scale-95 active:scale-90 transition-transform">
      Commencer — gratuit
    </button>
  </nav>
</template>