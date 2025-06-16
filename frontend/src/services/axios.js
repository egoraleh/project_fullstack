import axios from 'axios'

const api = axios.create({
    baseURL: '/api'
})

api.interceptors.response.use(
    api.interceptors.response.use(
        response => response,
        error => Promise.reject(error)
    )
)

export default api