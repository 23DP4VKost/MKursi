import axios from 'axios';

export const API_URL = import.meta.env.VITE_API_BASE_URL || (import.meta.env.DEV
  ? 'http://localhost:8000/api'
  : 'https://mkursi.up.railway.app/api');

export const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
  },
  withCredentials: true,
});
