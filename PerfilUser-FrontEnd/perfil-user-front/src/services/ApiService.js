import axios from 'axios';
import { API_CONFIG, ConfigUtils } from '@/config/api';

/**
 * Service centralizado para todas as chamadas da API - NestJS Backend
 */
class ApiService {
  constructor() {
    // Criar instância do axios com configuração profissional
    this.api = axios.create({
      baseURL: API_CONFIG.baseURL,
      timeout: API_CONFIG.timeout,
      headers: API_CONFIG.headers
    });

    // Configurar interceptadores
    this.setupInterceptors();
    
    // Estado de autenticação
    this.isRefreshing = false;
    this.failedQueue = [];
  }

  /**
   * Configurar interceptadores de request e response
   */
  setupInterceptors() {
    // Request interceptor - Adicionar token JWT automaticamente
    this.api.interceptors.request.use(
      (config) => {
        const token = this.getToken();
        if (token) {
          config.headers.Authorization = `${API_CONFIG.auth.tokenPrefix}${token}`;
        }
        
        ConfigUtils.debugLog('Request enviado:', {
          method: config.method.toUpperCase(),
          url: config.url,
          hasAuth: !!token
        });
        
        return config;
      },
      (error) => {
        ConfigUtils.debugLog('Erro no request interceptor:', error);
        return Promise.reject(error);
      }
    );

    // Response interceptor - Tratamento de erros e refresh token
    this.api.interceptors.response.use(
      (response) => {
        ConfigUtils.debugLog('Response recebido:', {
          status: response.status,
          url: response.config.url
        });
        return response;
      },
      async (error) => {
        const originalRequest = error.config;
        
        // Verificar se é erro 401 (token expirado)
        if (error.response?.status === 401 && !originalRequest._retry) {
          if (this.isRefreshing) {
            // Se já está renovando, colocar na fila
            return new Promise((resolve, reject) => {
              this.failedQueue.push({ resolve, reject });
            }).then(token => {
              originalRequest.headers.Authorization = `${API_CONFIG.auth.tokenPrefix}${token}`;
              return this.api(originalRequest);
            }).catch(err => {
              return Promise.reject(err);
            });
          }

          originalRequest._retry = true;
          this.isRefreshing = true;

          try {
            const refreshToken = this.getRefreshToken();
            if (refreshToken) {
              const newToken = await this.refreshAuthToken(refreshToken);
              this.processQueue(null, newToken);
              originalRequest.headers.Authorization = `${API_CONFIG.auth.tokenPrefix}${newToken}`;
              return this.api(originalRequest);
            }
          } catch (refreshError) {
            this.processQueue(refreshError, null);
            this.handleAuthError();
            return Promise.reject(refreshError);
          } finally {
            this.isRefreshing = false;
          }
        }

        return Promise.reject(this.handleApiError(error));
      }
    );
  }

  /**
   * Processar fila de requests falhosos
   * @param {Error} error - Erro ocorrido
   * @param {string} token - Novo token (se houver)
   */
  processQueue(error, token = null) {
    this.failedQueue.forEach(({ resolve, reject }) => {
      if (error) {
        reject(error);
      } else {
        resolve(token);
      }
    });
    
    this.failedQueue = [];
  }

  /**
   * Renovar token de autenticação
   * @param {string} refreshToken - Token de refresh
   * @returns {Promise<string>} Novo access token
   */
  async refreshAuthToken(refreshToken) {
    try {
      const response = await axios.post(
        `${API_CONFIG.baseURL}${API_CONFIG.endpoints.auth.refresh}`,
        { refreshToken },
        { headers: API_CONFIG.headers }
      );

      const { access_token, refresh_token } = response.data;
      
      // Salvar novos tokens
      this.setToken(access_token);
      this.setRefreshToken(refresh_token);
      
      ConfigUtils.debugLog('Token renovado com sucesso');
      return access_token;
    } catch (error) {
      ConfigUtils.debugLog('Erro ao renovar token:', error);
      throw error;
    }
  }

  /**
   * Tratamento profissional de erros da API
   * @param {Error} error - Erro da API
   * @returns {Object} Erro formatado
   */
  handleApiError(error) {
    if (!error.response) {
      // Erro de rede
      return {
        type: 'network',
        message: 'Erro de conexão. Verifique sua internet.',
        originalError: error
      };
    }

    const { status, data } = error.response;
    
    switch (status) {
      case 400:
        return {
          type: 'validation',
          message: data.message || 'Dados inválidos',
          errors: data.errors || {},
          statusCode: status
        };
        
      case 401:
        return {
          type: 'unauthorized',
          message: data.message || 'Token inválido ou expirado',
          statusCode: status
        };
        
      case 403:
        return {
          type: 'forbidden',
          message: data.message || 'Acesso negado',
          statusCode: status
        };
        
      case 404:
        return {
          type: 'not_found',
          message: data.message || 'Recurso não encontrado',
          statusCode: status
        };
        
      case 409:
        return {
          type: 'conflict',
          message: data.message || 'Conflito de dados',
          statusCode: status
        };
        
      case 422:
        return {
          type: 'validation',
          message: data.message || 'Dados inválidos',
          errors: data.errors || {},
          statusCode: status
        };
        
      case 500:
        return {
          type: 'server_error',
          message: 'Erro interno do servidor',
          statusCode: status
        };
        
      default:
        return {
          type: 'generic',
          message: data.message || 'Erro inesperado',
          statusCode: status
        };
    }
  }

