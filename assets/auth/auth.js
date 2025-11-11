import { createApp, h } from 'vue';
import AuthLogin from '@/pages/AuthLogin.vue';
import AuthForgot from '@/pages/AuthForgot.vue';

// Theme Anti-FOUC: vor Rendern anwenden
(() => {
  try {
    const ls = localStorage.getItem('theme');
    const isDark = ls ? ls === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
    document.documentElement.classList.toggle('dark', isDark);
  } catch {}
})();

function mount(id, component) {
  const el = document.getElementById(id);
  if (!el) return;
  const props = JSON.parse(el.dataset.props || '{}');
  createApp({ render: () => h(component, props) }).mount(el);
}

mount('auth-login-root', AuthLogin);
mount('auth-forgot-root', AuthForgot);
