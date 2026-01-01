<template>
  <div class="porsche-page">
    <header class="porsche-header transparent-header">
      <div class="porsche-bar container-prose">
        <div class="porsche-brand">
          <span class="brand-mark"></span>
        </div>
        <div class="porsche-actions">
          <button class="lang-toggle" @click="cycleLocale" :title="$t('nav.language')">
            {{ currentLocale.toUpperCase() }}
          </button>
          <a href="#kontakt" class="nav-link discovery-link">{{ $t('cta.discovery_call') }}</a>
        </div>
      </div>
    </header>

    <section class="porsche-hero">
      <div class="hero-media">
        <video autoplay muted loop playsinline :poster="heroPoster">
          <source :src="heroVideo" type="video/mp4" />
        </video>
      </div>
      <div class="hero-gradient"></div>
      <div class="hero-overlay container-prose">
        <div class="hero-meta">
          <span class="hero-divider"></span>
        </div>
        <h1 class="hero-headline">
          <span
            v-for="(letter, idx) in animatedLetters"
            :key="idx"
            class="headline-letter"
            :class="[{ visible: letter.visible }, letter.part === 'bold' ? 'letter-strong' : 'letter-italic']"
          >
            {{ letter.char === ' ' ? '\u00a0' : letter.char }}
          </span>
        </h1>
      </div>
    </section>

    <section class="porsche-stage">
      <div class="container-prose space-y-6">
        <div class="section-head">
          <div class="accent-bar"></div>
          <p class="section-kicker">Entdecken</p>
          <h2 class="h2 text-stone-900 dark:text-white">Drive. Design. Lifestyle.</h2>
        </div>
        <div class="stage-grid">
          <article v-for="tile in stageTiles" :key="tile.key" class="stage-card">
            <img :src="tile.image" :alt="tile.alt" loading="lazy" class="stage-img" />
            <div class="stage-overlay">
              <p class="stage-kicker">{{ tile.kicker }}</p>
              <h3 class="stage-title">{{ tile.title }}</h3>
              <p class="stage-copy">{{ tile.copy }}</p>
            </div>
          </article>
        </div>
      </div>
    </section>

    <section id="programme" class="porsche-section">
      <div class="container-prose space-y-10">
        <div class="section-head">
          <div class="accent-bar"></div>
          <p class="section-kicker">{{ $t('programs.title') }}</p>
          <h2 class="h2 text-stone-900 dark:text-white">{{ $t('programs.subtitle') }}</h2>
          <p class="section-sub">{{ $t('hero.subtitle') }}</p>
        </div>
        <div class="tile-grid">
          <article v-for="tile in videoTiles" :key="tile.key" class="porsche-tile">
            <div class="tile-media">
              <img :src="tile.image" :alt="tile.alt" loading="lazy" />
            </div>
            <div class="tile-body">
              <p class="tile-label">{{ tile.kicker }}</p>
              <h3 class="tile-title">{{ tile.title }}</h3>
              <p class="tile-copy">{{ tile.copy }}</p>
              <router-link :to="tile.route" class="tile-link">
                {{ tile.cta }}
                <span aria-hidden="true">→</span>
              </router-link>
            </div>
          </article>
        </div>
      </div>
    </section>

    <section id="kontakt" class="porsche-section contact-section">
      <div class="container-prose contact-grid">
        <div>
          <p class="section-kicker">{{ $t('cta.discovery_call') }}</p>
          <h2 class="h2 text-stone-900 dark:text-white">{{ $t('contact.title') }}</h2>
          <p class="section-sub">{{ $t('contact.subtitle') }}</p>
        </div>
        <div class="card contact-card">
          <div v-if="submitSuccess" class="alert alert-success" role="status" aria-live="polite">
            {{ $t('contact.form.success') }}
          </div>
          <div v-if="submitError" class="alert alert-error" role="alert" aria-live="assertive">
            {{ $t('contact.form.error_generic') }}
          </div>
          <form @submit.prevent="submitContact" class="grid grid-cols-1 md:grid-cols-2 gap-4" novalidate>
            <input class="hidden" type="text" v-model="honeypot" autocomplete="off" tabindex="-1" aria-hidden="true" />

            <div>
              <label class="input-label">{{ $t('contact.form.name_label') }}</label>
              <input class="input mt-1" :placeholder="$t('contact.form.name_placeholder')" v-model.trim="form.name" :disabled="submitting" required />
              <p v-if="errors.name" class="form-error">{{ errors.name }}</p>
            </div>
            <div>
              <label class="input-label">{{ $t('contact.form.email_label') }}</label>
              <input class="input mt-1" type="email" :placeholder="$t('contact.form.email_placeholder')" v-model.trim="form.email" :disabled="submitting" required />
              <p v-if="errors.email" class="form-error">{{ errors.email }}</p>
            </div>
            <div class="md:col-span-2">
              <label class="input-label">{{ $t('contact.form.phone_label') }}</label>
              <input class="input mt-1" type="tel" :placeholder="$t('contact.form.phone_placeholder')" v-model.trim="form.phone" :disabled="submitting" required />
              <p v-if="errors.phone" class="form-error">{{ errors.phone }}</p>
            </div>
            <div class="md:col-span-2">
              <label class="input-label">{{ $t('contact.form.goal_label') }}</label>
              <textarea class="textarea mt-1" :placeholder="$t('contact.form.goal_placeholder')" v-model.trim="form.goal" :disabled="submitting" rows="5" required></textarea>
              <p v-if="errors.goal" class="form-error">{{ errors.goal }}</p>
            </div>
            <div class="md:col-span-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
              <span class="privacy-note">{{ $t('contact.form.privacy_note') }}</span>
              <button type="submit" class="btn btn-primary" :disabled="submitting">
                <span v-if="!submitting">{{ $t('contact.form.submit') }}</span>
                <span v-else>{{ $t('contact.form.submitting') }}</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </section>

    <footer class="site-footer">
      <div class="site-footer-inner container-prose">
        <div class="footer-row">
          <p>{{ $t('footer.copyright', { year: new Date().getFullYear() }) }}</p>
          <div class="footer-links">
            <a href="#imprint" @click.prevent="openImprint" class="nav-link">{{ $t('footer.imprint') }}</a>
            <a href="#privacy" @click.prevent="openPrivacy" class="nav-link">{{ $t('footer.privacy') }}</a>
            <a href="#kontakt" class="nav-link">{{ $t('footer.contact') }}</a>
          </div>
        </div>
      </div>
    </footer>

    <Modal v-model="showImprint" :title="$t('legal.imprint.title')" size="lg" @close="onModalClose('imprint')">
      <div class="prose dark:prose-invert max-w-none" v-html="$t('legal.imprint.content')"></div>
    </Modal>
    <Modal v-model="showPrivacy" :title="$t('legal.privacy.title')" size="lg" @close="onModalClose('privacy')">
      <div class="prose dark:prose-invert max-w-none" v-html="$t('legal.privacy.content')"></div>
    </Modal>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed, onUnmounted } from 'vue'
