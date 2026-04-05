<script setup lang="ts">
import { ref } from 'vue'
import heroImg from '../assets/images/NoteFlow.png'

const count = ref(0)
</script>

<template>
  <section class="hero">
    <div class="max-w-4xl mx-auto text-center space-y-8 flex-grow">
      <!-- Hero Image -->
      <div class="relative inline-block">
        <img 
          :src="heroImg" 
          alt="NoteFlow Hero" 
          class="w-48 h-48 mx-auto mb-8 drop-shadow-lg"
        />
      </div>

      <!-- Content -->
      <div class="space-y-6">
        <h1 class="hero-title">
          <span class="bg-gradient-to-r from-primary to-primary-container bg-clip-text text-transparent">Ton esprit,<br /><em class="italic">organisé sans effort</em></span>
        </h1>
        
        <p class="hero-sub">
          Capture, connecte et retrouve tes idées en quelques frappes. Une app de
          notes avec graphe mental, édition markdown, notes quotidiennes et
          synchronisation offline.
        </p>
      </div>

      <!-- CTA Buttons -->
      <div class="hero-actions">
        <button class="btn-primary">
          Essayer gratuitement
        </button>
        
        <button class="btn-secondary">
          Voir la démo live
        </button>
      </div>

      <!-- Feature flags -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-12">
        <div class="space-y-2">
          <div class="text-3xl font-bold text-primary">{{ count + 100 }}</div>
          <p class="text-sm text-on-surface-variant">Active Users</p>
        </div>
        <div class="space-y-2">
          <div class="text-3xl font-bold text-tertiary">5K+</div>
          <p class="text-sm text-on-surface-variant">Notes Created</p>
        </div>
        <div class="space-y-2">
          <div class="text-3xl font-bold text-secondary">99%</div>
          <p class="text-sm text-on-surface-variant">Uptime</p>
        </div>
      </div>
    </div>

    <!-- MARQUEE -->
    <div class="marquee-wrap w-full">
      <div class="marquee-inner animate-marquee">
        <span class="marquee-item">Markdown editor <span class="marquee-dot">●</span></span>
        <span class="marquee-item">Graphe mental <span class="marquee-dot">●</span></span>
        <span class="marquee-item">Note quotidienne <span class="marquee-dot">●</span></span>
        <span class="marquee-item">Sync offline-first <span class="marquee-dot">●</span></span>
        <span class="marquee-item">Liens [[bidirectionnels]] <span class="marquee-dot">●</span></span>
        <span class="marquee-item">VILT Stack <span class="marquee-dot">●</span></span>
        <span class="marquee-item">Toile canvas <span class="marquee-dot">●</span></span>
        <span class="marquee-item">Recherche instantanée <span class="marquee-dot">●</span></span>
        <span class="marquee-item">Tags & filtres <span class="marquee-dot">●</span></span>
        <span class="marquee-item">SaaS-ready <span class="marquee-dot">●</span></span>
        <span class="marquee-item">Markdown editor <span class="marquee-dot">●</span></span>
        <span class="marquee-item">Graphe mental <span class="marquee-dot">●</span></span>
        <span class="marquee-item">Note quotidienne <span class="marquee-dot">●</span></span>
        <span class="marquee-item">Sync offline-first <span class="marquee-dot">●</span></span>
        <span class="marquee-item">Liens [[bidirectionnels]] <span class="marquee-dot">●</span></span>
        <span class="marquee-item">VILT Stack <span class="marquee-dot">●</span></span>
        <span class="marquee-item">Toile canvas <span class="marquee-dot">●</span></span>
        <span class="marquee-item">Recherche instantanée <span class="marquee-dot">●</span></span>
      </div>
    </div>

    <!-- FEATURES -->
    <section class="py-20 px-6 bg-surface">
      <div class="max-w-6xl mx-auto">
        <div class="section-label text-on-surface-variant font-label text-xs uppercase tracking-wider mb-3 text-left">Fonctionnalités</div>
        <h2 class="section-title font-headline font-bold text-4xl md:text-5xl text-on-background mb-4 text-left">
          Tout ce dont tu as besoin,<br /><em class="italic">rien de superflu</em>
        </h2>
        <p class="section-sub font-body text-on-surface-variant max-w-2xl mb-12 text-left">
          NoteFlow distille le meilleur d'Obsidian et Notion dans une interface
          épurée, construite pour la vélocité.
        </p>
        <div class="features-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div class="feat-card bg-surface-container border border-outline rounded-xl p-6 hover:border-primary transition-all duration-200 relative">
            <div class="feat-icon w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-xl mb-4">🧠</div>
            <div class="feat-name font-headline font-semibold text-on-background mb-2">Graphe mental interactif</div>
            <div class="feat-desc font-body text-sm text-on-surface-variant">
              Visualise les connexions entre tes notes. Navigue par clic, zoome,
              filtre par tag. Chaque nœud est une idée.
            </div>
            <div class="feat-tag absolute top-6 right-6 bg-primary/10 text-primary text-xs font-label px-2 py-1 rounded">Nouveau</div>
          </div>
          <div class="feat-card bg-surface-container border border-outline rounded-xl p-6 hover:border-primary transition-all duration-200 relative">
            <div class="feat-icon w-10 h-10 rounded-lg bg-tertiary/10 flex items-center justify-center text-xl mb-4">📅</div>
            <div class="feat-name font-headline font-semibold text-on-background mb-2">Note quotidienne</div>
            <div class="feat-desc font-body text-sm text-on-surface-variant">
              Une note créée automatiquement chaque jour. Ouverture au démarrage
              avec date, météo, rappels liés et résumé de la veille.
            </div>
            <div class="feat-tag absolute top-6 right-6 bg-primary/10 text-primary text-xs font-label px-2 py-1 rounded">Nouveau</div>
          </div>
          <div class="feat-card bg-surface-container border border-outline rounded-xl p-6 hover:border-primary transition-all duration-200 relative">
            <div class="feat-icon w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center text-xl mb-4">🖼️</div>
            <div class="feat-name font-headline font-semibold text-on-background mb-2">Toile (Canvas)</div>
            <div class="feat-desc font-body text-sm text-on-surface-variant">
              Espace visuel libre. Dispose tes notes, idées et images sur une
              toile infinie. Connecte par flèches.
            </div>
            <div class="feat-tag absolute top-6 right-6 bg-primary/10 text-primary text-xs font-label px-2 py-1 rounded">Nouveau</div>
          </div>
          <div class="feat-card bg-surface-container border border-outline rounded-xl p-6 hover:border-primary transition-all duration-200">
            <div class="feat-icon w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-xl mb-4">✍️</div>
            <div class="feat-name font-headline font-semibold text-on-background mb-2">Éditeur Markdown</div>
            <div class="feat-desc font-body text-sm text-on-surface-variant">
              Syntaxe markdown complète, auto-save, aperçu en temps réel. Liens
              <code class="font-label text-xs bg-surface-container px-1 py-0.5 rounded">[[wiki]]</code>
              avec suggestions.
            </div>
          </div>
          <div class="feat-card bg-surface-container border border-outline rounded-xl p-6 hover:border-primary transition-all duration-200">
            <div class="feat-icon w-10 h-10 rounded-lg bg-surface-variant flex items-center justify-center text-xl mb-4">🔗</div>
            <div class="feat-name font-headline font-semibold text-on-background mb-2">Liens bidirectionnels</div>
            <div class="feat-desc font-body text-sm text-on-surface-variant">
              Tape
              <code class="font-label text-xs bg-surface-container px-1 py-0.5 rounded">[[Nom]]</code>
              dans une note, le lien apparaît des deux côtés automatiquement.
              SQL sous le capot.
            </div>
          </div>
          <div class="feat-card bg-surface-container border border-outline rounded-xl p-6 hover:border-primary transition-all duration-200">
            <div class="feat-icon w-10 h-10 rounded-lg bg-tertiary/10 flex items-center justify-center text-xl mb-4">⚡</div>
            <div class="feat-name font-headline font-semibold text-on-background mb-2">Capture rapide</div>
            <div class="feat-desc font-body text-sm text-on-surface-variant">
              Raccourci
              <kbd class="font-label text-xs bg-surface-container border border-outline rounded px-1 py-0.5">Ctrl+K</kbd>
              → input → Entrée. Note créée en moins de 2 secondes depuis
              n'importe où.
            </div>
          </div>
          <div class="feat-card bg-surface-container border border-outline rounded-xl p-6 hover:border-primary transition-all duration-200">
            <div class="feat-icon w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center text-xl mb-4">🔍</div>
            <div class="feat-name font-headline font-semibold text-on-background mb-2">Recherche plein-texte</div>
            <div class="feat-desc font-body text-sm text-on-surface-variant">
              Résultats instantanés sur titre, contenu et tags. Cache Vue côté
              client, zéro latence perçue.
            </div>
          </div>
          <div class="feat-card bg-surface-container border border-outline rounded-xl p-6 hover:border-primary transition-all duration-200 relative">
            <div class="feat-icon w-10 h-10 rounded-lg bg-surface-variant flex items-center justify-center text-xl mb-4">📶</div>
            <div class="feat-name font-headline font-semibold text-on-background mb-2">Offline-first + Sync</div>
            <div class="feat-desc font-body text-sm text-on-surface-variant">
              Travaille sans connexion. Les actions sont stockées localement et
              synchronisées automatiquement dès le retour en ligne.
            </div>
            <div class="feat-tag absolute top-6 right-6 bg-surface-container-high text-on-surface-variant text-xs font-label px-2 py-1 rounded">Bêta</div>
          </div>
          <div class="feat-card bg-surface-container border border-outline rounded-xl p-6 hover:border-primary transition-all duration-200">
            <div class="feat-icon w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-xl mb-4">#</div>
            <div class="feat-name font-headline font-semibold text-on-background mb-2">Tags & filtres</div>
            <div class="feat-desc font-body text-sm text-on-surface-variant">
              Organise par hashtags. Filtre la liste des notes en temps réel.
              Combine plusieurs tags pour naviguer.
            </div>
          </div>
        </div>
      </div>
    </section>
  </section>
</template>
