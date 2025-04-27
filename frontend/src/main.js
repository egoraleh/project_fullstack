import { createApp } from 'vue'
import App from './App.vue'
import { createRouter, createWebHistory } from 'vue-router'
import About from './pages/main/About.vue'
import Register from './pages/authentication/Register.vue'
import NotFound from './pages/exceptions/NotFound.vue'
import Login from './pages/authentication/Login.vue'
import Profile from "./pages/profile/Profile.vue";
import EditProfile from "./pages/profile/EditProfile.vue";
import Home from "./pages/main/Home.vue";
import ServerError from "./pages/exceptions/ServerError.vue";
import Forbidden from "./pages/exceptions/Forbidden.vue";
import Unauthorized from "./pages/exceptions/Unauthorized.vue";
import BadGateway from "./pages/exceptions/BadGateway.vue";

const routes = [
    { path: '/', component: Home },
    { path: '/about', component: About },
    { path: '/register', component: Register },
    { path: '/login', component: Login },
    { path: '/edit-profile', component: EditProfile},
    { path: '/profile', component: Profile },
    { path: '/:pathMatch(.*)*', component: NotFound },
    { path: '/server-error', component: ServerError },
    { path: '/forbidden', component: Forbidden },
    { path: '/unauthorized', component: Unauthorized },
    { path: '/bad-gateway', component: BadGateway }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

const app = createApp(App)

app.use(router)
app.mount('#app')