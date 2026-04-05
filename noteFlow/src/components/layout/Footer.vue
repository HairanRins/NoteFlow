<script setup lang="ts">
import logoImg from '../../assets/images/NoteFlow.png'
const currentYear = new Date().getFullYear()

const footerLinks = [
  { label: 'Docs', href: '#' },
  { label: 'GitHub', href: '#' },
  { label: 'Changelog', href: '#' },
  { label: 'Roadmap', href: '#' },
  { label: 'Confidentialité', href: '#' },
  { label: 'CGU', href: '#' },
]
</script>

<template>
  <footer class="bg-surface text-on-surface-variant py-16 px-6">
    <div class="max-w-6xl mx-auto">
      <div class="flex flex-col lg:flex-row justify-between items-start gap-8 mb-8">
        <div class="flex items-center gap-4">
          <img 
            :src="logoImg" 
            alt="NoteFlow" 
            class="w-10 h-10 object-contain rounded"
          />
          <div>
            <div class="footer-brand font-headline text-2xl font-bold text-on-background">NoteFlow</div>
            <div class="footer-tagline font-label text-sm text-on-surface-variant">Ton second cerveau</div>
          </div>
        </div>
        <div class="footer-links flex flex-wrap gap-6">
          <a
            v-for="link in footerLinks"
            :key="link.label"
            :href="link.href"
            class="font-body text-sm text-on-surface-variant hover:text-primary transition-colors"
          >
            {{ link.label }}
          </a>
        </div>
      </div>
      <div class="footer-copy font-label text-xs text-on-surface-variant border-t border-outline/20 pt-8">
        © {{ currentYear }} NoteFlow · Built by Rin's Andrianina
      </div>
    </div>
  </footer>
</template>