import { useI18n } from 'vue-i18n'
import axios from 'axios'
import Modal from '@/components/Modal.vue'

import heroPoster from '@/images/bilder/pexels-daniel-andraski-197681005-13716061.jpg'
import shotMarketing from '@/images/bilder/pexels-drew-williams-1285451-3439481.jpg'
import shotExplain from '@/images/bilder/pexels-luna-lovegood-4087182.jpg'
import shotProduct from '@/images/bilder/pexels-anna-nekrashevich-6203311.jpg'
import shotWorkshop from '@/images/bilder/pexels-thirdman-8470839.jpg'
import shotLifestyle from '@/images/bilder/pexels-arina-krasnikova-5712085.jpg'
import shotNight from '@/images/bilder/pexels-vladbagacian-1228396.edited.jpg'

const heroVideo = '/build/videos/Video.Header.Simple.Successful.mp4'

const isDark = ref(false)
const animatedLetters = ref([])
const letterTimers = []
let letterCycle = null

const showImprint = ref(false)
const showPrivacy = ref(false)

function openImprint() {
  showImprint.value = true
  try { history.replaceState(null, '', '#imprint') } catch (e) {}
}
function openPrivacy() {
  showPrivacy.value = true
  try { history.replaceState(null, '', '#privacy') } catch (e) {}
}
function onModalClose(which) {
  try {
    const hash = location.hash.replace('#', '')
    if (hash === which) history.replaceState(null, '', ' ')
  } catch (e) {}
}

