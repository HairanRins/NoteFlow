<script setup lang="ts">
const currentYear = new Date().getFullYear()

const footerLinks = [
  { label: 'Privacy',   href: '#' },
  { label: 'Terms',     href: '#' },
  { label: 'Community', href: '#' },
  { label: 'Discord',   href: '#' },
]
</script>

<template>
  <footer
    class="bg-surface-container-lowest flex flex-col md:flex-row justify-between items-center
           px-12 py-12 border-t border-outline-variant/20"
  >
    <p class="text-xs tracking-wide uppercase text-outline-variant mb-6 md:mb-0">
      © {{ currentYear }} NoteFlow. Built for the Digital Architect.
    </p>

    <div class="flex gap-8">
      <a
        v-for="link in footerLinks"
        :key="link.label"
        :href="link.href"
        class="text-xs tracking-wide uppercase text-outline-variant
               hover:text-primary transition-colors"
      >
        {{ link.label }}
      </a>
    </div>
  </footer>
</template>