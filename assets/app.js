import { createApp } from 'vue';
import { createI18n } from 'vue-i18n';
import App from './App.vue';
import router from './router';
import './styles/app.css';

// Load i18n message bundles (static import for bundlers)
import en from './i18n/en.json';
import de from './i18n/de.json';
import fr from './i18n/fr.json';
import es from './i18n/es.json';
import zh from './i18n/zh.json';

const messages = { en, de, fr, es, zh };
const supported = Object.keys(messages);

// Locale priority: localStorage -> backend (Twig) -> browser -> en
let storedLocale = null;
try { storedLocale = localStorage.getItem('locale') } catch (e) {}
const backendLocale = (typeof window !== 'undefined' && window.APP_LOCALE) || null;
const browserLocale = (navigator.language ? navigator.language.split('-')[0] : null);
const initialLocale = storedLocale || backendLocale || browserLocale || 'en';
const locale = supported.includes(initialLocale) ? initialLocale : 'en';

const i18n = createI18n({
  legacy: false,
  locale,
  fallbackLocale: 'en',
  messages,
});

// Reflect locale in <html lang="..">
try { document.documentElement.setAttribute('lang', locale) } catch (e) {}

const target = document.querySelector('#onepager-app');
if (target) {
  const app = createApp(App);
  app.use(i18n);
  app.use(router);
  app.mount(target);
}
