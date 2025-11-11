import { createApp } from 'vue';
import AdminApp from './AdminApp.vue';

const target = document.getElementById('admin-app');

if (target) {
  createApp(AdminApp).mount(target);
}
