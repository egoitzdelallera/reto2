import axios from "axios";

const axiosInstance = axios.create({
    baseURL: "http://localhost:8000/api",
});

axiosInstance.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem("jwt_token");
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

axiosInstance.interceptors.response.use(
    (response) => response,
    (error) => {
        // En caso de que sea erroneo el usuario, o que no haya, se manda directamente al login
        if(error.response && error.response.status === 401) {

            localStorage.removeItem("jwt_token");
            window.location = "/";
        }
        return Promise.reject(error);
    }
);

export default axiosInstance;