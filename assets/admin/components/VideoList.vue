<template>
  <section class="card space-y-6">
    <header class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
      <div>
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Bibliothek</p>
        <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Deine Videos</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">
          Alle Assets inklusive Verfügbarkeit und Upload-Datum. Verwende die Suche, um schnell etwas zu finden.
        </p>
      </div>
      <div class="flex items-center gap-3">
        <button type="button" class="btn btn-secondary text-xs" @click="$emit('refresh')" :disabled="loading">
          Aktualisieren
        </button>
      </div>
    </header>

    <div class="relative">
      <input
        v-model="query"
        type="search"
        class="input pl-10"
        placeholder="Nach Titel oder Beschreibung suchen…"
        :disabled="loading"
      />
      <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
      </svg>
    </div>

    <div v-if="error" class="rounded-xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-600 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-200">
      {{ error }}
    </div>

    <div v-if="loading" class="space-y-3">
      <div v-for="n in 4" :key="`skeleton-${n}`" class="h-20 animate-pulse rounded-2xl bg-slate-100 dark:bg-slate-800/60"></div>
    </div>

    <div v-else-if="!filteredVideos.length" class="rounded-2xl border border-dashed border-slate-200 px-6 py-10 text-center text-slate-500 dark:border-slate-700 dark:text-slate-400">
      <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5c0 1.243-.502 2.365-1.316 3.179A4.477 4.477 0 0111.25 15c-1.242 0-2.364-.502-3.178-1.316A4.477 4.477 0 016.75 10.5m6.75 6.75H8.25A2.25 2.25 0 016 15V6.75m6.75 10.5h4.5A2.25 2.25 0 0019.5 15V6.75M6 6.75a2.25 2.25 0 012.25-2.25h7.5A2.25 2.25 0 0118 6.75m-12 0V9h12V6.75" />
      </svg>
      <p class="mt-3 text-base font-medium">Noch keine Ergebnisse</p>
      <p class="text-sm">Passe die Suche an oder lade ein neues Video hoch.</p>
    </div>

    <ul v-else class="space-y-4">
      <li
        v-for="video in filteredVideos"
        :key="video.id"
        class="rounded-2xl border border-slate-200/80 bg-white/80 p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900/80"
      >
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
          <div>
            <div class="flex flex-wrap items-center gap-2">
              <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ video.title || 'Unbenanntes Video' }}</h3>
              <span
                class="badge"
                :class="video.is_public ? 'badge-success' : 'badge-warning'"
              >
                {{ video.is_public ? 'Public' : 'Privat' }}
              </span>
            </div>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
              {{ video.description || 'Keine Beschreibung hinterlegt.' }}
            </p>
            <div class="mt-3 flex flex-wrap gap-4 text-xs text-slate-400 dark:text-slate-500">
              <span>
                Hochgeladen am
                <strong class="text-slate-600 dark:text-slate-200">{{ formatDate(video.uploaded_at || video.uploadedAt) }}</strong>
              </span>
              <span v-if="video.filename">
                Datei:
                <strong class="text-slate-600 dark:text-slate-200">{{ video.filename }}</strong>
              </span>
            </div>
          </div>
          <div class="flex shrink-0 flex-col gap-2 md:items-end">
            <button class="btn btn-primary w-full md:w-auto" @click="$emit('createLink', video)">
              Link erzeugen
            </button>
            <button class="btn btn-secondary w-full md:w-auto" @click="$emit('refresh')">
              Aktualisieren
            </button>
          </div>
        </div>
      </li>
    </ul>
  </section>
</template>

<script>
export default {
  props: {
    videos: { type: Array, default: () => [] },
    loading: { type: Boolean, default: false },
    error: { type: String, default: '' },
  },
  emits: ['refresh', 'createLink'],
  data() {
    return {
      query: '',
    };
  },
  computed: {
    filteredVideos() {
      if (!this.query) return this.videos;
      const q = this.query.toLowerCase();
      return this.videos.filter((video) => {
        return (
          (video.title && video.title.toLowerCase().includes(q)) ||
          (video.description && video.description.toLowerCase().includes(q))
        );
      });
    },
  },
  methods: {
    formatDate(value) {
      if (!value) return 'Unbekannt';
      try {
        return new Intl.DateTimeFormat('de-DE', {
          dateStyle: 'medium',
          timeStyle: 'short',
        }).format(new Date(value));
      } catch {
        return value;
      }
    },
  },
};
</script>
