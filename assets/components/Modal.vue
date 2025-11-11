<template>
  <div v-if="modelValue" class="fixed inset-0 z-50 flex items-center justify-center">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/40" @click="onBackdrop" aria-hidden="true"></div>

    <!-- Dialog -->
    <div
      ref="dialog"
      class="relative bg-white dark:bg-stone-900 rounded-xl shadow-xl w-[92%] sm:w-auto max-w-full"
      :class="sizeClass"
      role="dialog"
      :aria-modal="true"
      :aria-labelledby="headingId"
      @keydown.esc.prevent="onEsc"
    >
      <!-- Header -->
      <div class="px-4 py-3 border-b border-slate-200 dark:border-stone-800 flex items-center justify-between">
        <h3 :id="headingId" class="text-base font-semibold text-slate-900 dark:text-white">{{ title }}</h3>
        <button v-if="closable" type="button" class="btn btn-ghost" @click="close" :aria-label="t('legal.close')">
          <!-- X icon -->
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M6 18L18 6"/></svg>
        </button>
      </div>

      <!-- Body -->
      <div class="px-4 py-4 overflow-y-auto max-h-[70vh] text-slate-700 dark:text-slate-300">
        <slot />
      </div>

      <!-- Footer -->
      <div class="px-4 py-3 border-t border-slate-200 dark:border-stone-800 flex items-center justify-end gap-2">
        <button v-if="closable" type="button" class="btn btn-primary" @click="close">{{ t('legal.close') }}</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, onBeforeUnmount, computed } from 'vue'
import { useI18n } from 'vue-i18n'

const props = defineProps({
  modelValue: { type: Boolean, default: false },
  title: { type: String, default: '' },
  size: { type: String, default: 'md' }, // sm | md | lg
  closable: { type: Boolean, default: true }
})

const emit = defineEmits(['update:modelValue', 'open', 'close'])
const { t } = useI18n()

const dialog = ref(null)
const previouslyFocused = ref(null)
const headingId = `modal-title-${Math.random().toString(36).slice(2)}`

const sizeClass = computed(() => {
  switch (props.size) {
    case 'sm': return 'sm:min-w-[28rem]'
    case 'lg': return 'sm:min-w-[56rem]'
    default: return 'sm:min-w-[40rem]'
  }
})

function trapFocus(e) {
  if (!props.modelValue || !dialog.value) return
  const focusable = dialog.value.querySelectorAll(
    'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])'
  )
  if (!focusable.length) return
  const first = focusable[0]
  const last = focusable[focusable.length - 1]
  if (e.key === 'Tab') {
    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault(); last.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault(); first.focus();
    }
  }
}

function openSideEffects() {
  try {
    previouslyFocused.value = document.activeElement
    document.body.style.overflow = 'hidden'
  } catch (e) {}
  setTimeout(() => {
    if (!dialog.value) return
    const focusTarget = dialog.value.querySelector('[autofocus]') || dialog.value.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])')
    if (focusTarget && focusTarget.focus) focusTarget.focus()
  }, 0)
}

function closeSideEffects() {
  try { document.body.style.overflow = '' } catch (e) {}
  const el = previouslyFocused.value
  if (el && el.focus) setTimeout(() => el.focus(), 0)
}

function onEsc() { if (props.closable) close() }
function onBackdrop() { if (props.closable) close() }

function close() {
  emit('update:modelValue', false)
  emit('close')
}

watch(() => props.modelValue, (val) => {
  if (val) { openSideEffects(); emit('open') }
  else { closeSideEffects() }
})

onMounted(() => {
  document.addEventListener('keydown', trapFocus)
  if (props.modelValue) openSideEffects()
})

onBeforeUnmount(() => {
  document.removeEventListener('keydown', trapFocus)
  if (props.modelValue) closeSideEffects()
})
</script>

<style scoped>
/* Basic backdrop is in template via bg-black/40. Container uses fixed inset for centering. */
</style>
