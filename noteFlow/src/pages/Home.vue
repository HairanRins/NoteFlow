<script setup>
import TheNavbar    from '@/components/layout/Navbar.vue'
import HeroSection  from '@/components/HeroSection.vue'
import UserFlow     from '@/components/UserFlow.vue'
import BentoFeatures from '@/components/sections/BentoFeatures.vue'
import CtaSection   from '@/components/sections/CtaSection.vue'
import PricingSection from '@/components/PricingSection.vue'
import FinalCtaSection from '@/components/FinalCtaSection.vue'
import TheFooter    from '@/components/layout/Footer.vue'
</script>

<template>
  <div class="bg-surface text-on-surface font-body min-h-screen selection:bg-primary/30">
    <TheNavbar />

    <main class="pt-16">
      <HeroSection />
      <UserFlow />
      <BentoFeatures />
      <CtaSection />
      
      <!-- QUOTE -->
      <div class="primary-gradient py-20 px-6 text-center">
        <div class="max-w-4xl mx-auto">
          <p class="font-headline text-2xl md:text-3xl font-bold mb-8 leading-relaxed text-on-primary">
            "Pas une app de plus. <em class="italic">Un espace de pensée</em> qui s'adapte à ton
            cerveau, pas l'inverse."
          </p>
        </div>
      </div>
      
      <PricingSection />
      <FinalCtaSection />
    </main>

    <TheFooter />
  </div>
</template>