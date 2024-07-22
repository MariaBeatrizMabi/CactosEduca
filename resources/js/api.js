import axios from "axios";

export const api = axios.create({})

api.interceptors.request.use(
    (config) => ({
        ...config,
        headers: {
            ...config.headers,
            'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
    }),
    (error) => Promise.reject(error)
)
