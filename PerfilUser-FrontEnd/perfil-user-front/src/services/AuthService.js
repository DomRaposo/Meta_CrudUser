import ApiService from './ApiService';
import { API_CONFIG, ConfigUtils } from '@/config/api';

/**
 * Service específico para autenticação - NestJS Backend
 */
class AuthService {
  constructor() {
    this.api = ApiService;
  }

  // ===================
  // Métodos de Autenticação
  // ===================

  /**
   * Fazer login do usuário
   * @param {Object} credentials - { email, password }
   * @returns {Promise<Object>} Dados do usuário e tokens
   */
  async login(credentials) {
    try {
      ConfigUtils.debugLog('Iniciando processo de login...');
      
      const response = await this.api.post(API_CONFIG.endpoints.auth.login, credentials);
      
      ConfigUtils.debugLog('Login realizado com sucesso');
      
      // Salvar dados de autenticação
      if (response.access_token) {
        this.setAuthData(response.access_token, response.refresh_token, response.user);
      }
      
      return response;
    } catch (error) {
      ConfigUtils.debugLog('Erro no login:', error);
      throw this.handleAuthError(error);
    }
  }

  /**
   * Registrar novo usuário
   * @param {Object} userData - { name, email, password, confirmPassword }
   * @returns {Promise<Object>} Dados do usuário e tokens
   */
  async register(userData) {
    try {
      ConfigUtils.debugLog('Iniciando processo de registro...');
      
      const response = await this.api.post(API_CONFIG.endpoints.auth.register, userData);
      
      ConfigUtils.debugLog('Registro realizado com sucesso');
      
      // Salvar dados de autenticação
      if (response.access_token) {
        this.setAuthData(response.access_token, response.refresh_token, response.user);
      }
      
      return response;
    } catch (error) {
      ConfigUtils.debugLog('Erro no registro:', error);
      throw this.handleAuthError(error);
    }
  }

  /**
   * Fazer logout do usuário
   * @returns {Promise<void>}
   */
  async logout() {
    try {
      const refreshToken = this.getRefreshToken();
      
      if (refreshToken) {
        ConfigUtils.debugLog('Fazendo logout no servidor...');
        
        // Fazer logout no servidor
        await this.api.post(API_CONFIG.endpoints.auth.logout, {
          refreshToken
        });
      }
      
      // Limpar dados locais
      this.clearAuthData();
      
      // Emitir evento de logout
      if (typeof window !== 'undefined') {
        window.dispatchEvent(new CustomEvent('auth:logout'));
      }
      
      ConfigUtils.debugLog('Logout realizado com sucesso');
    } catch (error) {
      ConfigUtils.debugLog('Erro no logout:', error);
      
      // Mesmo com erro, limpar dados locais
      this.clearAuthData();
      
      throw this.handleAuthError(error);
    }
  }

  /**
   * Renovar token de autenticação
   * @returns {Promise<Object>} Novos tokens
   */
  async refreshToken() {
    try {
      const refreshToken = this.getRefreshToken();
      
      if (!refreshToken) {
        throw new Error('Refresh token não encontrado');
      }
      
      ConfigUtils.debugLog('Renovando token...');
      
      const response = await this.api.post(API_CONFIG.endpoints.auth.refresh, {
        refreshToken
      });
      
      // Salvar novos tokens
      this.setAuthData(response.access_token, response.refresh_token, response.user);
      
      ConfigUtils.debugLog('Token renovado com sucesso');
      
      return response;
    } catch (error) {
      ConfigUtils.debugLog('Erro ao renovar token:', error);
      
      // Se não conseguir renovar, deslogar
      this.clearAuthData();
      
      throw this.handleAuthError(error);
    }
  }

  // ===================
  // Métodos de Verificação
  // ===================

  /**
   * Verificar se usuário está autenticado
   * @returns {boolean} Status de autenticação
   */
  isAuthenticated() {
    return this.api.isAuthenticated();
  }

  /**
   * Verificar se usuário está autenticado com verificação no servidor
   * @returns {Promise<boolean>} Status de autenticação
   */
  async isAuthenticatedOnServer() {
    try {
      const token = this.getToken();
      if (!token) {
        return false;
      }

      // Fazer uma requisição simples para verificar se o token é válido
      await this.api.get('/users'); // Endpoint protegido
      return true;
    } catch (error) {
      ConfigUtils.debugLog('Token inválido no servidor:', error);
      return false;
    }
  }

  /**
   * Obter usuário atual
   * @returns {Promise<Object>} Dados do usuário
   */
  async getCurrentUser() {
    try {
      // Primeiro verificar se há usuário no localStorage
      const localUser = this.getUser();
      if (localUser) {
        return localUser;
      }

      // Se não há usuário local, buscar no servidor
      ConfigUtils.debugLog('Buscando usuário atual no servidor...');
      
      const users = await this.api.get('/users');
      const currentUser = users.find(user => user.id === this.getUserId());
      
      if (currentUser) {
        this.setUser(currentUser);
        return currentUser;
      }
      
      throw new Error('Usuário não encontrado');
    } catch (error) {
      ConfigUtils.debugLog('Erro ao obter usuário atual:', error);
      throw error;
    }
  }

