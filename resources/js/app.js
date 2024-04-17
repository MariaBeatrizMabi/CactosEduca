import { createApp } from 'vue/dist/vue.esm-bundler.js';
import App from '../App.vue'; 
import { createRouter, createWebHistory } from 'vue-router';
import router from '../router/index.js';

const app = createApp(App);

app.use(router);

app.mount('#app');