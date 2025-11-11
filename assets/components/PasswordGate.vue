<template>
  <div class="password-gate">
    <div v-if="!unlocked" class="gate card p-4 flex flex-col gap-3">
      <h4 class="text-base font-semibold text-slate-900 dark:text-white">
        {{ $t('videos.lock.title') }}
      </h4>
      <label class="text-sm text-slate-600 dark:text-slate-300">
        {{ $t('videos.lock.prompt') }}
      </label>
      <input
        class="input"
        :type="show ? 'text' : 'password'"
        :placeholder="$t('videos.lock.placeholder')"
        v-model="input"
        @keyup.enter="tryUnlock"
        aria-autocomplete="list"
      />
      <div class="flex items-center justify-between gap-3">
        <label class="inline-flex items-center gap-2 text-sm">
          <input type="checkbox" v-model="remember" />
          <span>{{ $t('videos.lock.remember') }}</span>
        </label>
        <button type="button" class="btn btn-primary" @click="tryUnlock">
          {{ $t('videos.lock.submit') }}
        </button>
      </div>
      <p v-if="error" class="text-sm text-red-600" role="alert">{{ $t('videos.lock.error') }}</p>
    </div>

    <div v-else>
      <slot />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  password: { type: String, default: 'Simple!#AK' },
  storageKey: { type: String, default: '' },
  rememberByDefault: { type: Boolean, default: false },
})

const emit = defineEmits(['unlocked'])

const input = ref('')
const unlocked = ref(false)
const error = ref(false)
const remember = ref(props.rememberByDefault)
const show = ref(false)

function persistUnlocked () {
  if (!props.storageKey) return
  try {
    const storage = remember.value ? localStorage : sessionStorage
    storage.setItem(props.storageKey, '1')
  } catch (e) {}
}

function restoreUnlocked () {
  if (!props.storageKey) return false
  try {
    return (
      localStorage.getItem(props.storageKey) === '1' ||
      sessionStorage.getItem(props.storageKey) === '1'
    )
  } catch (e) {
    return false
  }
}

function tryUnlock () {
  error.value = false
  if (input.value === props.password) {
    unlocked.value = true
    persistUnlocked()
    emit('unlocked')
  } else {
    error.value = true
  }
}

onMounted(() => {
  if (restoreUnlocked()) {
    unlocked.value = true
  }
})
</script>

<style scoped>
.password-gate .input { width: 100%; }
</style>
