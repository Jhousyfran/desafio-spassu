
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8001/api',
  timeout: 8000
});

api.interceptors.response.use(
  response => response,
  error => {
    if (error.response && error.response.status === 422) {
      error.validationErrors = error.response.data.errors;
    }
    return Promise.reject(error);
  }
);

export default api;
