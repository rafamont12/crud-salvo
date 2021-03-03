import Vue from 'vue';
import axios from 'axios';

let instance = axios.create();

instance.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response.status === 422) {
            let errors = Object.values(error.response.data.errors).map(item => item.join(''));
            errors.forEach(error => {
                Vue.notify({
                    title: 'Wrong fields!',
                    text: error,
                    group: 'notifications',
                    duration: 3500,
                    type: 'error',
                })
            })
        }
        else {
            Vue.notify({
                text: `${error.response.data.message}`,
                title: 'Request error!',
                group: 'notifications',
                duration: 3500,
                type: 'error',
            });
        }

        return Promise.reject(error);
    }
);

export const http = instance;