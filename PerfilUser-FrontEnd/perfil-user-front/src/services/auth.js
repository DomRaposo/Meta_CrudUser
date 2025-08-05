import ApiService from './ApiService';
import { API_CONFIG } from '@/config/api';

export async function loginApi(email, password) {
  try {
    console.log('Tentando login com:', { email, password });
    
    // Usar apenas '/login' já que o baseURL já inclui '/api'
    const response = await ApiService.post('/login', { email, password });
    
    console.log('Resposta do login:', response);
    
    return response;
  } catch (error) {
    console.error('Erro no login:', error);
    
    if (error.response) {
      // O servidor respondeu com um status de erro
      console.error('Status:', error.response.status);
      console.error('Dados:', error.response.data);
      
      if (error.response.status === 401) {
        throw new Error('Usuário ou senha incorretos. Digite novamente.');
      } else {
        throw new Error(`Erro do servidor: ${error.response.status} - ${error.response.data.message || 'Erro desconhecido'}`);
      }
    } else if (error.request) {
      // A requisição foi feita mas não houve resposta
      console.error('Sem resposta do servidor');
      throw new Error('Erro de conexão. Verifique sua internet.');
    } else {
      // Algo aconteceu na configuração da requisição
      console.error('Erro na configuração:', error.message);
      throw new Error('Erro na configuração da requisição.');
    }
  }
}

export async function logoutApi() {
  try {
    // Usar apenas '/logout' já que o baseURL já inclui '/api'
    await ApiService.post('/logout');
    // Agora sim, remova o token usando a chave correta
    localStorage.removeItem(API_CONFIG.auth.tokenKey);
  } catch (error) {
    console.error('Erro no logout:', error);
    // Mesmo com erro, limpar o localStorage
    localStorage.removeItem(API_CONFIG.auth.tokenKey);
    throw error;
  }
} 