<template>
  <section class="card space-y-6">
    <header class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
      <div>
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Freigabelinks</p>
        <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Video-Links verwalten</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">
          Übersicht aller generierten Links inklusive Status, Ablaufdatum und Schnellaktionen.
        </p>
      </div>
      <button type="button" class="btn btn-ghost text-sm" @click="$emit('close')">
        Schließen
      </button>
    </header>

    <div class="flex flex-wrap gap-2">
      <button
        v-for="filter in filters"
        :key="filter.value"
        class="btn text-xs"
        :class="filter.value === status ? 'btn-primary' : 'btn-secondary'"
        @click="setStatus(filter.value)"
      >
        {{ filter.label }}
      </button>
    </div>

    <div v-if="loading" class="space-y-3">
      <div v-for="n in 3" :key="`skeleton-${n}`" class="h-20 animate-pulse rounded-2xl bg-slate-100 dark:bg-slate-800/60"></div>
    </div>

    <div v-else-if="links.length === 0" class="rounded-2xl border border-dashed border-slate-200 px-6 py-10 text-center text-slate-500 dark:border-slate-700 dark:text-slate-400">
      <p class="text-base font-medium">Keine Links vorhanden</p>
      <p class="text-sm">Erstelle im Video-Tab neue Freigabelinks.</p>
    </div>

    <ul v-else class="space-y-4">
      <li
        v-for="link in links"
        :key="link.id"
        class="rounded-2xl border border-slate-200/80 bg-white/90 p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900/60"
      >
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
          <div>
            <div class="flex flex-wrap items-center gap-2">
              <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                {{ link.video?.title || 'Video #' + link.video?.id }}
              </h3>
              <span class="badge" :class="statusClass(link)">
                {{ statusLabel(link) }}
              </span>
            </div>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
              Token: <span class="font-mono">{{ link.token }}</span>
            </p>
            <div class="mt-3 flex flex-wrap gap-4 text-xs text-slate-500 dark:text-slate-400">
              <span>Erstellt: <strong class="text-slate-900 dark:text-slate-200">{{ formatDate(link.createdAt) }}</strong></span>
              <span>
                Ablauf:
                <strong class="text-slate-900 dark:text-slate-200">
                  {{ link.expiresAt ? formatDate(link.expiresAt) : 'Startet beim ersten View' }}
                </strong>
              </span>
              <span v-if="link.recipientEmail">Empfänger: <strong>{{ link.recipientEmail }}</strong></span>
            </div>
          </div>
          <div class="flex flex-col gap-2 md:items-end">
            <button type="button" class="btn btn-secondary text-xs" @click="copyLink(link)">
              {{ copyState[link.id] || 'Link kopieren' }}
            </button>
            <div class="flex gap-2 text-xs">
              <button type="button" class="btn btn-ghost" @click="shareEmail(link)">E-Mail</button>
              <button type="button" class="btn btn-ghost" @click="shareWhatsapp(link)">WhatsApp</button>
            </div>
          </div>
        </div>
      </li>
    </ul>

    <div v-if="meta.total > perPage" class="flex flex-wrap items-center justify-between gap-3 text-sm text-slate-500 dark:text-slate-400">
      <div>Seite {{ meta.page }} von {{ totalPages }}</div>
      <div class="flex gap-2">
        <button type="button" class="btn btn-secondary text-xs" :disabled="meta.page <= 1" @click="changePage(meta.page - 1)">
          Zurück
        </button>
        <button type="button" class="btn btn-secondary text-xs" :disabled="meta.page >= totalPages" @click="changePage(meta.page + 1)">
          Weiter
        </button>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import axios from 'axios';

const props = defineProps({
  perPage: { type: Number, default: 20 },
});

const emit = defineEmits(['close']);

const filters = [
  { label: 'Aktiv', value: 'active' },
  { label: 'Abgelaufen', value: 'expired' },
  { label: 'Angeklickt', value: 'clicked' },
  { label: 'Alle', value: 'all' },
];

const links = ref([]);
const loading = ref(false);
const status = ref('active');
const meta = reactive({ page: 1, perPage: props.perPage, total: 0 });
const copyState = reactive({});

const totalPages = computed(() => Math.max(1, Math.ceil(meta.total / meta.perPage)));

onMounted(loadLinks);

function setStatus(next) {
  if (status.value === next) return;
  status.value = next;
  meta.page = 1;
  loadLinks();
}

function changePage(next) {
  if (next === meta.page || next < 1 || next > totalPages.value) return;
  meta.page = next;
  loadLinks();
}

function loadLinks() {
  loading.value = true;
  axios
    .get('/api/admin/video/links', {
      params: {
        page: meta.page,
        perPage: meta.perPage,
        status: status.value,
      },
    })
    .then(({ data }) => {
      links.value = data.items || [];
      Object.assign(meta, data.meta || {});
    })
    .catch((error) => {
      console.error('Links could not be loaded', error);
      links.value = [];
      meta.total = 0;
    })
    .finally(() => {
      loading.value = false;
    });
}

function statusLabel(link) {
  if (!link.expiresAt) return link.clicked ? 'Aktiv' : 'Bereit';
  return link.status === 'expired' ? 'Abgelaufen' : 'Aktiv';
}

function statusClass(link) {
  if (link.status === 'expired') return 'badge-warning';
  if (link.clicked) return 'badge-success';
  return 'badge';
}

function formatDate(value) {
  if (!value) return '—';
  try {
    return new Intl.DateTimeFormat('de-DE', {
      dateStyle: 'medium',
      timeStyle: 'short',
    }).format(new Date(value));
  } catch (e) {
    return value;
  }
}

function copyLink(link) {
  const url = link.shareUrl;
  const fallback = () => {
    const input = document.createElement('input');
    input.value = url;
    document.body.appendChild(input);
    input.select();
    document.execCommand('copy');
    document.body.removeChild(input);
  };
  const done = () => {
    copyState[link.id] = 'Kopiert!';
    setTimeout(() => (copyState[link.id] = 'Link kopieren'), 2000);
  };
  if (navigator.clipboard?.writeText) {
    navigator.clipboard.writeText(url).then(done).catch(() => {
      fallback();
      done();
    });
  } else {
    fallback();
    done();
  }
}

function shareEmail(link) {
  const subject = encodeURIComponent('Privater Videolink');
  const body = encodeURIComponent(`Hier ist dein persönlicher Video-Link (24h gültig): ${link.shareUrl}`);
  window.open(`mailto:?subject=${subject}&body=${body}`, '_blank');
}

function shareWhatsapp(link) {
  const text = encodeURIComponent(`Hier ist dein persönlicher Video-Link (24h gültig): ${link.shareUrl}`);
  window.open(`https://wa.me/?text=${text}`, '_blank', 'noopener');
}
</script>
