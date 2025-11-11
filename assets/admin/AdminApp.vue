<template>
  <div class="min-h-screen bg-slate-50/70 dark:bg-slate-950/70 text-slate-900 dark:text-slate-100">
    <!-- Top bar -->
    <header class="border-b border-slate-200/70 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 backdrop-blur supports-[backdrop-filter]:bg-white/60 dark:supports-[backdrop-filter]:bg-slate-900/70">
      <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-6 py-5">
        <div class="flex flex-1 flex-wrap items-center gap-3">
          <button
            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white/70 px-3 py-1 text-sm font-medium text-slate-600 shadow-sm transition hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900/60 dark:text-slate-300"
            @click="goHome"
          >
            Zurück zur Seite
          </button>

          <button
            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white/70 px-3 py-1 text-sm font-medium text-slate-600 shadow-sm transition hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900/60 dark:text-slate-300"
            @click="LogOut"
          >
            ausloggen
          </button>

          <div class="flex-1"></div>
          <button
            class="btn btn-ghost size-11 rounded-full border border-slate-200 bg-white/60 text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-900/60 dark:text-slate-200"
            :aria-pressed="isDark"
            @click="toggleTheme"
            aria-label="Darstellung umschalten"
          >
            <svg v-if="!isDark" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 4a1 1 0 0 1 1-1h0a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0zm0 16a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0v-1a1 1 0 0 1 1-1zm8-8a1 1 0 0 1 1-1h1a1 1 0 1 1 0 2h-1a1 1 0 0 1-1-1zM4 12a1 1 0 0 1-1 1H2a1 1 0 1 1 0-2h1a1 1 0 0 1 1 1zm11.95 5.536a1 1 0 0 1 1.415 0l.707.707a1 1 0 1 1-1.415 1.414l-.707-.707a1 1 0 0 1 0-1.414zM5.636 6.343a1 1 0 0 1 0-1.415l.707-.707A1 1 0 0 1 7.758 5.636l-.707.707a1 1 0 0 1-1.415 0zm12.021-1.415a1 1 0 0 1 0 1.415l-.707.707a1 1 0 0 1-1.415-1.415l.707-.707a1 1 0 0 1 1.415 0zM6.343 18.364a1 1 0 0 1 1.415 0l.707.707a1 1 0 1 1-1.415 1.415l-.707-.707a1 1 0 0 1 0-1.415zM12 8a4 4 0 1 1 0 8 4 4 0 0 1 0-8z" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
              <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 1 0 9.79 9.79Z" />
            </svg>
          </button>
        </div>
      </div>
      <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-6 pb-5">
        <div>
          <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Simple Successful Admin</p>
          <h1 class="text-3xl font-semibold tracking-tight text-slate-900 dark:text-white">
            Videobibliothek
          </h1>
          <p class="text-sm text-slate-500 dark:text-slate-400">
            Lade neue Assets hoch, teile private Links und verwalte dein Content-Archiv.
          </p>
        </div>
        <div class="flex flex-col items-end gap-1">
          <span class="text-xs text-slate-400">Zuletzt aktualisiert</span>
          <strong class="text-base">{{ lastUpdateLabel }}</strong>
        </div>
      </div>
    </header>

    <main class="mx-auto flex max-w-6xl flex-col gap-8 px-6 py-10">
      <!-- KPI cards -->
      <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <article class="card card-hover">
          <p class="text-sm text-slate-400">Videos gesamt</p>
          <h3 class="mt-2 text-3xl font-semibold">{{ videos.length }}</h3>
          <p class="text-xs text-slate-500">inkl. Entwürfe und veröffentlichte Inhalte</p>
        </article>
        <article class="card card-hover">
          <p class="text-sm text-slate-400">Aktive Links</p>
          <h3 class="mt-2 text-3xl font-semibold">{{ activeCount }}</h3>
          <p class="text-xs text-slate-500">Freigegebene Videos mit gültigem Link</p>
        </article>
        <article class="card card-hover">
          <p class="text-sm text-slate-400">Letzter Upload</p>
          <h3 class="mt-2 text-lg font-semibold leading-tight">
            {{ lastUploadLabel || '—' }}
          </h3>
          <p class="text-xs text-slate-500">Basierend auf Upload-Datum</p>
        </article>
        <article class="card card-hover">
          <p class="text-sm text-slate-400">Status</p>
          <div class="mt-2 inline-flex items-center gap-2 rounded-full border border-emerald-200/80 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
            <span class="size-2 rounded-full bg-emerald-500"></span>
            Betriebsbereit
          </div>
          <p class="mt-2 text-xs text-slate-500">Backend online und erreichbar</p>
        </article>
      </section>

      <section class="grid gap-6">
        <video-upload @uploaded="refresh" />
        <video-list
          :videos="videos"
          :loading="loading"
          :error="error"
          @refresh="refresh"
        />
      </section>
    </main>
  </div>