const { t, locale } = useI18n()
const supportedLocales = ['de', 'en', 'es']
const currentLocale = ref(locale.value)

watch(currentLocale, (val) => {
  if (!val) return
  locale.value = val
  try { localStorage.setItem('locale', val) } catch (e) {}
  try { document.documentElement.setAttribute('lang', val) } catch (e) {}
})

const heroPhrase = 'SIMPLE. SUCCESSFUL.'
const boldLength = 7

function startLetterReveal () {
  letterTimers.forEach(clearTimeout)
  letterTimers.length = 0
  const baseLetters = heroPhrase.split('').map((char, idx) => ({
    char,
    part: idx < boldLength ? 'bold' : 'italic',
    visible: false
  }))
  animatedLetters.value = baseLetters
  const order = [...baseLetters.keys()].sort(() => Math.random() - 0.5)
  order.forEach((idx, seq) => {
    const delay = 100 + Math.random() * 900 + seq * 35
    const timer = setTimeout(() => {
      animatedLetters.value[idx].visible = true
    }, delay)
    letterTimers.push(timer)
  })
}

const videoTiles = computed(() => [
  {
    key: 'marketing',
    title: t('videos.marketing.title'),
    kicker: 'Direct Sales',
    copy: t('programs.starter_text'),
    cta: t('programs.watch_marketing'),
    route: { name: 'video_marketing' },
    image: shotMarketing,
    alt: t('programs.images.starter_alt')
  },
  {
    key: 'explain',
    title: t('videos.explain.title'),
    kicker: 'Network',
    copy: t('programs.momentum_text'),
    cta: t('programs.watch_explain'),
    route: { name: 'video_marketing_explain' },
    image: shotExplain,
    alt: t('programs.images.momentum_alt')
  },
  {
    key: 'product',
    title: t('videos.product.title'),
    kicker: 'Products',
    copy: t('programs.leadership_text'),
    cta: t('programs.watch_product'),
    route: { name: 'video_product' },
    image: shotProduct,
    alt: t('programs.images.leadership_alt')
  }
])

const stageTiles = computed(() => [
  {
    key: 'drive',
    kicker: 'Performance',
    title: 'Night Drive',
    copy: 'Kraft, Präzision und maximale Kontrolle – bereit für jede Straße.',
    image: shotNight,
    alt: 'Sportwagen bei Nacht'
  },
  {
    key: 'workshop',
    kicker: 'Experience',
    title: 'Werkstattkultur',
    copy: 'Feinschliff bis ins letzte Detail. Handwerk, das überzeugt.',
    image: shotWorkshop,
    alt: 'Mechaniker in Garage'
  },
  {
    key: 'lifestyle',
    kicker: 'Lifestyle',
    title: 'Signature Living',
    copy: 'Design, das Performance spricht – auch abseits der Strecke.',
    image: shotLifestyle,
    alt: 'Mode Detail'
  }
])

function applyTheme(dark) {
  isDark.value = !!dark
  const root = document.documentElement
  root.classList.toggle('dark', isDark.value)
  try { localStorage.setItem('theme', isDark.value ? 'dark' : 'light') } catch (e) {}
}

function toggleTheme() {
  applyTheme(!isDark.value)
}

function cycleLocale() {
  const idx = supportedLocales.indexOf(currentLocale.value) ?? 0
  const next = supportedLocales[(idx + 1) % supportedLocales.length]
  currentLocale.value = next
}

onMounted(() => {
  let stored
  try { stored = localStorage.getItem('theme') } catch (e) {}
  if (stored === 'dark' || stored === 'light') {
    applyTheme(stored === 'dark')
  } else {
    applyTheme(false)
  }

  try {
    const mq = window.matchMedia('(prefers-color-scheme: dark)')
    const handler = (e) => {
      const storedNow = localStorage.getItem('theme')
      if (!storedNow) applyTheme(e.matches)
    }
    if (mq.addEventListener) mq.addEventListener('change', handler)
    else mq.addListener && mq.addListener(handler)
  } catch (e) {}

  window.addEventListener('hashchange', () => {
    const h = (location.hash || '').replace('#', '')
    if (h === 'imprint') showImprint.value = true
    if (h === 'privacy') showPrivacy.value = true
  })
  try {
    const h = (location.hash || '').replace('#', '')
    if (h === 'imprint') showImprint.value = true
    if (h === 'privacy') showPrivacy.value = true
  } catch (e) {}

  startLetterReveal()
  letterCycle = setInterval(startLetterReveal, 5200)
})

