<template>
  <div class="simpsucc-page">
    <header class="simpsucc-header transparent-header">
      <div class="simpsucc-bar container-prose">
        <div class="simpsucc-brand">
          <button class="burger-btn" type="button" aria-label="Menü öffnen">
            <span class="burger-lines" aria-hidden="true"></span>
          </button>
          <span class="brand-mark"><strong>Simple.</strong><i>Successful.</i></span>
        </div>
        <div class="simpsucc-actions">
          <select v-model="currentLocale" class="lang-select" :aria-label="$t('nav.language')" :title="$t('nav.language')">
            <option v-for="code in supportedLocales" :key="code" :value="code">{{ code.toUpperCase() }}</option>
          </select>
          <a href="#kontakt" class="nav-link discovery-link">{{ $t('cta.discovery_call') }}</a>
        </div>
      </div>
    </header>

    <section class="simpsucc-hero">
      <div class="hero-media">
        <video ref="heroVideoEl" autoplay muted loop playsinline>
          <source :src="heroVideo" type="video/mp4" />
        </video>
      </div>
      <div class="hero-gradient"></div>
      <div class="hero-overlay container-prose">
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
        <button
          class="hero-video-toggle"
          type="button"
          :aria-pressed="heroVideoPlaying"
          :aria-label="heroVideoPlaying ? 'Video pausieren' : 'Video abspielen'"
          @click="toggleHeroVideo"
        >
          <span aria-hidden="true">{{ heroVideoPlaying ? '⏸' : '▶' }}</span>
        </button>
      </div>
    </section>

    <section class="intro-pitch">
      <div class="container-prose intro-grid">
        <p class="intro-lead">{{ $t('sections.intro.lead') }}</p>
        <p class="intro-copy">{{ $t('sections.intro.body') }}</p>
      </div>
    </section>

    <section class="network-section">
      <div class="container-prose network-inner">
        <div class="section-head">
          <h2 class="h2">{{ $t('sections.network.title') }}</h2>
        </div>
        <div class="network-layout">
          <div class="network-media-grid">
            <router-link v-for="card in networkCards" :key="card.key" :to="card.to" class="network-card">
              <div class="network-media">
                <img :src="card.image" :alt="card.alt" loading="lazy" />
                <span class="play-badge" aria-hidden="true">
              <span class="play-icon"></span>
              </span>
            </div>
            <div class="network-body">
              <p v-if="card.kicker" class="network-kicker">{{ card.kicker }}</p>
              <h3 class="network-title">{{ card.title }}</h3>
            </div>
          </router-link>
        </div>
          <div class="network-aside">
            <p class="network-aside-text">
              {{ $t('sections.network.aside') }}
            </p>
          </div>
        </div>
        <div class="section-cta">
          <a href="#kontakt" class="nav-link discovery-link">{{ $t('cta.discovery_call') }}</a>
        </div>
      </div>
    </section>
    <section class="product-section product-criteria">
      <div class="container-prose network-inner">
        <div class="section-head">
          <h2 class="h2">{{ $t('sections.products.title') }}</h2>
        </div>
        <div class="network-layout">
          <div class="network-media-grid product-card-grid">
            <router-link :to="productCard.to" class="network-card">
              <div class="network-media">
                <img :src="productCard.image" :alt="productCard.alt" loading="lazy" />
                <span class="play-badge" aria-hidden="true">
              <span class="play-icon"></span>
              </span>
            </div>
            <div class="network-body">
              <p v-if="productCard.kicker" class="network-kicker">{{ productCard.kicker }}</p>
              <h3 class="network-title">{{ productCard.title }}</h3>
            </div>
          </router-link>
        </div>
          <div class="network-aside">
            <p class="network-aside-text">{{ $t('sections.products.prereq') }}</p>
            <p class="network-aside-text">
              {{ $t('sections.products.optimal') }}
            </p>
            <ul class="criteria-list">
              <li v-for="(item, idx) in productCriteriaBullets" :key="idx">{{ item }}</li>
            </ul>
          </div>
        </div>
        <div class="section-cta">
          <a href="#kontakt" class="nav-link discovery-link">{{ $t('cta.discovery_call') }}</a>
        </div>
      </div>
    </section>
    <section class="theory-section">
      <div class="container-prose network-inner">
        <div class="section-head">
          <h2 class="h2">{{ $t('sections.theory.title') }}</h2>
        </div>
        <div class="network-grid">
          <router-link :to="actionCard.to" class="network-card">
            <div class="network-media">
              <img :src="actionCard.image" :alt="actionCard.alt" loading="lazy" />
              <span class="play-badge" aria-hidden="true">
              <span class="play-icon"></span>
              </span>
            </div>
            <div class="network-body">
              <p v-if="actionCard.kicker" class="network-kicker">{{ actionCard.kicker }}</p>
              <h3 class="network-title">{{ actionCard.title }}</h3>
            </div>
          </router-link>
        </div>
        <div class="section-cta">
          <a href="#kontakt" class="nav-link discovery-link">{{ $t('cta.discovery_call') }}</a>
        </div>
      </div>
    </section>

    <section class="network-section effectiveness-section">
      <div class="container-prose network-inner">
        <div class="section-head">
          <h2 class="h2">{{ $t('sections.effectiveness.title') }}</h2>
        </div>
        <div class="network-grid effectiveness-grid">
          <router-link v-for="card in effectivenessCards" :key="card.key" :to="card.to" class="network-card">
            <div class="network-media">
              <img :src="card.image" :alt="card.alt" loading="lazy" />
              <span class="play-badge" aria-hidden="true">
              <span class="play-icon"></span>
              </span>
            </div>
            <div class="network-body">
              <p v-if="card.kicker" class="network-kicker">{{ card.kicker }}</p>
              <h3 class="network-title">{{ card.title }}</h3>
            </div>
          </router-link>
        </div>
        <div class="section-cta">
          <a href="#kontakt" class="nav-link discovery-link">{{ $t('cta.discovery_call') }}</a>
        </div>
      </div>
    </section>
    <section class="future-section">
      <div class="container-prose future-inner">
        <div class="section-head">
          <h2 class="h2">{{ $t('sections.future.title') }}</h2>
        </div>
        <div class="future-content">
          <div class="future-block">
            <p class="future-copy keep-dark">{{ $t('sections.future.p1') }}</p>
            <p class="future-copy keep-dark">{{ $t('sections.future.p2') }}</p>
            <p class="future-copy keep-dark">{{ $t('sections.future.p3') }}</p>
            <ul class="future-list keep-dark">
              <li v-for="(item, idx) in futureList1" :key="`f1-${idx}`">{{ item }}</li>
            </ul>
          </div>

          <div class="future-block">
            <p class="future-copy keep-dark">{{ $t('sections.future.p4') }}</p>
            <p class="future-copy keep-dark">{{ $t('sections.future.p5') }}</p>
            <ul class="future-list keep-dark">
              <li v-for="(item, idx) in futureList2" :key="`f2-${idx}`">{{ item }}</li>
            </ul>
          </div>

          <div class="future-block">
            <p class="future-copy keep-dark">
              {{ $t('sections.future.p6') }}
            </p>
            <p class="future-copy keep-dark">{{ $t('sections.future.p7') }}</p>
            <ul class="future-list keep-dark">
              <li v-for="(item, idx) in futureList3" :key="`f3-${idx}`">{{ item }}</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <section id="kontakt" class="simpsucc-section contact-section">
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
            <input class="hidden" type="text" id="contact-website" name="website" v-model="honeypot" autocomplete="off" tabindex="-1" aria-hidden="true" />

            <div>
              <label class="input-label" for="contact-name">{{ $t('contact.form.name_label') }}</label>
              <input
                id="contact-name"
                name="name"
                class="input mt-1"
                :placeholder="$t('contact.form.name_placeholder')"
                v-model.trim="form.name"
                :disabled="submitting"
                required
              />
              <p v-if="errors.name" class="form-error">{{ errors.name }}</p>
            </div>
            <div>
              <label class="input-label" for="contact-email">{{ $t('contact.form.email_label') }}</label>
              <input
                id="contact-email"
                name="email"
                class="input mt-1"
                type="email"
                :placeholder="$t('contact.form.email_placeholder')"
                v-model.trim="form.email"
                :disabled="submitting"
                required
              />
              <p v-if="errors.email" class="form-error">{{ errors.email }}</p>
            </div>
            <div class="md:col-span-2">
              <label class="input-label" for="contact-phone">{{ $t('contact.form.phone_label') }}</label>
              <input
                id="contact-phone"
                name="phone"
                class="input mt-1"
                type="tel"
                :placeholder="$t('contact.form.phone_placeholder')"
                v-model.trim="form.phone"
                :disabled="submitting"
                required
              />
              <p v-if="errors.phone" class="form-error">{{ errors.phone }}</p>
            </div>
            <div class="md:col-span-2">
              <label class="input-label" for="contact-goal">{{ $t('contact.form.goal_label') }}</label>
              <textarea
                id="contact-goal"
                name="goal"
                class="textarea mt-1"
                :placeholder="$t('contact.form.goal_placeholder')"
                v-model.trim="form.goal"
                :disabled="submitting"
                rows="5"
                required
              ></textarea>
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
import shotCup from '@/images/bilder/stock.cup.edited.png'