  /**
   * Tratar erro de autenticação
   */
  handleAuthError() {
    this.clearAuthData();
    
    // Emitir evento para componentes
    if (typeof window !== 'undefined') {
      window.dispatchEvent(new CustomEvent('auth:logout'));
    }
    
    ConfigUtils.debugLog('Usuário deslogado por erro de autenticação');
  }

  // ===================
  // Métodos HTTP
  // ===================

  /**
   * GET request
   * @param {string} url - URL do endpoint
   * @param {Object} config - Configurações adicionais
   * @returns {Promise} Response da API
   */
  async get(url, config = {}) {
    const response = await this.api.get(url, config);
    return response.data;
  }

  /**
   * POST request
   * @param {string} url - URL do endpoint
   * @param {Object} data - Dados para enviar
   * @param {Object} config - Configurações adicionais
   * @returns {Promise} Response da API
   */
  async post(url, data = {}, config = {}) {
    const response = await this.api.post(url, data, config);
    return response.data;
  }

  /**
   * PUT request
   * @param {string} url - URL do endpoint
   * @param {Object} data - Dados para enviar
   * @param {Object} config - Configurações adicionais
   * @returns {Promise} Response da API
   */
  async put(url, data = {}, config = {}) {
    const response = await this.api.put(url, data, config);
    return response.data;
  }

  /**
   * PATCH request
   * @param {string} url - URL do endpoint
   * @param {Object} data - Dados para enviar
   * @param {Object} config - Configurações adicionais
   * @returns {Promise} Response da API
   */
  async patch(url, data = {}, config = {}) {
    const response = await this.api.patch(url, data, config);
    return response.data;
  }

  /**
   * DELETE request
   * @param {string} url - URL do endpoint
   * @param {Object} config - Configurações adicionais
   * @returns {Promise} Response da API
   */
  async delete(url, config = {}) {
    const response = await this.api.delete(url, config);
    return response.data;
  }

  // ===================
  // Métodos de Autenticação
  // ===================

  /**
   * Obter token de autenticação
   * @returns {string|null} Token JWT
   */
  getToken() {
    if (typeof window === 'undefined') return null;
    return localStorage.getItem(API_CONFIG.auth.tokenKey);
  }

  /**
   * Obter refresh token
   * @returns {string|null} Refresh token
   */
  getRefreshToken() {
    if (typeof window === 'undefined') return null;
    return localStorage.getItem(API_CONFIG.auth.refreshTokenKey);
  }

  /**
   * Salvar token de autenticação
   * @param {string} token - Token JWT
   */
  setToken(token) {
    if (typeof window === 'undefined') return;
    localStorage.setItem(API_CONFIG.auth.tokenKey, token);
  }

  /**
   * Salvar refresh token
   * @param {string} refreshToken - Refresh token
   */
  setRefreshToken(refreshToken) {
    if (typeof window === 'undefined') return;
    localStorage.setItem(API_CONFIG.auth.refreshTokenKey, refreshToken);
  }

  /**
   * Salvar dados do usuário
   * @param {Object} user - Dados do usuário
   */
  setUser(user) {
    if (typeof window === 'undefined') return;
    localStorage.setItem(API_CONFIG.auth.userKey, JSON.stringify(user));
  }

  /**
   * Obter dados do usuário
   * @returns {Object|null} Dados do usuário
   */
  getUser() {
    if (typeof window === 'undefined') return null;
    const user = localStorage.getItem(API_CONFIG.auth.userKey);
    return user ? JSON.parse(user) : null;
  }

  /**
   * Limpar dados de autenticação
   */
  clearAuthData() {
    if (typeof window === 'undefined') return;
    localStorage.removeItem(API_CONFIG.auth.tokenKey);
    localStorage.removeItem(API_CONFIG.auth.refreshTokenKey);
    localStorage.removeItem(API_CONFIG.auth.userKey);
  }

  /**
   * Verificar se usuário está autenticado
   * @returns {boolean} Status de autenticação
   */
  isAuthenticated() {
    return !!this.getToken();
  }
}

// Exportar instância única (Singleton)
export default new ApiService(); 