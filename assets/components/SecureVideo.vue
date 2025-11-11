<template>
  <div
    class="secure-video relative w-full"
    :class="[rounded ? 'rounded-xl overflow-hidden' : '']"
    role="group"
    :aria-label="ariaLabel || title || 'Video'"
    @keydown.space.prevent="togglePlay"
    @keydown.enter.prevent="togglePlay"
  >
    <video
      ref="videoEl"
      class="block w-full h-auto"
      :poster="poster || undefined"
      :autoplay="autoplay"
      :muted="muted"
      :loop="loop"
      :preload="preload"
      :playsinline="playsinline"
      :controls="controls"
      controlslist="nodownload noplaybackrate noremoteplayback"
      disablepictureinpicture
      disableremoteplayback
      @contextmenu.prevent
    >
      <source :src="src" :type="type" />
      {{ fallbackText }}
    </video>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, defineProps, computed } from 'vue'

const props = defineProps({
  src: { type: String, required: true },
  type: { type: String, default: 'video/mp4' },
  poster: { type: String, default: '' },
  title: { type: String, default: '' },
  ariaLabel: { type: String, default: '' },
  autoplay: { type: Boolean, default: false },
  muted: { type: Boolean, default: false },
  loop: { type: Boolean, default: false },
  preload: { type: String, default: 'metadata' },
  playsinline: { type: Boolean, default: true },
  controls: { type: Boolean, default: true },
  rounded: { type: Boolean, default: true }
})

const videoEl = ref(null)

const fallbackText = computed(() => props.title || 'Your browser does not support the video tag.')

function togglePlay () {
  const el = videoEl.value
  if (!el) return
  if (el.paused) el.play().catch(() => {})
  else el.pause()
}

onMounted(() => {
  // Autoplay requires muted on most browsers
  if (props.autoplay && videoEl.value && !props.muted) {
    try { videoEl.value.muted = true } catch (e) {}
  }
})
</script>

<style scoped>
/* ensures video takes the full width with rounded corners if enabled */
.secure-video video { display: block; width: 100%; height: auto; }
</style>
