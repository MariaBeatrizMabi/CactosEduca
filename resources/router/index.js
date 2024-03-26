import { createRouter, createWebHistory } from 'vue-router';
import Login from '../../resources/js/pages/login.vue';
import Dashboard from '../../resources/js/pages/dashboard.vue';
import ContactUs from '../../resources/js/pages/contactus.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            component: Login
        },
        {
            path: '/dashboard',
            component: Dashboard,
            meta: { requiresAuth: true } // Esta rota requer autenticação
        },
        {
            path: '/contactus',
            component: ContactUs,
            meta: { requiresAuth: true } // Esta rota requer autenticação
        }
    ]
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // Verifique se o usuário está autenticado, caso contrário, redirecione para a página de login
        if (!isAuthenticated()) {
            next({ path: '/', query: { redirect: to.fullPath } });
        } else {
            next();
        }
    } else {
        next();
    }
});

function isAuthenticated() {
    // Lógica para verificar se o usuário está autenticado
    // Retorna true se autenticado, caso contrário, retorna false
    return true
}

export default router;