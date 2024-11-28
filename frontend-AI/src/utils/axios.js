import Axios from 'axios';

const axios = Axios.create({
  baseURL: import.meta.env.VITE_APP_BACKEND_URL,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json',
  },
  
  withCredentials: true,
});


axios.interceptors.request.use((config) => {
  const token = document.cookie
    .split('; ')
    .find((row) => row.startsWith('XSRF-TOKEN='))  
    ?.split('=')[1];

  if (token) {
    config.headers['X-XSRF-TOKEN'] = decodeURIComponent(token);  
  }

  return config;
}, (error) => {
  return Promise.reject(error);
});


axios.interceptors.response.use(
  (response) => response,
  (error) => {
    console.error('Axios Error:', error.response || error.message); 
    return Promise.reject(error);
  }
);

export default axios;
