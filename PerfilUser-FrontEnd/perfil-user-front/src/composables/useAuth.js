import { ref } from 'vue';
import { loginApi, logoutApi } from '@/services/auth';
import { API_CONFIG } from '@/config/api';

const user = ref(JSON.parse(localStorage.getItem(API_CONFIG.auth.userKey)) || {});
const token = ref(localStorage.getItem(API_CONFIG.auth.tokenKey) || null);

export function useAuth() {
  const loading = ref(false);
  const error = ref('');

  async function login({ email, password }) {
    loading.value = true;
    error.value = '';
    try {
      console.log('useAuth: Iniciando login...');
      const result = await loginApi(email, password);
      console.log('useAuth: Login bem-sucedido, salvando dados...');
      
      localStorage.setItem(API_CONFIG.auth.tokenKey, result.token);
      localStorage.setItem(API_CONFIG.auth.userKey, JSON.stringify(result.user));
      user.value = result.user || {};
      token.value = result.token;
      
      console.log('useAuth: Dados salvos com sucesso');
    } catch (e) {
      console.error('useAuth: Erro no login:', e);
      error.value = e.message || 'Erro ao fazer login';
    } finally {
      loading.value = false;
    }
  }

  async function logout() {
    await logoutApi();
    user.value = {};
    token.value = null;
    localStorage.removeItem(API_CONFIG.auth.userKey);
    localStorage.removeItem(API_CONFIG.auth.tokenKey);
  }

  return { login, logout, loading, error, user, token };
} 