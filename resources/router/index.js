import { createRouter, createWebHistory } from 'vue-router';
import Login from '../../resources/js/pages/login.vue';
import Dashboard from '../../resources/js/pages/dashboard.vue';
import ContactUs from '../../resources/js/pages/contactus.vue';
import Management from '../../resources/js/pages/management.vue';
import Filters from '../../resources/js/pages/Filters.vue';
import SchoolDetails from '../../resources/js/pages/schoolDetails.vue';
import StudentForm from '../../resources/js/pages/StudentForm.vue';
import ClassDetails from '../../resources/js/pages/ClassDetails.vue';
import StudentHistory from '../../resources/js/pages/StudentHistory.vue';

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
            meta: { requiresAuth: true }
        },
        {
            path: '/Filters',
            component: Filters,
            meta: { requiresAuth: true }
        },
        {
            path: '/contactus',
            component: ContactUs,
            meta: { requiresAuth: true }
        },
        {
            path: '/management',
            component: Management,
            meta: { requiresAuth: true }
        },
        {
            path: '/schoolDetails/:city',
            name: 'SchoolDetailsByCity',
            component: Filters,
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: '/schoolDetails/:city/:schoolName/:schoolId', // Atualiza a rota para incluir schoolId
            name: 'SchoolDetailsByCityAndSchool',
            component: SchoolDetails,
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: '/student/:student',
            name: 'Student',
            component: StudentForm,
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: '/class/:class',
            name: 'Class',
            component: ClassDetails,
            props: true,
            meta: { requiresAuth: true }
        },
        {
            path: '/student/:student/classes/:class',
            name: 'StudentHistory',
            component: StudentHistory,
            props: true,
            meta: { requiresAuth: true }
        }
    ]
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
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
    return true;
}

export default router;
