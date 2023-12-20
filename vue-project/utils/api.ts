import axios from 'axios';

const baseURL = import.meta.env.VITE_API_BASE_URL;

const axiosInstance = axios.create({
  baseURL,
  headers : {
    'cache-control': `private, max-age=60, must-revalidate`,
  },
});

/*
axiosInstance.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
}, (error) => {
  return Promise.reject(error);
}); 

axiosInstance.interceptors.response.use((response) => {
  return response;
}, (error) => {
  return Promise.reject(error);
});

*/

/* 
utilizar discriminate units para tipar esses parametros

https://www.youtube.com/watch?v=xsfdypZCLQ8
*/
type RequestConfig = {
  url: string
  method?: string
  params?: any
  data?: any
}

const api = (config: RequestConfig) => {
  
  return axiosInstance(
    {
      method: config.method,
      url: config.url,
      params: config.params,
      data: config.data,
      validateStatus(status) {
        return status < 500;
      },
    })
    .then((response) => {
      return response;
    })
    .catch((error) => {
      if (error.response) {

        const { data, status, headers } = error.response;
        console.log('ErrorResponse', {data, status, headers});
      } else if (error.request) {

        const {code, message, name} = error;
        console.log('ErrorRequest', {code, message, name});
      } else {

        const {code, message, name} = error;
        console.log('SettingUpRequest', {code, message, name});
      }
      console.log('ErrorConfig', error.config);
      return error;
    })
} 

export default api;