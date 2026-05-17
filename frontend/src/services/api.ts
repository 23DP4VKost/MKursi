import axios from 'axios';

export const API_URL = 'https://mkursi.up.railway.app/api';

export const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
  },
  withCredentials: true,
});