const heroVideo = '/build/videos/Video.Header.Simple.Successful.mp4'

const isDark = ref(false)
const animatedLetters = ref([])
const letterTimers = []
let letterCycle = null
const videoSectionRefs = ref([])
const videoStackRef = ref(null)
let videoObserver = null
const heroVideoEl = ref(null)
const heroVideoPlaying = ref(true)

function syncBodyInvert () {
  try {
    const body = document.body
    if (!body) return
    body.classList.remove('invert-page')
  } catch (e) {}
}

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

const { t, tm, locale } = useI18n()
const supportedLocales = ['de', 'en', 'es']
const currentLocale = ref(locale.value)

watch(currentLocale, (val) => {
  if (!val) return
  locale.value = val
  try { localStorage.setItem('locale', val) } catch (e) {}
  try { document.documentElement.setAttribute('lang', val) } catch (e) {}
})

const heroPhrases = ['SIMPLE.SUCCESSFUL.', 'EINFACH.ERFOLGREICH.', 'SENCILLO.EXITOSO.']
const heroPhraseIndex = ref(0)
const currentHeroPhrase = computed(() => heroPhrases[heroPhraseIndex.value] || heroPhrases[0])
function startLetterReveal () {
  letterTimers.forEach(clearTimeout)
  letterTimers.length = 0
  const phrase = currentHeroPhrase.value || ''
  const firstDot = phrase.indexOf('.')
  const splitIdx = firstDot >= 0 ? firstDot + 1 : phrase.length
  const boldPart = phrase.slice(0, splitIdx)
  const italicPart = phrase.slice(splitIdx)
  const baseLetters = [
    ...boldPart.split('').map(char => ({ char, part: 'bold', visible: false })),
    ...italicPart.split('').map(char => ({ char, part: 'italic', visible: false }))
  ]
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

const networkCards = computed(() => [
  {
    key: 'direct',
    title: t('sections.network.cards.direct'),
    kicker: t('sections.common.video'),
    to: { name: 'video_marketing' },
    image: shotMarketing,
    alt: t('sections.network.cards.direct_alt')
  },
  {
    key: 'career',
    title: t('sections.network.cards.career'),
    kicker: t('sections.common.video'),
    to: { name: 'video_marketing_explain' },
    image: shotExplain,
    alt: t('sections.network.cards.career_alt')
  }
])

const productCard = computed(() => ({
  title: t('sections.products.cardTitle'),
  kicker: t('sections.common.video'),
  to: { name: 'video_product' },
  image: shotProduct,
  alt: t('sections.products.cardAlt')
}))

const actionCard = computed(() => ({
  title: t('sections.theory.cardTitle'),
  kicker: t('sections.common.video'),
  to: { name: 'video_marketing_explain' },
  image: shotCup,
  alt: t('sections.theory.cardAlt')
}))

const effectivenessCards = computed(() => [
  {
    key: 'run90',
    title: t('sections.effectiveness.cards.run90'),
    kicker: t('sections.common.video'),
    to: { name: 'video_marketing' },
    image: shotNight,
    alt: t('sections.effectiveness.cards.run90_alt')
  },
  {
    key: 'coaching',
    title: t('sections.effectiveness.cards.coaching'),
    kicker: t('sections.common.video'),
    to: { name: 'video_marketing_explain' },
    image: shotWorkshop,
    alt: t('sections.effectiveness.cards.coaching_alt')
  }
])

const productCriteriaBullets = computed(() => tm('sections.products.bullets') || [])
const futureList1 = computed(() => tm('sections.future.list1') || [])
const futureList2 = computed(() => tm('sections.future.list2') || [])
const futureList3 = computed(() => tm('sections.future.list3') || [])

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

const videoSections = computed(() => [
  { key: 'v1', title: 'Hero Cut', kicker: 'Motion', src: heroVideo, poster: heroPoster },
  { key: 'v2', title: 'Workshop Detail', kicker: 'Craft', src: heroVideo, poster: shotWorkshop },
  { key: 'v3', title: 'Lifestyle Shot', kicker: 'Style', src: heroVideo, poster: shotLifestyle },
  { key: 'v4', title: 'Night Ride', kicker: 'Drive', src: heroVideo, poster: shotNight },
  { key: 'v5', title: 'Product Focus', kicker: 'Clarity', src: heroVideo, poster: shotProduct },
  { key: 'v6', title: 'Explain Teaser', kicker: 'Story', src: heroVideo, poster: shotExplain },
  { key: 'v7', title: 'Signature', kicker: 'Icon', src: heroVideo, poster: shotCup }
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

function toggleHeroVideo() {
  const hv = heroVideoEl.value
  if (!hv) return
  if (hv.paused) {
    hv.play().then(() => {
      heroVideoPlaying.value = true
    }).catch(() => {})
  } else {
    hv.pause()
    heroVideoPlaying.value = false
  }
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
  letterCycle = setInterval(() => {
    heroPhraseIndex.value = (heroPhraseIndex.value + 1) % heroPhrases.length
    startLetterReveal()
  }, 5200)

  try {
    const hv = heroVideoEl.value
    if (hv) {
      heroVideoPlaying.value = !hv.paused
      const onPlay = () => { heroVideoPlaying.value = true }
      const onPause = () => { heroVideoPlaying.value = false }
      hv.addEventListener('play', onPlay)
      hv.addEventListener('pause', onPause)
      hv.addEventListener('ended', onPause)
    }
  } catch (e) {}

  try {
    videoObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (!entry.target) return
        if (entry.isIntersecting) {
          entry.target.classList.add('section-dark')
        } else {
          entry.target.classList.remove('section-dark')
        }
      })
    }, { threshold: 0.35 })
    videoSectionRefs.value.forEach((el) => el && videoObserver.observe(el))
  } catch (e) {}
})

