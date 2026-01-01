<template>
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
    :title="$t('videos.explain.title')"
  />
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import SecureVideo from './SecureVideo.vue'

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

const builtInSources = {
  de: '/build/videos/marketing_ex/marketing_ex_de.mp4',
  en: '/build/videos/marketing_ex/marketing_ex_en.mp4',
  es: '/build/videos/marketing_ex/marketing_ex_es.mp4'
}

const normalizeLocale = () => (locale.value || 'en').split('-')[0].toLowerCase()

function localePriority () {
  const raw = normalizeLocale()
  const order = [raw, 'en']
  return [...new Set(order.filter(Boolean))]
}

const resolvedSrc = computed(() => {
  for (const key of localePriority()) {
    if (props.sources[key]) return props.sources[key]
    if (builtInSources[key]) return builtInSources[key]
  }
  return builtInSources.en || Object.values(builtInSources)[0]
})

const resolvedPoster = computed(() => {
  for (const key of localePriority()) {
    if (props.posters[key]) return props.posters[key]
  }
  return props.posters.en || ''
})
</script>
