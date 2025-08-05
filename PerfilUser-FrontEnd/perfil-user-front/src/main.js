import { createApp } from 'vue';
import App from './App.vue';
import './assets/tailwind.css';
import './assets/css/login.css';

// Importar services centralizados
import { ApiService } from '@/services';

// Importar o Vue Router
import router from './router';

// Configurar interceptors globais
ApiService.setupInterceptors();

// Criar aplicação Vue
const app = createApp(App);

// Disponibilizar services globalmente (opcional)
app.config.globalProperties.$api = ApiService;

// Usar o router
app.use(router);

app.mount('#app');
