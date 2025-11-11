<template>
  <header class="navbar">
    <div class="navbar-inner">
      <a :href="homeHref" class="flex items-center gap-3 text-stone-900 dark:text-white">
        <!-- Dein Original-SVG -->
        <svg class="h-10 w-auto" viewBox="0 0 248.351 47.166" role="img" aria-label="DrSimple Logo" fill="currentColor">
          <!-- Füge hier **den kompletten** SVG-Inhalt ein (alle <path> aus deinem Snippet) -->
          <path d="M41.133,3.692h9.568c10.348,0,17.004,5.148,17.004,16.797s-6.656,17.108-16.588,17.108h-9.984V3.692z M50.233,31.409
            c5.772,0,9.621-2.808,9.621-10.92S56.005,9.881,50.233,9.881h-1.456v21.528H50.233z"/>
          <!-- … alle weiteren <path> aus deiner Vorlage … -->
        </svg>
      </a>

      <!-- Menü (auf Auth-Seiten meist ausgeblendet) -->
      <nav v-if="showMenu" class="hidden md:flex items-center gap-6">
        <a v-for="l in links" :key="l.href" :href="l.href" class="nav-link">{{ l.label }}</a>
      </nav>

      <div class="flex items-center gap-2 sm:gap-3">
        <!-- Darkmode Toggle -->
        <button class="btn btn-ghost h-10 w-10 p-0" :aria-pressed="isDark" @click="toggleTheme"
                aria-label="Dark Mode umschalten" title="Dark Mode umschalten">
          <svg v-if="!isDark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5">
            <path fill="currentColor" d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 1 0 9.79 9.79Z"/>
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5">
            <path fill="currentColor" d="M12 4a1 1 0 0 1 1-1h0a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0V4Zm0 15a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0v-1a1 1 0 0 1 1-1Zm8-7a1 1 0 0 1 1 1h1a1 1 0 1 1 0 2h-1a1 1 0 1 1-2 0 1 1 0 0 1 1-1Zm-17 1a1 1 0 0 1-1 1H1a1 1 0 1 1 0-2h1a1 1 0 0 1 1 1Zm12.95 5.536a1 1 0 0 1 1.415 0l.707.707a1 1 0 0 1-1.415 1.414l-.707-.707a1 1 0 0 1 0-1.414ZM5.636 6.343a1 1 0 0 1 0-1.415l.707-.707A1 1 0 0 1 7.758 5.636l-.707.707a1 1 0 0 1-1.415 0Zm12.021-1.415a1 1 0 0 1 0 1.415l-.707.707a1 1 0 0 1-1.415-1.415l.707-.707a1 1 0 0 1 1.415 0ZM6.343 18.364a1 1 0 0 1 1.415 0l.707.707a1 1 0 0 1-1.415 1.415l-.707-.707a1 1 0 0 1 0-1.415Z"/>
          </svg>
        </button>

        <!-- Optionale CTAs -->
        <a v-if="ctas?.secondary && showCtas" :href="ctas.secondary.href" class="btn btn-secondary hidden sm:inline-flex">
          {{ ctas.secondary.label }}
        </a>
        <a v-if="ctas?.primary && showCtas" :href="ctas.primary.href" class="btn btn-primary hidden md:inline-flex">
          {{ ctas.primary.label }}
        </a>
        <a :href="adminHref" class="btn btn-primary w-full">Login</a>

        <!-- Mobile Toggle -->
        <button @click="mobileOpen = !mobileOpen" class="md:hidden btn btn-ghost" aria-label="Menü öffnen">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
      </div>
    </div>

    <!-- Mobile Drawer -->
    <div v-show="mobileOpen && showMenu" class="md:hidden border-t divider bg-white/80 dark:bg-stone-900/80 backdrop-blur">
      <div class="container-prose py-3 grid gap-3">
        <a v-for="l in links" :key="l.href" @click="mobileOpen=false" :href="l.href" class="nav-link">{{ l.label }}</a>
        <div class="flex gap-2 pt-1" v-if="showCtas && (ctas?.primary || ctas?.secondary)">
          <a v-if="ctas?.secondary" :href="ctas.secondary.href" class="btn btn-secondary w-full">{{ ctas.secondary.label }}</a>
          <a v-if="ctas?.primary" :href="ctas.primary.href" class="btn btn-primary w-full">{{ ctas.primary.label }}</a>
        </div>
        <button class="btn btn-ghost mt-2" :aria-pressed="isDark" @click="toggleTheme" aria-label="Dark Mode umschalten">
          <span class="mr-2">Theme</span>
          <svg v-if="!isDark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5"><path fill="currentColor" d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 1 0 9.79 9.79Z"/></svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5"><path fill="currentColor" d="M12 4a1 1 0 0 1 1-1h0a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0V4Zm0 15a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0v-1a1 1 0 0 1 1-1Zm8-7a1 1 0 0 1 1 1h1a1 1 0 1 1 0 2h-1a1 1 0 1 1-2 0 1 1 0 0 1 1-1Zm-17 1a1 1 0 0 1-1 1H1a1 1 0 1 1 0-2h1a1 1 0 0 1 1 1Zm12.95 5.536a1 1 0 0 1 1.415 0l.707.707a1 1 0 0 1-1.415 1.414l-.707-.707a1 1 0 0 1 0-1.414ZM5.636 6.343a1 1 0 0 1 0-1.415l.707-.707A1 1 0 0 1 7.758 5.636l-.707.707a1 1 0 0 1-1.415 0Zm12.021-1.415a1 1 0 0 1 0 1.415l-.707.707a1 1 0 0 1-1.415-1.415l.707-.707a1 1 0 0 1 1.415 0ZM6.343 18.364a1 1 0 0 1 1.415 0l.707.707a1 1 0 0 1-1.415 1.415l-.707-.707a1 1 0 0 1 0-1.415Z"/></svg>
        </button>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
  homeHref: { type: String, default: '/' },
  adminHref: { type: String, default: '/admin' },
  links: { type: Array, default: () => [] },
  showMenu: { type: Boolean, default: true },
  showCtas: { type: Boolean, default: true },
  ctas: { type: Object, default: () => ({}) }
});

const mobileOpen = ref(false);
const isDark = ref(false);

function applyTheme(dark) {
  isDark.value = !!dark;
  document.documentElement.classList.toggle('dark', isDark.value);
  try { localStorage.setItem('theme', isDark.value ? 'dark' : 'light'); } catch {}
}
function toggleTheme() { applyTheme(!isDark.value); }

onMounted(() => {
  let stored = null;
  try { stored = localStorage.getItem('theme'); } catch {}
  if (stored === 'dark' || stored === 'light') {
    applyTheme(stored === 'dark');
  } else {
    const prefersDark = window.matchMedia?.('(prefers-color-scheme: dark)').matches;
    applyTheme(!!prefersDark);
  }
  try {
    const mq = window.matchMedia('(prefers-color-scheme: dark)');
    const handler = (e) => {
      const saved = localStorage.getItem('theme');
      if (!saved) applyTheme(e.matches);
    };
    mq.addEventListener?.('change', handler);
  } catch {}
  window.addEventListener('hashchange', () => (mobileOpen.value = false));
});
</script>
