<template>
  <div class="min-h-screen bg-gradient-to-b from-slate-50 via-white to-slate-100 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
    <header class="border-b border-white/60 bg-white/80 backdrop-blur dark:border-slate-800 dark:bg-slate-900/80">
      <div class="mx-auto flex max-w-5xl flex-wrap items-center justify-between gap-4 px-6 py-5">
        <div class="flex items-center gap-3">
        </div>
        <a
          class="btn btn-secondary text-sm"
          :href="brand.ctaHref"
        >
          {{ brand.ctaLabel }}
        </a>
      </div>
    </header>

    <main class="mx-auto flex max-w-5xl flex-col gap-8 px-6 py-10">
      <div class="grid gap-6 lg:grid-cols-[minmax(0,3fr)_minmax(0,2fr)]">
        <section class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-2xl shadow-slate-200/60 dark:border-slate-800 dark:bg-slate-900/80 dark:shadow-none">
          <div class="space-y-4">
            <div class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300">
              <span class="size-2 rounded-full bg-emerald-500"></span>
              Sicherer Stream
            </div>
            <h2 class="text-3xl font-semibold text-slate-900 dark:text-white">{{ video.title || 'Dein Video' }}</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ video.description || 'Personalisierter Inhalt, bereit zum Stream.' }}</p>
          </div>

          <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200/80 bg-black/90 shadow-lg dark:border-slate-800">
            <video
              controls
              playsinline
              preload="metadata"
              class="w-full bg-black"
            >
              <source :src="streamUrl" type="video/mp4" />
              Dein Browser unterstützt dieses Video nicht.
            </video>
          </div>
        </section>

        <aside class="space-y-6">
          <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-5 shadow-lg dark:border-slate-800 dark:bg-slate-900/80">
            <h3 class="text-base font-semibold text-slate-900 dark:text-white">Details</h3>
            <dl class="mt-4 space-y-3 text-sm">
              <div class="flex justify-between gap-3 text-slate-500 dark:text-slate-400">
                <dt>Bereitgestellt am</dt>
                <dd class="text-slate-900 dark:text-slate-100">{{ uploadedLabel }}</dd>
              </div>
              <div class="flex justify-between gap-3 text-slate-500 dark:text-slate-400">
                <dt>Link läuft ab</dt>
                <dd class="text-slate-900 dark:text-slate-100">{{ expiresDisplay }}</dd>
              </div>
              <div class="flex justify-between gap-3 text-slate-500 dark:text-slate-400">
                <dt>Datei</dt>
                <dd class="truncate text-slate-900 dark:text-slate-100">{{ video.filename || 'vertraulich' }}</dd>
              </div>
            </dl>
          </div>

          <div class="rounded-3xl border border-slate-200/80 bg-slate-50/80 p-5 shadow-inner dark:border-slate-800 dark:bg-slate-900/60">
            <h3 class="text-base font-semibold text-slate-900 dark:text-white">Teilen & Zugriff</h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
              Der Link ist persönlich und verfällt nach dem ersten Öffnen automatisch nach 24 Stunden.
            </p>
          </div>
        </aside>
      </div>
    </main>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
  video: { type: Object, required: true },
  streamUrl: { type: String, required: true },
  shareUrl: { type: String, required: true },
  token: { type: String, required: true },
  expiresAt: { type: String, default: null },
  brand: {
    type: Object,
    default: () => ({ name: 'DrSimple', ctaLabel: 'Zur Website', ctaHref: '/' }),
  },
});

const copied = ref(false);

const brandInitials = computed(() => {
  if (!props.brand?.name) return 'DS';
  return props.brand.name
    .split(' ')
    .map((p) => p[0])
    .join('')
    .slice(0, 2)
    .toUpperCase();
});

const uploadedLabel = computed(() => formatDate(props.video?.uploadedAt));

const expiresDisplay = computed(() => {
  if (!props.expiresAt) return '24 h nach erstem Zugriff';
  const absolute = formatDate(props.expiresAt);
  const relative = relativeFromNow(props.expiresAt);
  return `${absolute} (${relative})`;
});

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

function relativeFromNow(value) {
  try {
    const diffMs = new Date(value).getTime() - Date.now();
    const units = [
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
    return 'sofort';
  } catch (e) {
    return '';
  }
}

function copyShare() {
  const fallback = () => {
    const input = document.createElement('input');
    input.value = props.shareUrl;
    document.body.appendChild(input);
    input.select();
    document.execCommand('copy');
    document.body.removeChild(input);
  };
  const markCopied = () => {
    copied.value = true;
    setTimeout(() => (copied.value = false), 2000);
  };
  if (navigator.clipboard?.writeText) {
    navigator.clipboard.writeText(props.shareUrl).then(markCopied).catch(() => {
      fallback();
      markCopied();
    });
  } else {
    fallback();
    markCopied();
  }
}

function shareEmail() {
  const subject = encodeURIComponent('Privater Videolink');
  const body = encodeURIComponent(`Hier ist dein persönlicher Video-Link (24h gültig): ${props.shareUrl}`);
  window.open(`mailto:?subject=${subject}&body=${body}`, '_blank');
}

function shareWhatsapp() {
  const text = encodeURIComponent(`Hier ist dein persönlicher Video-Link (24h gültig): ${props.shareUrl}`);
  window.open(`https://wa.me/?text=${text}`, '_blank', 'noopener');
}
</script>
