<template>
  <section class="section">
    <div class="container-prose max-w-4xl">
      <router-link to="/" class="nav-link">← {{ $t('videos.back') }}</router-link>
      <h1 class="h2 mt-2">{{ $t('videos.marketing.title') }}</h1>
      <div class="mt-4">
        <MarketingVideo />
      </div>
    </div>
  </section>
</template>

<script setup>
import MarketingVideo from '@/components/MarketingVideo.vue'
</script>
