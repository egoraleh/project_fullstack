import { defineStore } from 'pinia'
import api from '../services/axios'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null
    }),

    getters: {
        username(state) {
            return state.user ? state.user.email : 'гость'
        }
    },

    actions: {
        async fetchCurrentUser() {
            try {
                const response = await api.get('/me')
                this.user = response.data
            } catch (error) {
                if (error.response?.status === 401) {
                    this.user = null
                }
            }
        },

        async logout() {
            await api.post('/logout')
            this.user = null
        }
    }
})
