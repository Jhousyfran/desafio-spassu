
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'bootstrap';
import money from 'v-money3'

const app = createApp(App);
app.use(router);
app.use(money, {
    precision: 2,        // 2 casas decimais
    decimal: ',',        // separador decimal
    thousands: '.',      // separador milhar
    prefix: 'R$ ',       // opcional
    masked: false        // false = v-model Number; true = String formatada
});
app.mount('#app');