onUnmounted(() => {
  letterTimers.forEach(clearTimeout)
  if (letterCycle) clearInterval(letterCycle)
  if (videoObserver) videoObserver.disconnect()
  try { document.body.classList.remove('invert-page') } catch (e) {}
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
* {
  font-synthesis: none !important;
}

.simpsucc-page {
  background: inherit;
  color: inherit;
}

:global(body) {
  background: #f7f7f7;
  color: #000;
  transition: none;
}

.simpsucc-header {
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

.simpsucc-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1.25rem;
  padding: 0.9rem 0;
}

.simpsucc-brand {
  display: inline-flex;
  align-items: center;
  gap: 0.65rem;
}

.brand-mark {
  font-size: 0.95rem;
  font-weight: 400;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: #fff;
}

.burger-btn {
  width: 50px;
  height: 50px;
  border-radius: 25px;
  border: 1px solid rgba(255, 255, 255, 0.35);
  background: rgba(0, 0, 0, 0.25);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}

.burger-btn:hover,
.burger-btn:focus-visible {
  transform: translateY(-2px);
  border-color: rgba(255, 255, 255, 0.55);
  box-shadow: 0 12px 24px -14px rgba(0, 0, 0, 0.55);
}

.burger-lines {
  position: relative;
  width: 18px;
  height: 2px;
  background: #fff;
  display: block;
}

.burger-lines::before,
.burger-lines::after {
  content: '';
  position: absolute;
  left: 0;
  width: 18px;
  height: 2px;
  background: #fff;
}

.burger-lines::before {
  top: -6px;
}

.burger-lines::after {
  top: 6px;
}

.simpsucc-nav {
  justify-self: center;
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.nav-item {
  font-weight: 600;
  color: #fff;
  text-decoration: none;
  position: relative;
  padding: 0.35rem 0;
}

.dark .nav-item {
  color: #000;
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
  height: 50px;
  display: inline-flex;
  align-items: center;
  border: 1px solid rgba(255, 255, 255, 0.5);
  border-radius: 999px;
  background: rgba(0, 0, 0, 0.35);
  color: #fff;
  box-shadow: 0 8px 24px -16px rgba(0, 0, 0, 0.35);
}

.dark .nav-admin {
  background: #0b1220;
  border-color: rgba(255, 255, 255, 0.08);
  color: #000;
}

.simpsucc-actions {
  display: inline-flex;
  align-items: center;
  justify-content: flex-end;
  gap: 0.75rem;
}

.lang-select {
  min-width: 50px;
  max-width: 50px;
  height: 50px;
  border-radius: 999px;
  border: 1px solid #fff;
  background: none;
  color: #fff;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  box-shadow: 0 10px 24px -16px rgba(0, 0, 0, 0.35);
  padding: 0 14px;
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  background-image: none;
  cursor: pointer;
}

.discovery-link {
  min-height: 50px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.55rem 1rem;
  border-radius: 999px;
  border: 1px solid rgba(255, 255, 255, 0.5);
  background: rgba(0, 0, 0, 0.25);
  color: #fff;
  font-weight: 700;
  letter-spacing: 0.02em;
}

.dark .action-btn,
.dark .lang-select {
  background: #0b1220;
  color: #000;
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
  color: #000;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.dark .drawer-link {
  color: #000;
  border-color: rgba(255, 255, 255, 0.05);
}

.simpsucc-hero {
  position: relative;
  min-height: 100vh;
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
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  align-items: center;
  gap: 0.75rem;
  color: #f8fafc;
  padding: 6rem 1.25rem 8vh;
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
  font-size: clamp(3.2rem, 4vw + 1.15rem, 5.3rem);
  line-height: 1.05;
  letter-spacing: -0.03em;
  font-weight: 600;
  text-shadow: 0 18px 40px rgba(0, 0, 0, 0.55);
  display: inline-flex;
  flex-wrap: wrap;
  justify-content: center;
  text-align: center;
}

.hero-video-toggle {
  position: absolute;
  right: 1.5rem;
  bottom: 1.5rem;
  z-index: 3;
  min-width: 56px;
  height: 56px;
  border-radius: 999px;
  border: 1px solid rgba(255, 255, 255, 0.65);
  background: rgba(0, 0, 0, 0.5);
  color: #fff;
  font-weight: 800;
  letter-spacing: 0.02em;
  backdrop-filter: blur(6px);
  box-shadow: 0 12px 30px -18px rgba(0, 0, 0, 0.8);
  transition: transform 0.2s ease, box-shadow 0.3s ease, background-color 0.3s ease;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
}

.hero-video-toggle:hover,
.hero-video-toggle:focus-visible {
  transform: translateY(-2px);
  box-shadow: 0 16px 36px -18px rgba(0, 0, 0, 0.9);
  background: rgba(0, 0, 0, 0.65);
}

.headline-letter {
  opacity: 0;
  visibility: hidden;
  display: inline-block;
  transition:
    opacity 0.35s ease,
    transform 0.5s cubic-bezier(0.22, 1.4, 0.64, 1),
    filter 0.45s ease;
  transform: translateY(14px);
  filter: blur(8px);
  will-change: transform, filter, opacity;
}

.headline-letter.visible {
  opacity: 1;
  visibility: visible;
  transform: translateY(-6px);
  filter: blur(0);
}

.letter-strong {
  font-weight: 500;
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

.simpsucc-section {
  padding: 4rem 0;
}

.intro-pitch {
  background: #000;
  color: #fff;
  padding: 2.5rem 2.5rem;
}

.intro-grid {
  display: grid;
  gap: 0.75rem;
  text-align: center;
  justify-items: center;
  padding-left: 0.35rem;
  padding-right: 0.35rem;
}

.intro-lead,
.intro-copy {
  max-width: 860px;
  font-size: clamp(1.25rem, 2vw + 0.5rem, 1.6rem);
  line-height: 1.55;
  font-weight: 500;
  color: #fff;
}

.intro-lead {
  font-size: clamp(1.375rem, 2vw + 0.55rem, 1.76rem);
}

.intro-lead {
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

.dynamic-section {
  position: relative;
  overflow: visible;
  background: inherit;
  color: inherit;
  transition: none;
}

.dynamic-section::before {
  content: '';
  display: none;
}

.dynamic-section .container-prose {
  position: relative;
  z-index: 1;
}

.dynamic-section.section-active {
  background: inherit;
  color: inherit;
}

.invert-on-view.section-active {
  background: inherit;
  color: inherit;
}

:global(body.invert-page) .dynamic-section,
:global(body.invert-page) .simpsucc-section,
:global(body.invert-page) .network-section,
:global(body.invert-page) .product-section,
:global(body.invert-page) .future-section,
:global(body.invert-page) .intro-pitch {
  background: inherit;
  color: inherit;
}

.dynamic-section .section-kicker,
.dynamic-section.section-active .section-kicker {
  color: inherit;
}

.invert-on-view.section-active .section-kicker {
  color: inherit;
}

.dynamic-section .section-sub,
.dynamic-section.section-active .section-sub {
  color: inherit;
}

.invert-on-view.section-active .section-sub {
  color: inherit;
}

:global(body.invert-page) .section-kicker,
:global(body.invert-page) .section-sub {
  color: #000;
}

.future-copy {
  font-size: 1.02rem;
  line-height: 1.7;
  color: #000;
}

.future-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: grid;
  gap: 0.35rem;
  font-weight: 700;
  font-size: 1.02rem;
  color: #000;
}

:global(body.invert-page) .network-aside-text {
  color: #000;
}

:global(body.invert-page) .future-copy,
:global(body.invert-page) .future-list {
  color: #000;
}

.network-section {
  padding: 3.5rem 0;
}

.product-criteria,
.effectiveness-section {
  background: #000;
  color: #fff;
}

.product-criteria .section-head .h2,
.effectiveness-section .section-head .h2 {
  color: #fff;
}

.product-criteria .network-aside-text,
.product-criteria .criteria-list,
.product-criteria .criteria-list li,
.product-criteria .network-title,
.product-criteria .network-kicker {
  color: #fff;
}

.product-criteria .network-body,
.effectiveness-section .network-body {
  color: #fff;
}

.effectiveness-section .network-kicker,
.effectiveness-section .network-title,
.effectiveness-section .network-aside-text {
  color: #fff;
}

.product-criteria .network-card,
.effectiveness-section .network-card {
  background: rgba(255, 255, 255, 0.06);
  border-color: rgba(255, 255, 255, 0.2);
}

.product-criteria .network-card:hover,
.product-criteria .network-card:focus-visible,
.effectiveness-section .network-card:hover,
.effectiveness-section .network-card:focus-visible {
  border-color: rgba(255, 255, 255, 0.35);
}

.product-criteria .criteria-list li::marker {
  color: #fff;
}

.network-inner {
  display: grid;
  gap: 1.5rem;
}

.network-layout {
  display: grid;
  gap: 1.5rem;
}

@media (min-width: 960px) {
  .network-layout {
    grid-template-columns: minmax(0, 1.4fr) minmax(280px, 1fr);
    align-items: center;
  }
}

.future-inner {
  display: grid;
  gap: 1.5rem;
}

.future-content {
  display: grid;
  gap: 1.25rem;
}

@media (min-width: 960px) {
  .future-inner {
    grid-template-columns: minmax(240px, 0.8fr) minmax(0, 1.6fr);
    align-items: start;
  }
  .future-content {
    grid-template-columns: repeat(2, minmax(0, 1fr));
    grid-column: 2 / 3;
  }
  .future-block {
    border-left: 1px solid rgba(0, 0, 0, 0.08);
    padding-left: 1rem;
  }
  :global(body.invert-page) .future-block {
    border-left-color: rgba(255, 255, 255, 0.15);
  }
  .future-section .section-head {
    grid-column: 1 / 2;
  }
}

.network-media-grid {
  display: grid;
  gap: 1rem;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
}

.network-media-grid .network-card {
  max-width: 320px;
  justify-self: start;
}

.section-cta {
  margin-top: 1.5rem;
}

.network-aside-text {
  font-size: 1rem;
  line-height: 1.6;
  font-weight: 700;
  letter-spacing: 0.01em;
  color: inherit;
}

.criteria-list {
  list-style: disc;
  padding-left: 1.2rem;
  display: grid;
  gap: 0.35rem;
  font-weight: 600;
  color: inherit;
}

:global(body.invert-page) .network-aside-text,
:global(body.invert-page) .criteria-list {
  color: inherit;
}

.network-aside-text,
.criteria-list {
  font-size: 1.08rem;
}

.keep-dark {
  color: #000 !important;
}

.product-criteria .network-media-grid {
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
}

.product-criteria .network-media-grid .network-card {
  max-width: 360px;
}

.future-section {
  padding: 3.5rem 0;
}

.future-inner {
  display: grid;
  gap: 1.5rem;
}

.future-block {
  display: grid;
  gap: 0.7rem;
}

.future-copy {
  font-size: 1.02rem;
  line-height: 1.7;
  color: #fff;
}

.future-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: grid;
  gap: 0.35rem;
  font-weight: 700;
  font-size: 1.02rem;
  color: #fff;
}

.future-divider {
  height: 1px;
  width: 100%;
  background: linear-gradient(90deg, rgba(0, 0, 0, 0.08), transparent, rgba(0, 0, 0, 0.08));
  margin: 0.4rem 0;
}

.dynamic-section.section-active .future-divider {
  background: linear-gradient(90deg, rgba(0, 0, 0, 0.08), transparent, rgba(0, 0, 0, 0.08));
}

.network-grid {
  display: grid;
  gap: 1.25rem;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
}

.network-card {
  position: relative;
  display: block;
  text-decoration: none;
  color: inherit;
  background: rgba(255, 255, 255, 0.04);
  border: 1px solid rgba(255, 255, 255, 0.18);
  border-radius: 16px;
  overflow: hidden;
  transition:
    transform 0.25s ease,
    border-color 0.6s ease,
    box-shadow 0.6s ease,
    background-color 0.6s ease,
    color 0.6s ease;
}

.network-card:hover,
.network-card:focus-visible {
  transform: translateY(0);
  border-color: rgba(255, 255, 255, 0.35);
  box-shadow: 0 18px 38px -26px rgba(0, 0, 0, 0.35);
}

.network-media {
  position: relative;
  overflow: hidden;
  aspect-ratio: 16 / 9;
}

.network-media img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  filter: saturate(1.05);
  transition: transform 0.6s ease;
}

.play-badge {
  position: absolute;
  inset: 0;
  display: grid;
  place-items: center;
  background: linear-gradient(180deg, rgba(0, 0, 0, 0.15) 0%, rgba(0, 0, 0, 0.45) 100%);
  pointer-events: none;
}

.play-icon {
  width: 58px;
  height: 58px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.4);
  border: 2px solid rgba(255, 255, 255, 0.9);
  display: grid;
  place-items: center;
  position: relative;
  box-shadow: 0 10px 24px -14px rgba(0, 0, 0, 0.55);
}

.play-icon::before {
  content: '';
  width: 0;
  height: 0;
  border-top: 10px solid transparent;
  border-bottom: 10px solid transparent;
  border-left: 16px solid #fff;
  transform: translateX(2px);
}

.network-body {
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  padding: 0.85rem 1rem 1.05rem;
  display: grid;
  gap: 0.1rem;
  background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.82) 100%);
  color: #fff;
  text-shadow: 0 10px 22px rgba(0, 0, 0, 0.65);
}

