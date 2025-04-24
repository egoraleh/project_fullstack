import { createApp } from 'vue'
import App from './App.vue'
import { createRouter, createWebHistory } from 'vue-router'
import About from './pages/About.vue'
import Register from './pages/Register.vue'
import NotFound from './pages/NotFound.vue'
import Login from './pages/Login.vue'

const routes = [
    { path: '/about', component: About },
    { path: '/register', component: Register },
    { path: '/login', component: Login },
    { path: '/:pathMatch(.*)*', component: NotFound }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

const app = createApp(App)

app.use(router)
app.mount('#app')