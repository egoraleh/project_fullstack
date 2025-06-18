import { createApp } from 'vue'
import App from './App.vue'
import { createRouter, createWebHistory } from 'vue-router'
import { createPinia } from "pinia";
import {useAuthStore} from "./stores/authStore";
import About from './pages/main/About.vue'
import Register from './pages/authentication/Register.vue'
import NotFound from './pages/exceptions/NotFound.vue'
import Login from './pages/authentication/Login.vue'
import Profile from "./pages/profile/Profile.vue";
import EditProfile from "./pages/profile/EditProfile.vue";
import Ads from "./pages/ads/Ads.vue";
import ServerError from "./pages/exceptions/ServerError.vue";
import Forbidden from "./pages/exceptions/Forbidden.vue";
import Unauthorized from "./pages/exceptions/Unauthorized.vue";
import BadGateway from "./pages/exceptions/BadGateway.vue";
import AdDetails from "./pages/ads/AdDetails.vue";
import NewAd from "./pages/ads/NewAd.vue";
import MyAds from "./pages/ads/MyAds.vue";
import Favorites from "./pages/ads/Favorites.vue";
import EditAd from "./pages/ads/EditAd.vue";

const routes = [
    { path: '/', component: Ads },
    { path: '/ad/:id', component: AdDetails },
    { path: '/ad/:id/edit', component: EditAd },
    { path: '/about', component: About },
    { path: '/register', component: Register },
    { path: '/login', component: Login },
    { path: '/edit-profile', component: EditProfile},
    { path: '/profile', component: Profile },
    { path: '/ads', component: MyAds },
    { path: '/ads/favorites', component: Favorites },
    { path: '/ads/new', component: NewAd },
    { path: '/:pathMatch(.*)*', component: NotFound },
    { path: '/server-error', component: ServerError },
    { path: '/forbidden', component: Forbidden },
    { path: '/unauthorized', component: Unauthorized },
    { path: '/bad-gateway', component: BadGateway }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

const app = createApp(App);
const pinia = createPinia();

app.use(pinia);

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();

    if (authStore.user === null) {
        await authStore.fetchCurrentUser();
    }

    const protectedRoutes = ['/profile', '/edit-profile', '/ads/new'];

    if (protectedRoutes.includes(to.path) && !authStore.user) {
        return next('/login');
    }

    next();
});

app.use(router);
app.mount('#app');