import axios from "axios";
import getCookie from "../helper/cookie";

const token = getCookie('ACCESS_TOKEN')

export const axiosClient = axios.create({
    baseURL: 'http://127.0.0.1:8000/api'
});

axiosClient.interceptors.request.use(
    (config) => {
      if (token) {
        if (config.url !== "/login" || config.url !== "/register") {
            config.headers.Authorization = `Bearer ${token}`;
        }
      }
      return config;
    },
    (error) => {
      return Promise.reject(error);
    }
  );