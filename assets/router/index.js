import { createRouter, createWebHistory } from 'vue-router'

// Pages
import OnepagerApp from '@/pages/OnepagerApp.vue'
import MarketingVideoPage from '@/pages/MarketingVideoPage.vue'
import MarketingExplainVideoPage from '@/pages/MarketingExplainVideoPage.vue'
import ProductVideoPage from '@/pages/ProductVideoPage.vue'
import AdminApp from '@/admin/AdminApp.vue'

const routes = [
  { path: '/', name: 'home', component: OnepagerApp },
  { path: '/video/marketing', name: 'video_marketing', component: MarketingVideoPage },
  { path: '/video/marketing-explain', name: 'video_marketing_explain', component: MarketingExplainVideoPage },
  { path: '/video/product', name: 'video_product', component: ProductVideoPage },
  { path: '/admin/app', name: 'admin_app', component: AdminApp },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) return savedPosition
    return { top: 0 }
  }
})

export default router