.network-kicker {
  text-transform: uppercase;
  letter-spacing: 0.12em;
  font-size: 0.75rem;
  color: #000;
}

.network-title {
  font-size: 1.05rem;
  font-weight: 700;
  color: #fff;
}

.dynamic-section.section-active .network-card {
  background: rgba(255, 255, 255, 0.04);
  border-color: rgba(255, 255, 255, 0.18);
  box-shadow: 0 18px 38px -26px rgba(0, 0, 0, 0.65);
}

.dynamic-section.section-active .network-card:hover,
.dynamic-section.section-active .network-card:focus-visible {
  border-color: rgba(255, 255, 255, 0.35);
}

.invert-on-view.section-active .network-card {
  background: rgba(255, 255, 255, 0.05);
  border-color: rgba(255, 255, 255, 0.2);
}

.invert-on-view.section-active .network-card:hover,
.invert-on-view.section-active .network-card:focus-visible {
  border-color: rgba(255, 255, 255, 0.35);
}

.invert-on-view.section-active .network-kicker {
  color: #000;
}

.invert-on-view.section-active .network-title {
  color: #fff;
}

.product-section {
  padding: 3.5rem 0;
}

.theory-section {
  padding: 3.5rem 0;
  background: #fff;
  color: #000;
}

.theory-section .network-layout {
  display: grid;
  gap: 1.5rem;
}

