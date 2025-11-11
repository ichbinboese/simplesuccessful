<template>
  <PasswordGate storageKey="gate_product">
    <SecureVideo
      :src="resolvedSrc"
      :type="type"
      :poster="resolvedPoster"
      :autoplay="autoplay"
      :muted="muted"
      :loop="loop"
      :preload="preload"
      :playsinline="playsinline"
      :controls="controls"
      :rounded="rounded"
      :aria-label="$t('videos.aria.label')"
      :title="$t('videos.product.title')"
    />
  </PasswordGate>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import SecureVideo from './SecureVideo.vue'
import PasswordGate from './PasswordGate.vue'

const props = defineProps({
  sources: { type: Object, default: () => ({}) },
  posters: { type: Object, default: () => ({}) },
  type: { type: String, default: 'video/mp4' },
  autoplay: { type: Boolean, default: false },
  muted: { type: Boolean, default: false },
  loop: { type: Boolean, default: false },
  preload: { type: String, default: 'metadata' },
  playsinline: { type: Boolean, default: true },
  controls: { type: Boolean, default: true },
  rounded: { type: Boolean, default: true }
})

const { locale } = useI18n()

function buildLocalPath(lc) {
  return `/build/videos/product/product_${lc}.mp4`
}

const resolvedSrc = computed(() => {
  const raw = (locale.value || 'en')
  const lc = ['de', 'en'].includes(raw) ? raw : 'en'
  if (props.sources[lc] || props.sources.en) {
    return props.sources[lc] || props.sources.en
  }
  return buildLocalPath(lc)
})

const resolvedPoster = computed(() => {
  const lc = locale.value || 'en'
  return props.posters[lc] || props.posters.en || ''
})
</script>
