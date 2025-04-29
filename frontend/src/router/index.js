
import { createRouter, createWebHistory } from 'vue-router';

import Authors from '@/views/Authors.vue';
import Topics from '@/views/Topics.vue';
import Books from '@/views/Books.vue';
import Dashboard from '@/views/Dashboard.vue';

const routes = [
  { path: '/', component: Dashboard },
  { path: '/authors', component: Authors },
  { path: '/topics', component: Topics },
  { path: '/books', component: Books }
];

export default createRouter({
  history: createWebHistory(),
  routes
});