onUnmounted(() => {
  letterTimers.forEach(clearTimeout)
  if (letterCycle) clearInterval(letterCycle)
})

const submitting = ref(false)
const submitSuccess = ref(false)
const submitError = ref(false)
const errors = ref({})
const honeypot = ref('')
const form = ref({ name: '', email: '', phone: '', goal: '' })

async function submitContact () {
  submitSuccess.value = false
  submitError.value = false
  errors.value = {}

  if (!form.value.name) errors.value.name = t('contact.form.error_name_required')
  if (!form.value.email) errors.value.email = t('contact.form.error_email_required')
  else if (!/^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/.test(form.value.email)) errors.value.email = t('contact.form.error_email_invalid')
  if (!form.value.phone) errors.value.phone = t('contact.form.error_phone_required')
  else if (!/^\\+?[0-9\\s().-]{7,}$/.test(form.value.phone)) errors.value.phone = t('contact.form.error_phone_invalid')
  if (!form.value.goal) errors.value.goal = t('contact.form.error_goal_required')
  if (Object.keys(errors.value).length) return

  submitting.value = true
  try {
    await axios.post('/api/contact', {
      name: form.value.name,
      email: form.value.email,
      phone: form.value.phone,
      goal: form.value.goal,
      locale: locale.value,
      website: honeypot.value
    })
    submitSuccess.value = true
    form.value = { name: '', email: '', phone: '', goal: '' }
    honeypot.value = ''
  } catch (e) {
    if (e.response && e.response.status === 422 && e.response.data && e.response.data.errors) {
      errors.value = e.response.data.errors
    } else {
      submitError.value = true
    }
  } finally {
    submitting.value = false
  }
}
</script>

<style scoped>
.porsche-page {
  background: rgb(var(--bg));
  color: rgb(var(--text));
}

.porsche-header {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  z-index: 50;
  background: transparent;
  border: none;
}

.transparent-header {
  background: linear-gradient(180deg, rgba(0, 0, 0, 0.18) 0%, rgba(0, 0, 0, 0) 100%);
}

.porsche-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1.25rem;
  padding: 0.9rem 0;
}

.porsche-brand {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  font-weight: 700;
  font-size: 0.9rem;
}

.brand-mark {
  width: 18px;
  height: 18px;
  background: #ffffff;
  border-radius: 3px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.16);
}

.transparent-header .brand-mark {
  color: #ffffff;
}

