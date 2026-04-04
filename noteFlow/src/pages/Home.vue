<script setup>
import TheNavbar    from '@/components/layout/Navbar.vue'
import HeroSection  from '@/components/HeroSection.vue'
import BentoFeatures from '@/components/sections/BentoFeatures.vue'
import CtaSection   from '@/components/sections/CtaSection.vue'
import TheFooter    from '@/components/layout/Footer.vue'
</script>

<template>
  <div class="bg-surface text-on-surface font-body min-h-screen selection:bg-primary/30">
    <TheNavbar />

    <main class="pt-16">
      <HeroSection />
      <BentoFeatures />
      <CtaSection />
    </main>

    <TheFooter />
  </div>
</template>