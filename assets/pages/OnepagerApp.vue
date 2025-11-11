<template>
  <!-- NAVBAR -->
  <header class="navbar">
    <div class="navbar-inner">
      <!-- Desktop Nav -->
      <nav class="hidden md:flex items-center gap-6">
        <a href="#about" class="nav-link">{{ $t('nav.about') }}</a>
        <a href="#coaching" class="nav-link">{{ $t('nav.coaching') }}</a>
        <a href="#programme" class="nav-link">{{ $t('nav.programs') }}</a>
        <a href="#kontakt" class="nav-link">{{ $t('nav.contact') }}</a>
      </nav>

      <!-- CTAs + Mobile Toggle -->
      <div class="flex items-center gap-2 sm:gap-3">
        <!-- Darkmode Toggle -->
        <button
          class="btn btn-ghost h-10 w-10 p-0" :aria-pressed="isDark"
          @click="toggleTheme" :aria-label="$t('aria.toggle_dark')"
          :title="$t('aria.toggle_dark')"
        >
          <!-- Moon when light -->
          <svg v-if="!isDark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5">
            <path fill="currentColor" d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 1 0 9.79 9.79Z"/>
          </svg>
          <!-- Sun when dark -->
          <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5">
            <path fill="currentColor" d="M12 4a1 1 0 0 1 1-1h0a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0V4Zm0 15a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0v-1a1 1 0 0 1 1-1Zm8-7a1 1 0 0 1 1 1h1a1 1 0 1 1 0 2h-1a1 1 0 1 1-2 0 1 1 0 0 1 1-1Zm-17 1a1 1 0 0 1-1 1H1a1 1 0 1 1 0-2h1a1 1 0 0 1 1 1Zm12.95 5.536a1 1 0 0 1 1.415 0l.707.707a1 1 0 0 1-1.415 1.414l-.707-.707a1 1 0 0 1 0-1.414ZM5.636 6.343a1 1 0 0 1 0-1.415l.707-.707A1 1 0 0 1 7.758 5.636l-.707.707a1 1 0 0 1-1.415 0Zm12.021-1.415a1 1 0 0 1 0 1.415l-.707.707a1 1 0 0 1-1.415-1.415l.707-.707a1 1 0 0 1 1.415 0ZM6.343 18.364a1 1 0 0 1 1.415 0l.707.707a1 1 0 0 1-1.415 1.415l-.707-.707a1 1 0 0 1 0-1.415ZM12 8a4 4 0 1 1 0 8 4 4 0 0 1 0-8Z"/>
          </svg>
        </button>

        <!-- Language Switcher -->
        <select v-model="currentLocale" class="lang-switch hidden sm:block" :aria-label="$t('nav.language')" :title="$t('nav.language')">
          <option v-for="code in supportedLocales" :key="code" :value="code">{{ code.toUpperCase() }}</option>
        </select>
        <a href="#kontakt" class="btn btn-secondary hidden sm:inline-flex">{{ $t('cta.discovery_call') }}</a>
        <a href="#programme" class="btn btn-primary hidden md:inline-flex">{{ $t('cta.get_started') }}</a>
        <a href="/admin" class="btn btn-secondary md:inline-flex">{{ $t('cta.admin_area') }}</a>
        <button @click="mobileOpen = !mobileOpen" class="md:hidden btn btn-ghost" :aria-label="$t('aria.open_menu')">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
      </div>
    </div>

    <!-- Mobile Drawer -->
    <div v-show="mobileOpen" class="md:hidden border-t divider bg-white/80 dark:bg-stone-900/80 backdrop-blur">
      <div class="container-prose py-3 grid gap-3">
        <a @click="mobileOpen=false" href="#about" class="nav-link">{{ $t('nav.about') }}</a>
        <a @click="mobileOpen=false" href="#coaching" class="nav-link">{{ $t('nav.coaching') }}</a>
        <a @click="mobileOpen=false" href="#programme" class="nav-link">{{ $t('nav.programs') }}</a>
        <a @click="mobileOpen=false" href="#kontakt" class="nav-link">{{ $t('nav.contact') }}</a>
        <div class="flex gap-2 pt-1">
          <a href="#kontakt" class="btn btn-secondary w-full">{{ $t('cta.discovery_call') }}</a>
          <a href="#programme" class="btn btn-primary w-full">{{ $t('cta.start') }}</a>
        </div>
        <!-- Mobile Language Switcher -->
        <div class="mt-2">
          <label class="sr-only" for="mobile-locale">{{ $t('nav.language') || 'Language' }}</label>
          <select id="mobile-locale" v-model="currentLocale" class="select select-ghost w-full" :aria-label="$t('nav.language') || 'Language'">
            <option v-for="code in supportedLocales" :key="code" :value="code">{{ code.toUpperCase() }}</option>
          </select>
        </div>
        <!-- Dark toggle duplicated in mobile for convenience -->
        <button
          class="btn btn-ghost mt-2"
          :aria-pressed="isDark" @click="toggleTheme" :aria-label="$t('aria.toggle_dark')"
        >
          <span class="mr-2">{{ $t('mobile.theme') }}</span>
          <svg v-if="!isDark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5"><path fill="currentColor" d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 1 0 9.79 9.79Z"/></svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5"><path fill="currentColor" d="M12 4a1 1 0 0 1 1-1h0a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0V4Zm0 15a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0v-1a1 1 0 0 1 1-1Zm8-7a1 1 0 0 1 1 1h1a1 1 0 1 1 0 2h-1a1 1 0 1 1-2 0 1 1 0 0 1 1-1Zm-17 1a1 1 0 0 1-1 1H1a1 1 0 1 1 0-2h1a1 1 0 0 1 1 1Zm12.95 5.536a1 1 0 0 1 1.415 0l.707.707a1 1 0 0 1-1.415 1.414l-.707-.707a1 1 0 0 1 0-1.414ZM5.636 6.343a1 1 0 0 1 0-1.415l.707-.707A1 1 0 0 1 7.758 5.636l-.707.707a1 1 0 0 1-1.415 0Zm12.021-1.415a1 1 0 0 1 0 1.415l-.707.707a1 1 0 0 1-1.415-1.415l.707-.707a1 1 0 0 1 1.415 0ZM6.343 18.364a1 1 0 0 1 1.415 0l.707.707a1 1 0 0 1-1.415 1.415l-.707-.707a1 1 0 0 1 0-1.415Z"/></svg>
        </button>
      </div>
    </div>
  </header>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-inner">
      <div class="grid lg:grid-cols-12 gap-10 items-center">
        <div class="lg:col-span-6 order-1 lg:order-1">
            <a href="#">
                <!-- Light: sichtbar -->
                <img
                    class="block dark:hidden"
                    :src="require('/assets/images/simple_logo.png')"
                    alt="Logo"
                    width="400"
                />
                <!-- Dark: sichtbar -->
                <img
                    class="hidden dark:block"
                    :src="require('/assets/images/simple_logo_white.png')"
                    alt="Logo (weiß)"
                    width="400"
                />
            </a>
          <p class="hero-subtitle">{{ $t('hero.subtitle') }}</p>
          <div class="hero-cta flex flex-wrap gap-3">
            <a href="#coaching" class="btn btn-primary">{{ $t('hero.primary') }}</a>
            <a href="#about" class="btn btn-secondary">{{ $t('hero.secondary') }}</a>
          </div>
          <div class="mt-6 flex flex-wrap items-center gap-2 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
            <span class="badge">{{ $t('badges.performance') }}</span>
            <span class="badge">{{ $t('badges.mindset') }}</span>
            <span class="badge">{{ $t('badges.aesthetics') }}</span>
            <span class="badge">{{ $t('badges.routines') }}</span>
          </div>
        </div>
        <div class="lg:col-span-6 order-2 lg:order-2">
          <div class="card elevate overflow-hidden">
            <img class="w-full aspect-[16/10] object-cover rounded-xl" src="https://images.unsplash.com/photo-1614252368727-99517bc90d7b?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=3270" :alt="$t('hero.image_alt')"/>
            <div class="mt-4">
              <h3 class="h3 text-slate-900 dark:text-white">{{ $t('hero.card_title') }}</h3>
              <p class="mt-2">{{ $t('hero.card_text') }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

    <!-- PROGRAMME / PAKETE -->
    <section id="programme" class="section section-muted">
        <div class="container-prose">
            <div class="text-center max-w-2xl mx-auto">
                <h2 class="h2 text-slate-900 dark:text-white">{{ $t('programs.title') }}</h2>
                <p class="mt-2">{{ $t('programs.subtitle') }}</p>
            </div>
            <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <!-- Card: Marketing Video -->
                <div class="card card-hover">
                    <router-link :to="{ name: 'video_marketing' }" class="block group" :aria-label="$t('programs.watch_marketing')">
                        <img class="w-full aspect-[16/9] object-cover rounded-xl group-hover:opacity-95 transition" src="https://images.unsplash.com/photo-1713947503588-8ff8196dc4a3?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=3264" :alt="$t('programs.images.starter_alt')"/>
                        <h3 class="h3 mt-4 text-slate-900 dark:text-white">{{ $t('videos.marketing.title') }}</h3>
                        <p class="mt-2">{{ $t('programs.starter_text') }}</p>
                    </router-link>
                    <router-link :to="{ name: 'video_marketing' }" class="btn btn-primary mt-6 w-full">{{ $t('programs.watch_marketing') }}</router-link>
                </div>
                <!-- Card: Marketing Explain Video -->
                <div class="card card-hover elevate">
                    <router-link :to="{ name: 'video_marketing_explain' }" class="block group" :aria-label="$t('programs.watch_explain')">
                        <img class="w-full aspect-[16/9] object-cover rounded-xl group-hover:opacity-95 transition" src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?q=80&w=1600&auto=format&fit=crop" :alt="$t('programs.images.momentum_alt')"/>
                        <div class="badge badge-success mt-4">{{ $t('programs.momentum_badge') }}</div>
                        <h3 class="h3 mt-2 text-slate-900 dark:text-white">{{ $t('videos.explain.title') }}</h3>
                        <p class="mt-2">{{ $t('programs.momentum_text') }}</p>
                    </router-link>
                    <router-link :to="{ name: 'video_marketing_explain' }" class="btn btn-primary mt-6 w-full">{{ $t('programs.watch_explain') }}</router-link>
                </div>
                <!-- Card: Product Video -->
                <div class="card card-hover">
                    <router-link :to="{ name: 'video_product' }" class="block group" :aria-label="$t('programs.watch_product')">
                        <img class="w-full aspect-[16/9] object-cover rounded-xl group-hover:opacity-95 transition" src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=1600&auto=format&fit=crop" :alt="$t('programs.images.leadership_alt')"/>
                        <h3 class="h3 mt-4 text-slate-900 dark:text-white">{{ $t('videos.product.title') }}</h3>
                        <p class="mt-2">{{ $t('programs.leadership_text') }}</p>
                    </router-link>
                    <router-link :to="{ name: 'video_product' }" class="btn btn-primary mt-6 w-full">{{ $t('programs.watch_product') }}</router-link>
                </div>
            </div>
        </div>
    </section>
  <!-- ÜBER MICH -->
  <section id="about" class="section section-muted">
    <div class="container-prose grid lg:grid-cols-12 gap-8 items-center">
      <div class="lg:col-span-6 order-2 lg:order-1">
        <h2 class="h2 text-slate-900 dark:text-white">{{ $t('about.title') }}</h2>
        <p class="mt-3">{{ $t('about.text') }}</p>
        <dl class="mt-6 grid sm:grid-cols-2 gap-4 text-sm">
          <div class="card"><dt class="font-semibold">{{ $t('about.items.routine_title') }}</dt><dd class="mt-1">{{ $t('about.items.routine_text') }}</dd></div>
          <div class="card"><dt class="font-semibold">{{ $t('about.items.business_title') }}</dt><dd class="mt-1">{{ $t('about.items.business_text') }}</dd></div>
          <div class="card"><dt class="font-semibold">{{ $t('about.items.body_title') }}</dt><dd class="mt-1">{{ $t('about.items.body_text') }}</dd></div>
          <div class="card"><dt class="font-semibold">{{ $t('about.items.aesthetic_title') }}</dt><dd class="mt-1">{{ $t('about.items.aesthetic_text') }}</dd></div>
        </dl>
      </div>
      <div class="lg:col-span-6 order-1 lg:order-2">
        <img class="w-full aspect-[4/3] object-cover rounded-2xl elevate" src="https://images.unsplash.com/photo-1520975916090-3105956dac38?q=80&w=1600&auto=format&fit=crop" :alt="$t('about.image_alt')"/>
      </div>
    </div>
  </section>

  <!-- COACHING SÄULEN -->
  <section id="coaching" class="section">
    <div class="container-prose">
      <div class="max-w-2xl">
        <h2 class="h2 text-slate-900 dark:text-white">{{ $t('pillars.title') }}</h2>
        <p class="mt-2">{{ $t('pillars.subtitle') }}</p>
      </div>
      <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <article class="card hover-lift">
          <div class="badge mb-3">{{ $t('badges.clarity') }}</div>
          <h3 class="h3 text-slate-900 dark:text-white">{{ $t('pillars.cards.clarity_title') }}</h3>
          <p class="mt-2">{{ $t('pillars.cards.clarity_text') }}</p>
        </article>
        <article class="card hover-lift">
          <div class="badge mb-3">{{ $t('badges.energy') }}</div>
          <h3 class="h3 text-slate-900 dark:text-white">{{ $t('pillars.cards.energy_title') }}</h3>
          <p class="mt-2">{{ $t('pillars.cards.energy_text') }}</p>
        </article>
        <article class="card hover-lift">
          <div class="badge mb-3">{{ $t('badges.structure') }}</div>
          <h3 class="h3 text-slate-900 dark:text-white">{{ $t('pillars.cards.structure_title') }}</h3>
          <p class="mt-2">{{ $t('pillars.cards.structure_text') }}</p>
        </article>
        <article class="card hover-lift">
          <div class="badge mb-3">{{ $t('badges.aesthetics') }}</div>
          <h3 class="h3 text-slate-900 dark:text-white">{{ $t('pillars.cards.aesthetics_title') }}</h3>
          <p class="mt-2">{{ $t('pillars.cards.aesthetics_text') }}</p>
        </article>
      </div>
    </div>
  </section>

  <!-- EINBLICKE / FEED -->
  <section class="section">
    <div class="container-prose">
      <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
          <h2 class="h2 text-slate-900 dark:text-white">{{ $t('feed.title') }}</h2>
          <p class="mt-2">{{ $t('feed.subtitle') }}</p>
        </div>
        <a href="#kontakt" class="btn btn-ghost self-start sm:self-auto">{{ $t('feed.cta') }}</a>
      </div>
      <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-3">
        <img class="rounded-xl object-cover aspect-[1/1] w-full" src="https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=3270" :alt="$t('feed.images.gym_alt')"/>
        <img class="rounded-xl object-cover aspect-[1/1] w-full" src="https://images.unsplash.com/photo-1556761175-4b46a572b786?ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&q=80&w=3348" :alt="$t('feed.images.workspace_alt')"/>
        <img class="rounded-xl object-cover aspect-[1/1] w-full" src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=1600&auto=format&fit=crop" :alt="$t('feed.images.coffee_alt')"/>
        <img class="rounded-xl object-cover aspect-[1/1] w-full" src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=1600&auto=format&fit=crop" :alt="$t('feed.images.team_alt')"/>
      </div>
    </div>
  </section>

  <!-- TESTIMONIALS -->
  <section class="section section-muted">
    <div class="container-prose">
      <div class="grid gap-6 md:grid-cols-2">
        <figure class="card">
          <blockquote class="text-lg text-slate-700 dark:text-slate-300">{{ $t('testimonials.quote1') }}</blockquote>
          <figcaption class="mt-4 text-sm text-slate-500">{{ $t('testimonials.author1') }}</figcaption>
        </figure>
        <figure class="card">
          <blockquote class="text-lg text-slate-700 dark:text-slate-300">{{ $t('testimonials.quote2') }}</blockquote>
          <figcaption class="mt-4 text-sm text-slate-500">{{ $t('testimonials.author2') }}</figcaption>
        </figure>
      </div>
    </div>
  </section>

  <!-- KONTAKT -->
  <section id="kontakt" class="section">
    <div class="container-prose max-w-3xl">
      <div class="card">
        <h2 class="h2 text-slate-900 dark:text-white">{{ $t('contact.title') }}</h2>
        <p class="mt-2">{{ $t('contact.subtitle') }}</p>
        <div v-if="submitSuccess" class="mt-4 p-3 rounded-lg bg-green-50 text-green-800 border border-green-200" role="status" aria-live="polite">
          {{ $t('contact.form.success') }}
        </div>
        <div v-if="submitError" class="mt-4 p-3 rounded-lg bg-red-50 text-red-800 border border-red-200" role="alert" aria-live="assertive">
          {{ $t('contact.form.error_generic') }}
        </div>
        <form @submit.prevent="submitContact" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4" novalidate>
          <!-- Honeypot -->
          <input class="hidden" type="text" v-model="honeypot" autocomplete="off" tabindex="-1" aria-hidden="true" />

          <div>
            <label class="text-sm text-slate-600 dark:text-slate-400">{{ $t('contact.form.name_label') }}</label>
            <input class="input mt-1" :placeholder="$t('contact.form.name_placeholder')" v-model.trim="form.name" :disabled="submitting" required />
            <p v-if="errors.name" class="mt-1 text-xs text-red-600">{{ errors.name }}</p>
          </div>
          <div>
            <label class="text-sm text-slate-600 dark:text-slate-400">{{ $t('contact.form.email_label') }}</label>
            <input class="input mt-1" type="email" :placeholder="$t('contact.form.email_placeholder')" v-model.trim="form.email" :disabled="submitting" required />
            <p v-if="errors.email" class="mt-1 text-xs text-red-600">{{ errors.email }}</p>
          </div>
          <div class="md:col-span-2">
            <label class="text-sm text-slate-600 dark:text-slate-400">{{ $t('contact.form.goal_label') }}</label>
            <textarea class="textarea mt-1" :placeholder="$t('contact.form.goal_placeholder')" v-model.trim="form.goal" :disabled="submitting" rows="5" required></textarea>
            <p v-if="errors.goal" class="mt-1 text-xs text-red-600">{{ errors.goal }}</p>
          </div>
          <div class="md:col-span-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <span class="text-xs text-slate-500">{{ $t('contact.form.privacy_note') }}</span>
            <button type="submit" class="btn btn-primary" :disabled="submitting">
              <span v-if="!submitting">{{ $t('contact.form.submit') }}</span>
              <span v-else>{{ $t('contact.form.submitting') }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="site-footer">
    <div class="site-footer-inner container-prose">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
        <p>{{ $t('footer.copyright', { year: new Date().getFullYear() }) }}</p>
        <div class="flex items-center gap-4">
          <a href="#imprint" @click.prevent="openImprint" class="nav-link">{{ $t('footer.imprint') }}</a>
          <a href="#privacy" @click.prevent="openPrivacy" class="nav-link">{{ $t('footer.privacy') }}</a>
          <a href="#kontakt" class="nav-link">{{ $t('footer.contact') }}</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- LEGAL MODALS -->
  <Modal v-model="showImprint" :title="$t('legal.imprint.title')" size="lg" @close="onModalClose('imprint')">
    <div class="prose dark:prose-invert max-w-none" v-html="$t('legal.imprint.content')"></div>
  </Modal>
  <Modal v-model="showPrivacy" :title="$t('legal.privacy.title')" size="lg" @close="onModalClose('privacy')">
    <div class="prose dark:prose-invert max-w-none" v-html="$t('legal.privacy.content')"></div>
  </Modal>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import axios from 'axios'
import Modal from '@/components/Modal.vue'

const mobileOpen = ref(false)
const isDark = ref(false)

// Legal modals state
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
  // Clear hash if it matches the modal we closed
  try {
    const hash = location.hash.replace('#', '')
    if (hash === which) history.replaceState(null, '', ' ')
  } catch (e) {}
}

// i18n locale handling and language switcher
const { t, locale } = useI18n()
const supportedLocales = ['en', 'de', 'fr', 'es', 'zh']
const currentLocale = ref(locale.value)

watch(currentLocale, (val) => {
  if (!val) return
  locale.value = val
  try { localStorage.setItem('locale', val) } catch (e) {}
  try { document.documentElement.setAttribute('lang', val) } catch (e) {}
})

import logoUrl from '/assets/images/dr_simple_black.svg'

function applyTheme(dark) {
  isDark.value = !!dark
  const root = document.documentElement
  root.classList.toggle('dark', isDark.value)
  try { localStorage.setItem('theme', isDark.value ? 'dark' : 'light') } catch (e) {}
}

function toggleTheme() {
  applyTheme(!isDark.value)
}

onMounted(() => {
  // 1) load stored preference
  let stored
  try { stored = localStorage.getItem('theme') } catch (e) {}
  if (stored === 'dark' || stored === 'light') {
    applyTheme(stored === 'dark')
  } else {
    // 2) default to light mode (requested)
    applyTheme(false)
  }
  // 3) sync when system preference changes
  try {
    const mq = window.matchMedia('(prefers-color-scheme: dark)')
    const handler = (e) => {
      const storedNow = localStorage.getItem('theme')
      if (!storedNow) applyTheme(e.matches)
    }
    if (mq.addEventListener) mq.addEventListener('change', handler)
    else mq.addListener && mq.addListener(handler)
  } catch (e) {}
  // Close mobile menu on route/hash change and handle legal deep links
  window.addEventListener('hashchange', () => {
    mobileOpen.value = false
    const h = (location.hash || '').replace('#', '')
    if (h === 'imprint') showImprint.value = true
    if (h === 'privacy') showPrivacy.value = true
  })
  // Open matching modal when arriving with #imprint or #privacy
  try {
    const h = (location.hash || '').replace('#', '')
    if (h === 'imprint') showImprint.value = true
    if (h === 'privacy') showPrivacy.value = true
  } catch (e) {}
})

// --- Contact form state & submission ---
const submitting = ref(false)
const submitSuccess = ref(false)
const submitError = ref(false)
const errors = ref({})
const honeypot = ref('')
const form = ref({ name: '', email: '', goal: '' })

async function submitContact () {
  submitSuccess.value = false
  submitError.value = false
  errors.value = {}

  // simple client validation
  if (!form.value.name) errors.value.name = t('contact.form.error_name_required')
  if (!form.value.email) errors.value.email = t('contact.form.error_email_required')
  else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.email)) errors.value.email = t('contact.form.error_email_invalid')
  if (!form.value.goal) errors.value.goal = t('contact.form.error_goal_required')
  if (Object.keys(errors.value).length) return

  submitting.value = true
  try {
    const { locale } = useI18n()
    await axios.post('/api/contact', {
      name: form.value.name,
      email: form.value.email,
      goal: form.value.goal,
      locale: locale.value,
      website: honeypot.value
    })
    submitSuccess.value = true
    form.value = { name: '', email: '', goal: '' }
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
/* Alle Styles kommen aus app.css/Tailwind. Nur Responsiveness-Sugar: */
.aspect-\[16\/10\] { aspect-ratio: 16/10; }
.aspect-\[4\/3\] { aspect-ratio: 4/3; }
.aspect-\[1\/1\] { aspect-ratio: 1/1; }

/* Desktop Language Switcher an Theme-Toggle angepasst: weißer Hintergrund, keine Pfeil-UI, rund */
.lang-switch {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background-image: none;
  width: 40px;
  height: 40px;
  background: #ffffff;
  color: #0c0a09; /* near stone-950 */
  border: 1px solid #e5e7eb; /* slate-200 */
  border-radius: 0.75rem; /* fully rounded */
  padding: 0;
  text-align: center;
  line-height: 40px; /* vertically center the 2-letter code */
  font-weight: 600;
  font-size: 12px;
  letter-spacing: 0.02em;
  cursor: pointer;
  box-shadow: 0 1px 1px rgba(0,0,0,0.04);
}
.lang-switch:focus {
  outline: 2px solid #94a3b8; /* slate-400 */
  outline-offset: 2px;
}
/* hide old IE arrow */
.lang-switch::-ms-expand { display: none; }
/* options keep dark text so contrast on white */
.lang-switch option { color: #0c0a09; }
/* Keep it white in dark mode as requested */
@media (prefers-color-scheme: dark) {
  .lang-switch {
    background: #ffffff;
    color: #0c0a09;
    border-color: #e5e7eb;
  }
}
/* ensure white in app dark mode too */
:global(.dark) .lang-switch,
.dark .lang-switch {
  background: #ffffff;
  color: #0c0a09;
  border-color: #e5e7eb;
}
</style>
