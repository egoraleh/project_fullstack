import { createApp } from 'vue'
import App from './App.vue'
import { createRouter, createWebHistory } from 'vue-router'
import Home from './pages/Home.vue'
import Register from './pages/Register.vue'
import NotFound from './pages/NotFound.vue'

const routes = [
    { path: '/', component: Home },
    { path: '/register', component: Register },
    { path: '/:pathMatch(.*)*', component: NotFound }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

const app = createApp(App)

app.use(router)
app.mount('#app')