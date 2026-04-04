<script setup lang="ts">
import { ref } from 'vue'
import heroImg from '../assets/hero.png'

const count = ref(0)
</script>

<template>
  <section class="min-h-[calc(100vh-64px)] flex items-center justify-center px-6 py-24 bg-gradient-to-b from-surface to-surface-container">
    <div class="max-w-4xl mx-auto text-center space-y-8">
      <!-- Hero Image -->
      <div class="relative inline-block">
        <img 
          :src="heroImg" 
          alt="NoteFlow Hero" 
          class="w-48 h-48 mx-auto mb-8 drop-shadow-lg"
        />
      </div>

      <!-- Content -->
      <div class="space-y-4">
        <h1 class="font-headline font-bold text-5xl md:text-6xl text-on-background leading-tight">
          Welcome to <span class="bg-gradient-to-r from-primary to-primary-container bg-clip-text text-transparent">NoteFlow</span>
        </h1>
        
        <p class="font-body text-lg text-on-surface-variant max-w-2xl mx-auto leading-relaxed">
          Organize your thoughts, connect your ideas, and flow with your creativity. 
          Built for the digital architect in you.
        </p>
      </div>

      <!-- CTA Buttons -->
      <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6">
        <button 
          class="px-8 py-3 bg-primary text-on-primary font-headline font-bold rounded-xl
                 hover:bg-primary-container transition-all duration-200 shadow-lg hover:shadow-xl"
        >
          Get Started
        </button>
        
        <button 
          class="px-8 py-3 bg-surface-container text-on-surface border border-outline-variant
                 font-headline font-bold rounded-xl hover:bg-surface-container-high
                 transition-all duration-200"
        >
          Learn More
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
  </section>
</template>