@media (min-width: 960px) {
  .theory-section .network-layout {
    grid-template-columns: minmax(0, 1.4fr) minmax(280px, 1fr);
    align-items: center;
  }
}

.product-section .network-grid {
  grid-template-columns: repeat(auto-fit, minmax(280px, 420px));
  justify-content: center;
}

.theory-section .network-grid {
  display: grid;
  gap: 1rem;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  align-items: stretch;
}

@media (min-width: 900px) {
  .effectiveness-grid {
    display: flex;
    gap: 1.25rem;
    align-items: stretch;
    min-height: 340px;
    height: 360px;
  }
  .effectiveness-grid .network-card {
    display: flex;
    flex-direction: column;
    height: 100%;
    flex: 1 1 0%;
    transition:
      flex-grow 0.8s ease,
      flex-basis 0.8s ease;
  }
  .effectiveness-grid:hover .network-card {
    flex: 0.9 1 0%;
  }
  .effectiveness-grid .network-card:hover {
    flex: 1.1 1 0%;
  }
  .effectiveness-grid .network-media {
    flex: 1;
    height: 100%;
    aspect-ratio: unset;
    min-height: 0;
  }
  .effectiveness-grid .network-card:hover .network-media img {
    transform: scale(1.12);
  }
}

@media (max-width: 640px) {
  .network-grid {
    grid-template-columns: 1fr;
  }
}

