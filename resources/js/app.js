import { createApp } from 'vue/dist/vue.esm-bundler.js';
import App from '../App.vue'; // Importe o componente principal

// Importe o Vue Router
import { createRouter, createWebHistory } from 'vue-router';
import router from '../router/index.js'; // Importe suas rotas do arquivo index.js em sua pasta routes

// Crie uma instância do aplicativo Vue
const app = createApp(App);

// Use o Vue Router no aplicativo Vue
app.use(router);

// Monte o aplicativo Vue no elemento HTML com o id 'app'
app.mount('#app');