.porsche-nav {
  justify-self: center;
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.nav-item {
  font-weight: 600;
  color: #0f172a;
  text-decoration: none;
  position: relative;
  padding: 0.35rem 0;
}

.dark .nav-item {
  color: #e5e7eb;
}

.nav-item::after {
  content: '';
  position: absolute;
  inset: auto 0 -6px 0;
  height: 2px;
  background: rgb(var(--accent));
  transform: scaleX(0);
  transform-origin: center;
  transition: transform 0.3s ease;
}

.nav-item:hover::after,
.nav-item:focus-visible::after {
  transform: scaleX(1);
}

.nav-admin {
  padding-inline: 0.85rem;
  border: 1px solid rgba(0, 0, 0, 0.08);
  border-radius: 999px;
  background: #fff;
  box-shadow: 0 8px 24px -16px rgba(0, 0, 0, 0.35);
}

.dark .nav-admin {
  background: #0b1220;
  border-color: rgba(255, 255, 255, 0.08);
  color: #e5e7eb;
}

.porsche-actions {
  display: inline-flex;
  align-items: center;
  justify-content: flex-end;
  gap: 0.75rem;
}

.lang-toggle {
  min-width: 64px;
  height: 38px;
  border-radius: 999px;
  border: 1px solid rgba(255, 255, 255, 0.5);
  background: rgba(255, 255, 255, 0.12);
  color: #fff;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.discovery-link {
  padding: 0.55rem 1rem;
  border-radius: 999px;
  border: 1px solid rgba(255, 255, 255, 0.5);
  background: rgba(0, 0, 0, 0.25);
  color: #fff;
  font-weight: 700;
  letter-spacing: 0.02em;
}

.dark .action-btn,
.dark .lang-switch {
  background: #0b1220;
  color: #e5e7eb;
  border-color: rgba(255, 255, 255, 0.08);
}

.mobile-drawer {
  border-top: 1px solid rgba(0, 0, 0, 0.06);
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
}

.dark .mobile-drawer {
  background: rgba(15, 23, 42, 0.95);
  border-top-color: rgba(255, 255, 255, 0.06);
}

.drawer-link {
  display: block;
  padding: 0.75rem 0;
  font-weight: 600;
  color: #0f172a;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.dark .drawer-link {
  color: #e5e7eb;
  border-color: rgba(255, 255, 255, 0.05);
}

.porsche-hero {
  position: relative;
  min-height: 85vh;
  overflow: hidden;
  background: #000;
}

.hero-media video {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: saturate(1.05);
}

.hero-gradient {
  position: absolute;
  inset: 0;
  background: linear-gradient(120deg, rgba(31, 45, 52, 0.88), rgba(0, 0, 0, 0.35));
  pointer-events: none;
}

.hero-overlay {
  position: relative;
  z-index: 2;
  min-height: 85vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 0.75rem;
  color: #f8fafc;
  padding: 4rem 1.25rem;
}

.hero-meta {
  display: flex;
  align-items: center;
  gap: 0.9rem;
}

.hero-pill {
  display: inline-flex;
  align-items: center;
  padding: 0.45rem 0.9rem;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.16);
  letter-spacing: 0.08em;
  text-transform: uppercase;
  font-size: 0.75rem;
  font-weight: 700;
}

.hero-divider {
  flex: 1;
  height: 1px;
  background: linear-gradient(90deg, rgba(255, 255, 255, 0.25), transparent);
}

.hero-headline {
  font-size: clamp(2.8rem, 4vw + 1rem, 4.6rem);
  line-height: 1.05;
  letter-spacing: -0.03em;
  font-weight: 700;
  text-shadow: 0 18px 40px rgba(0, 0, 0, 0.55);
}

.headline-letter {
  opacity: 0;
  display: inline-block;
  transition: opacity 0.35s ease, transform 0.35s ease;
  transform: translateY(8px);
}

.headline-letter.visible {
  opacity: 1;
  transform: translateY(0);
}

.letter-strong {
  font-weight: 800;
  font-style: normal;
}

.letter-italic {
  font-weight: 400;
  font-style: italic;
}

.hero-copy {
  max-width: 640px;
  font-size: 1.1rem;
  color: rgba(248, 250, 252, 0.85);
}

.hero-cta {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
  margin-top: 0.5rem;
}

.porsche-section {
  padding: 4rem 0;
}

.porsche-stage {
  padding: 3.5rem 0 2rem;
  background: linear-gradient(180deg, #ffffff 0%, #f4f4f4 100%);
}

.dark .porsche-stage {
  background: linear-gradient(180deg, #0b1220 0%, #0f172a 100%);
}

.section-head {
  max-width: 720px;
}

.section-kicker {
  letter-spacing: 0.12em;
  text-transform: uppercase;
  font-weight: 700;
  color: #8c8c8c;
}

.dark .section-kicker {
  color: #94a3b8;
}

.section-sub {
  color: #4b5563;
  margin-top: 0.65rem;
}

.accent-bar {
  width: 64px;
  height: 3px;
  background: rgb(var(--accent));
  margin-bottom: 0.75rem;
}

.stage-grid {
  display: grid;
  gap: 1.25rem;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
}

.stage-card {
  position: relative;
  border-radius: 18px;
  overflow: hidden;
  min-height: 260px;
  isolation: isolate;
  box-shadow: 0 24px 48px -36px rgba(0, 0, 0, 0.5);
}

.stage-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  transform: scale(1.02);
}

.stage-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, rgba(0, 0, 0, 0.05) 0%, rgba(0, 0, 0, 0.55) 100%);
  color: #fff;
  display: grid;
  gap: 0.35rem;
  align-content: flex-end;
  padding: 1.5rem;
}

