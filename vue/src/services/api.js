import axios from "axios";

const apiClient = axios.create({
    baseURL: "http://localhost:8000/api",
    headers: {
        'Content-Type' : 'application/json',
    },
});

// Agregar automaticamente el token si la solicitud esta disponibler
apiClient.interceptors.request.use((config) => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
})

export default {
    obtenerDatos() {
        return apiClient.get('/datos');
    },
};