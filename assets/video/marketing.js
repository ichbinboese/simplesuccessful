import { createApp } from 'vue'
import { createI18n } from 'vue-i18n'
import MarketingVideo from '@/components/MarketingVideo.vue'

import en from '@/i18n/en.json'
import de from '@/i18n/de.json'
import fr from '@/i18n/fr.json'
import es from '@/i18n/es.json'
import zh from '@/i18n/zh.json'

const messages = { en, de, fr, es, zh }
const supported = Object.keys(messages)

let storedLocale = null
try { storedLocale = localStorage.getItem('locale') } catch (e) {}
const backendLocale = (typeof window !== 'undefined' && window.APP_LOCALE) || null
const browserLocale = (navigator.language ? navigator.language.split('-')[0] : null)
const initialLocale = storedLocale || backendLocale || browserLocale || 'en'
const locale = supported.includes(initialLocale) ? initialLocale : 'en'

const i18n = createI18n({ legacy: false, locale, fallbackLocale: 'en', messages })
try { document.documentElement.setAttribute('lang', locale) } catch (e) {}

const el = document.querySelector('#marketing-video-app')
if (el) {
  const app = createApp(MarketingVideo)
  app.use(i18n)
  app.mount(el)
}
