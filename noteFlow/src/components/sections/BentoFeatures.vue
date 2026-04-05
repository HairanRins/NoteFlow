<script setup>
// BentoFeatures.vue
const tags = ['#architecture', '#productivity', '#design-system']

const searchResults = [
  { title: 'Foundations of Digital Arch…', date: 'Yesterday' },
  { title: 'NoteFlow Brand Identity',     date: '2 days ago' },
]

const graphLines = [
  { x1: '50%', y1: '50%', x2: '20%', y2: '20%' },
  { x1: '50%', y1: '50%', x2: '80%', y2: '30%' },
  { x1: '50%', y1: '50%', x2: '70%', y2: '80%' },
  { x1: '50%', y1: '50%', x2: '25%', y2: '75%' },
]
</script>

<template>
  <section id="features" class="py-24 px-8 max-w-7xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

      <!-- ① Markdown First — col-span-7 -->
      <div
        class="md:col-span-7 bg-surface-container-low rounded-2xl p-8
               relative overflow-hidden group hover:scale-[1.02] hover:shadow-xl transition-all duration-300 border border-transparent hover:border-primary/20"
      >
        <div class="relative z-10 flex flex-col h-full">
          <div class="mb-auto">
            <h3 class="text-2xl font-headline font-bold text-on-background mb-3">
              Markdown First
            </h3>
            <p class="text-on-surface-variant max-w-sm mb-8">
              Write without friction. Every shortcut you know, built into the core experience.
            </p>
          </div>

          <!-- Code preview -->
          <div
            class="bg-surface-container-lowest p-6 rounded-xl border border-outline-variant/10
                   font-mono text-sm leading-relaxed shadow-inner"
          >
            <div class="text-primary-fixed-dim"># Quarterly Review</div>
            <div class="text-on-surface-variant mt-2">- [x] Launch NoteFlow v2</div>
            <div class="text-on-surface-variant">- [ ] Integrate API</div>
            <div class="text-tertiary mt-4">```javascript</div>
            <div class="text-on-surface-variant pl-4">const flow = new NoteFlow();</div>
            <div class="text-on-surface-variant pl-4">flow.sync();</div>
            <div class="text-tertiary">```</div>
          </div>
        </div>
      </div>

      <!-- ② Bi-directional Links — col-span-5 -->
      <div
        class="md:col-span-5 bg-surface-container-low rounded-2xl p-8
               relative overflow-hidden hover:scale-[1.02] hover:shadow-xl transition-all duration-300 border border-transparent hover:border-tertiary/20"
      >
        <h3 class="text-2xl font-headline font-bold text-on-background mb-3">
          Bi-directional Links
        </h3>
        <p class="text-on-surface-variant mb-8">
          Connect ideas with
          <code class="text-primary bg-primary/10 px-1.5 py-0.5 rounded">[[Link]]</code>
          syntax.
        </p>

        <!-- Graph visual -->
        <div class="relative h-48 flex items-center justify-center">
          <div
            class="absolute w-3 h-3 bg-tertiary rounded-full
                   shadow-[0_0_15px_rgba(255,183,132,0.5)]"
          />
          <svg class="absolute inset-0 w-full h-full">
            <line
              v-for="(line, i) in graphLines"
              :key="i"
              :x1="line.x1" :y1="line.y1"
              :x2="line.x2" :y2="line.y2"
              stroke="#454652" stroke-width="1"
              stroke-dasharray="4 4" opacity="0.3"
            />
          </svg>
          <div
            class="absolute top-[20%] left-[20%] px-3 py-1 bg-surface-container-high
                   rounded-full text-[10px] text-outline"
          >
            Product Strategy
          </div>
          <div
            class="absolute top-[30%] right-[20%] px-3 py-1 bg-surface-container-high
                   rounded-full text-[10px] text-outline"
          >
            Market Research
          </div>
          <div
            class="absolute bottom-[20%] right-[30%] px-3 py-1 bg-surface-container-high
                   rounded-full text-[10px] text-outline"
          >
            User Interview
          </div>
        </div>
      </div>

      <!-- ③ Instant Search — col-span-6 -->
      <div class="md:col-span-6 bg-surface-container-low rounded-2xl p-8 hover:scale-[1.02] hover:shadow-xl transition-all duration-300 border border-transparent hover:border-secondary/20">
        <h3 class="text-2xl font-headline font-bold text-on-background mb-3">
          Instant Search
        </h3>

        <div class="mt-6 space-y-4">
          <div
            class="flex items-center gap-3 bg-surface-container-highest
                   px-4 py-3 rounded-xl"
          >
            <span class="material-symbols-outlined text-primary">search</span>
            <span class="text-on-surface">Digital Architecture</span>
          </div>

          <div class="space-y-2 px-2">
            <div
              v-for="result in searchResults"
              :key="result.title"
              class="flex justify-between items-center py-2
                     border-b border-outline-variant/10"
            >
              <span class="text-sm font-medium">{{ result.title }}</span>
              <span class="text-[10px] text-outline uppercase tracking-wider">
                {{ result.date }}
              </span>
            </div>
          </div>

          <div class="flex gap-2 flex-wrap pt-2">
            <span
              v-for="tag in tags"
              :key="tag"
              class="px-3 py-1 bg-surface-container-highest rounded-full text-xs
                     text-outline hover:bg-primary-container hover:text-on-primary-container
                     transition-colors cursor-pointer"
            >
              {{ tag }}
            </span>
          </div>
        </div>
      </div>

      <!-- ④ Offline & Sync — col-span-6 -->
      <div
        class="md:col-span-6 bg-surface-container-low rounded-2xl p-8
               flex flex-col justify-between hover:scale-[1.02] hover:shadow-xl transition-all duration-300 border border-transparent hover:border-primary/20"
      >
        <div>
          <h3 class="text-2xl font-headline font-bold text-on-background mb-3">
            Offline &amp; Sync
          </h3>
          <p class="text-on-surface-variant">
            Built for the Digital Architect. Your notes live on your machine,
            syncing silently to all your devices without a hitch.
          </p>
        </div>

        <div class="mt-8 flex items-center gap-4">
          <div
            class="flex items-center gap-2 px-4 py-2 bg-primary/10
                   text-primary-fixed-dim rounded-lg border border-primary/20"
          >
            <span class="material-symbols-outlined text-sm">cloud_done</span>
            <span class="text-xs font-bold uppercase tracking-widest">Always Synced</span>
          </div>
          <div
            class="flex items-center gap-2 px-4 py-2 bg-tertiary/10
                   text-tertiary-fixed-dim rounded-lg border border-tertiary/20"
          >
            <span class="material-symbols-outlined text-sm">wifi_off</span>
            <span class="text-xs font-bold uppercase tracking-widest">Offline First</span>
          </div>
        </div>
      </div>

    </div>
  </section>
</template>