.simpsucc-stage {
  padding: 3.5rem 0 2rem;
  background: linear-gradient(180deg, #ffffff 0%, #f4f4f4 100%);
}

.dark .simpsucc-stage {
  background: linear-gradient(180deg, #0b1220 0%, #000000 100%);
}

.video-stack {
  padding: 2rem 0 1rem;
  gap: 0;
}

.video-section {
  padding: 3rem 0;
  transition: background-color 0.4s ease;
  background: transparent;
}

.video-wrapper {
  display: grid;
  gap: 1.25rem;
}

.video-head {
  max-width: 720px;
}

.video-frame {
  border-radius: 18px;
  overflow: hidden;
  box-shadow: 0 24px 48px -32px rgba(0, 0, 0, 0.35);
}

.video-frame video {
  width: 100%;
  display: block;
  background: #000;
  aspect-ratio: 16 / 9;
  object-fit: cover;
}

.video-section.section-dark {
  background: #000;
}

.video-section.section-dark .video-head .h3,
.video-section.section-dark .section-kicker,
.video-section.section-dark .section-sub {
  color: #000;
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
  color: #000;
  margin-top: 0.65rem;
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

.simpsucc-tile {
  background: rgb(var(--card));
  border-radius: 18px;
  overflow: hidden;
  border: 1px solid rgba(0, 0, 0, 0.05);
  box-shadow: 0 20px 40px -28px rgba(0, 0, 0, 0.35);
  display: flex;
  flex-direction: column;
}

.dark .simpsucc-tile {
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
  color: #000;
}

.dark .tile-title {
  color: #000;
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
  .simpsucc-bar {
    grid-template-columns: 1fr auto;
  }
  .simpsucc-nav {
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
