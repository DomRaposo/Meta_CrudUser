import { ref } from 'vue';
import { loginApi } from '@/services/auth';

const user = ref(null);
const token = ref(null);

export function useAuth() {
  async function login(email, password) {
    try {
      const response = await loginApi(email, password);
      user.value = response.user;
      token.value = response.token;
      // Aqui você pode salvar o token no localStorage/cookies se quiser
      return { success: true };
    } catch (e) {
      return { success: false, message: e.message || 'Erro ao fazer login' };
    }
  }
  function logout() {
    user.value = null;
    token.value = null;
    // Limpar localStorage/cookies se necessário
  }
  return { user, token, login, logout };
} 