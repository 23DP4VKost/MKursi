import axios from 'axios';

export const API_URL = 'https://mkursi.up.railway.app/api';

export const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
  },
  withCredentials: true,
});

api.interceptors.request.use(
  config => {
    const token = localStorage.getItem('mkursi.auth_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  error => Promise.reject(error)
);

api.interceptors.response.use(
  response => response,
  async error => {
    if (error.response?.status === 401 && !error.config?.headers?.Authorization && !error.config?.hasTriedToken) {
      try {
        error.config.hasTriedToken = true;
        const { data } = await api.get('/token');
        
        if (data.token) {
          localStorage.setItem('mkursi.auth_token', data.token);
          api.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
          error.config.headers.Authorization = `Bearer ${data.token}`;
          return api.request(error.config);
        }
      } catch (tokenError) {
      }
    }
    
    if (error.response?.status === 401) {
      // Clear auth on 401
      localStorage.removeItem('mkursi.authenticated')
      localStorage.removeItem('mkursi.auth_token')
      delete api.defaults.headers.common['Authorization']
    }
    
    return Promise.reject(error)
  }
);