</template>

<script>
import axios from 'axios';
import VideoUpload from './components/VideoUpload.vue';
import VideoList from './components/VideoList.vue';

export default {
  components: { VideoUpload, VideoList },
  data() {
    return {
      videos: [],
      loading: false,
      error: '',
      lastRefresh: null,
      isDark: false,
    };
  },
  computed: {
    activeCount() {
      return this.videos.filter((v) => v.is_public).length;
    },
    lastUploadLabel() {
      if (!this.videos.length) return '';
      const latest = [...this.videos]
        .map((v) => v.uploaded_at || v.uploadedAt)
        .filter(Boolean)
        .sort((a, b) => new Date(b) - new Date(a))[0];
      return latest ? this.formatDate(latest) : '';
    },
    lastUpdateLabel() {
      if (!this.lastRefresh) return '—';
      return this.relativeFromNow(this.lastRefresh);
    },
  },
  mounted() {
    this.initializeTheme();
    this.refresh();
  },
  methods: {
    initializeTheme() {
      try {
        const stored = localStorage.getItem('admin-theme');
        if (stored === 'dark' || stored === 'light') {
          this.applyTheme(stored === 'dark');
          return;
        }
      } catch (e) {
        // ignore storage errors
      }
      const prefersDark = window.matchMedia?.('(prefers-color-scheme: dark)').matches;
      this.applyTheme(!!prefersDark);
    },
    applyTheme(dark) {
      this.isDark = !!dark;
      document.documentElement.classList.toggle('dark', this.isDark);
      try {
        localStorage.setItem('admin-theme', this.isDark ? 'dark' : 'light');
      } catch (e) {
        // ignore
      }
    },
    toggleTheme() {
      this.applyTheme(!this.isDark);
    },
    goHome() {
      window.location.href = '/';
    },
    LogOut() {
      window.location.href = '/logout';
    },
    async refresh() {
      this.loading = true;
      this.error = '';
      try {
        const { data } = await axios.get('/api/admin/video/list');
        this.videos = Array.isArray(data) ? data : [];
        this.lastRefresh = new Date();
      } catch (e) {
        this.error = e.response?.data?.message ?? 'Konnte Videos nicht laden.';
      } finally {
        this.loading = false;
      }
    },
    formatDate(value) {
      try {
        return new Intl.DateTimeFormat('de-DE', {
          dateStyle: 'short',
          timeStyle: 'short',
        }).format(new Date(value));
      } catch {
        return value;
      }
    },
    relativeFromNow(date) {
      const diffMs = new Date(date).getTime() - Date.now();
      const units = [
        { unit: 'year', ms: 1000 * 60 * 60 * 24 * 365 },
        { unit: 'month', ms: 1000 * 60 * 60 * 24 * 30 },
        { unit: 'day', ms: 1000 * 60 * 60 * 24 },
        { unit: 'hour', ms: 1000 * 60 * 60 },
        { unit: 'minute', ms: 1000 * 60 },
        { unit: 'second', ms: 1000 },
      ];
      const rtf = new Intl.RelativeTimeFormat('de', { numeric: 'auto' });
      for (const { unit, ms } of units) {
        if (Math.abs(diffMs) >= ms || unit === 'second') {
          return rtf.format(Math.round(diffMs / ms), unit);
        }
      }
      return 'gerade eben';
    },
  },
};
</script>