.stage-kicker {
  letter-spacing: 0.12em;
  text-transform: uppercase;
  font-weight: 700;
  font-size: 0.8rem;
  opacity: 0.9;
}

.stage-title {
  font-size: 1.4rem;
  font-weight: 700;
  letter-spacing: -0.01em;
}

.stage-copy {
  color: rgba(255, 255, 255, 0.82);
}

.tile-grid {
  display: grid;
  gap: 1.5rem;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
}

.porsche-tile {
  background: rgb(var(--card));
  border-radius: 18px;
  overflow: hidden;
  border: 1px solid rgba(0, 0, 0, 0.05);
  box-shadow: 0 20px 40px -28px rgba(0, 0, 0, 0.35);
  display: flex;
  flex-direction: column;
}

.dark .porsche-tile {
  border-color: rgba(255, 255, 255, 0.1);
  box-shadow: 0 24px 48px -32px rgba(0, 0, 0, 0.6);
}

.tile-media img {
  width: 100%;
  height: 220px;
  object-fit: cover;
  display: block;
}

.tile-body {
  padding: 1.25rem 1.35rem 1.5rem;
  display: grid;
  gap: 0.45rem;
}

.tile-label {
  letter-spacing: 0.08em;
  text-transform: uppercase;
  font-size: 0.75rem;
  color: #6b7280;
  font-weight: 700;
}

.tile-title {
  font-size: 1.35rem;
  font-weight: 700;
  color: #0f172a;
}

.dark .tile-title {
  color: #e5e7eb;
}

.tile-copy {
  color: #4b5563;
}

.tile-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 700;
  color: rgb(var(--accent));
  margin-top: 0.35rem;
}

.contact-section {
  background: linear-gradient(180deg, #f7f7f7 0%, #ededed 100%);
}

.dark .contact-section {
  background: linear-gradient(180deg, #05080c 0%, #0b1220 100%);
}

.contact-grid {
  display: grid;
  gap: 2rem;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  align-items: start;
}

.contact-card {
  background: rgb(var(--card));
  box-shadow: 0 24px 48px -32px rgba(0, 0, 0, 0.25);
}

.input-label {
  font-size: 0.9rem;
  color: #111827;
}

.form-error {
  margin-top: 0.35rem;
  font-size: 0.85rem;
  color: #dc2626;
}

.privacy-note {
  font-size: 0.85rem;
  color: #6b7280;
}

.alert {
  border-radius: 12px;
  padding: 0.9rem 1rem;
  margin-bottom: 1rem;
  font-weight: 600;
}

.alert-success {
  background: rgba(16, 185, 129, 0.12);
  color: #047857;
  border: 1px solid rgba(16, 185, 129, 0.35);
}

.alert-error {
  background: rgba(220, 38, 38, 0.12);
  color: #b91c1c;
  border: 1px solid rgba(220, 38, 38, 0.35);
}

.footer-row {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.footer-links {
  display: inline-flex;
  align-items: center;
  gap: 1.25rem;
}

@media (min-width: 640px) {
  .footer-row {
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
  }
}

.section-sub,
.tile-copy,
.privacy-note {
  color: #4b5563;
}

.dark .section-sub,
.dark .tile-copy,
.dark .privacy-note {
  color: #cbd5e1;
}

.slogan-fade-enter-active,
.slogan-fade-leave-active {
  transition: opacity 0.6s ease, transform 0.6s ease;
}

.slogan-fade-enter-from,
.slogan-fade-leave-to {
  opacity: 0;
  transform: translateY(15px);
}

@media (max-width: 768px) {
  .porsche-bar {
    grid-template-columns: 1fr auto;
  }
  .porsche-nav {
    display: none;
  }
  .hero-headline {
    font-size: clamp(2.2rem, 6vw + 1rem, 3.4rem);
  }
  .hero-overlay {
    min-height: 70vh;
  }
}
</style>
