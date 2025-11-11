import { createApp, h } from 'vue';
import PublicWatchApp from './PublicWatchApp.vue';

const mount = () => {
  const el = document.getElementById('public-watch-root');
  if (!el) return;
  let props = {};
  try {
    props = JSON.parse(el.dataset.props || '{}');
  } catch (e) {
    console.error('Failed to parse props for public watch view.', e);
  }
  createApp({ render: () => h(PublicWatchApp, props) }).mount(el);
};

mount();