  // ===================
  // Métodos de Armazenamento
  // ===================

  /**
   * Salvar dados de autenticação completos
   * @param {string} accessToken - Token JWT
   * @param {string} refreshToken - Refresh token
   * @param {Object} user - Dados do usuário
   */
  setAuthData(accessToken, refreshToken, user) {
    this.api.setToken(accessToken);
    this.api.setRefreshToken(refreshToken);
    this.api.setUser(user);
    
    ConfigUtils.debugLog('Dados de autenticação salvos');
  }

  /**
   * Obter token de autenticação
   * @returns {string|null} Token JWT
   */
  getToken() {
    return this.api.getToken();
  }

  /**
   * Obter refresh token
   * @returns {string|null} Refresh token
   */
  getRefreshToken() {
    return this.api.getRefreshToken();
  }

  /**
   * Obter dados do usuário
   * @returns {Object|null} Dados do usuário
   */
  getUser() {
    return this.api.getUser();
  }

  /**
   * Obter ID do usuário
   * @returns {string|null} ID do usuário
   */
  getUserId() {
    const user = this.getUser();
    return user ? user.id : null;
  }

  /**
   * Salvar dados do usuário
   * @param {Object} user - Dados do usuário
   */
  setUser(user) {
    this.api.setUser(user);
  }

  /**
   * Limpar dados de autenticação
   */
  clearAuthData() {
    this.api.clearAuthData();
    ConfigUtils.debugLog('Dados de autenticação limpos');
  }

  // ===================
  // Tratamento de Erros
  // ===================

  /**
   * Tratar erros de autenticação
   * @param {Error} error - Erro ocorrido
   * @returns {Object} Erro formatado
   */
  handleAuthError(error) {
    // Verificar se error é null ou undefined
    if (!error) {
      return {
        type: 'generic',
        message: 'Erro inesperado. Tente novamente mais tarde.'
      };
    }

    // Se o erro já foi processado pelo ApiService, retornar como está
    if (error.type) {
      return error;
    }

    // Se é um erro bruto, processar
    const errorData = error.response?.data;
    const status = error.response?.status;

    switch (status) {
      case 400:
        return {
          type: 'validation',
          message: errorData?.message || 'Dados inválidos',
          errors: errorData?.errors || {}
        };

      case 401:
        return {
          type: 'unauthorized',
          message: errorData?.message || 'Credenciais inválidas'
        };

      case 409:
        return {
          type: 'conflict',
          message: errorData?.message || 'Email já está em uso'
        };

      case 422:
        return {
          type: 'validation',
          message: errorData?.message || 'Dados inválidos',
          errors: errorData?.errors || {}
        };

      default:
        return {
          type: 'generic',
          message: errorData?.message || 'Erro interno do servidor. Tente novamente mais tarde.'
        };
    }
  }

  // ===================
  // Métodos Utilitários
  // ===================

  /**
   * Verificar se o token está próximo do vencimento
   * @param {number} minutes - Minutos antes do vencimento para considerar
   * @returns {boolean} Se o token está próximo do vencimento
   */
  isTokenExpiringSoon(minutes = 5) {
    const token = this.getToken();
    if (!token) return true;

    try {
      // Decodificar JWT para verificar expiração
      const payload = JSON.parse(atob(token.split('.')[1]));
      const exp = payload.exp * 1000; // Converter para milliseconds
      const now = Date.now();
      const threshold = minutes * 60 * 1000; // Minutos em milliseconds

      return (exp - now) < threshold;
    } catch (error) {
      ConfigUtils.debugLog('Erro ao verificar expiração do token:', error);
      return true;
    }
  }

  /**
   * Renovar token automaticamente se necessário
   * @returns {Promise<boolean>} Se o token foi renovado
   */
  async refreshTokenIfNeeded() {
    try {
      if (this.isTokenExpiringSoon()) {
        await this.refreshToken();
        return true;
      }
      return false;
    } catch (error) {
      ConfigUtils.debugLog('Erro ao renovar token automaticamente:', error);
      return false;
    }
  }

  // ===================
  // Event Listeners
  // ===================

  /**
   * Configurar listeners para eventos de autenticação
   */
  setupEventListeners() {
    if (typeof window === 'undefined') return;

    // Listener para logout forçado
    window.addEventListener('auth:logout', () => {
      this.clearAuthData();
      // Redirecionar para login se necessário
      if (window.location.pathname !== '/login') {
        window.location.href = '/login';
      }
    });

    // Listener para renovação de token
    window.addEventListener('auth:token-refreshed', (event) => {
      ConfigUtils.debugLog('Token renovado via evento:', event.detail);
    });
  }
}

// Exportar instância única (Singleton)
export default new AuthService(); 