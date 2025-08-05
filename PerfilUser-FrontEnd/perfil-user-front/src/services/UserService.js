import ApiService from './ApiService';
import { API_CONFIG, ConfigUtils } from '@/config/api';

/**
 * Service específico para operações de usuário - NestJS Backend
 */
class UserService {
  constructor() {
    this.api = ApiService;
  }

  // ===================
  // Métodos CRUD
  // ===================

  /**
   * Listar todos os usuários
   * @param {Object} params - Parâmetros de consulta (paginação, filtros)
   * @returns {Promise<Array>} Lista de usuários
   */
  async getUsers(params = {}) {
    try {
      ConfigUtils.debugLog('Buscando lista de usuários...');
      
      const response = await this.api.get(API_CONFIG.endpoints.users.list, { params });
      
      ConfigUtils.debugLog('Lista de usuários obtida com sucesso');
      return response;
    } catch (error) {
      ConfigUtils.debugLog('Erro na busca de usuários:', error);
      throw this.handleUserError(error);
    }
  }

  /**
   * Buscar usuário por ID
   * @param {string} id - ID do usuário
   * @returns {Promise<Object>} Dados do usuário
   */
  async getUserById(id) {
    try {
      ConfigUtils.debugLog(`Buscando usuário ID: ${id}`);
      
      const url = ConfigUtils.replaceUrlParams(API_CONFIG.endpoints.users.show, { id });
      const response = await this.api.get(url);
      
      ConfigUtils.debugLog(`Usuário ${id} obtido com sucesso`);
      return response;
    } catch (error) {
      ConfigUtils.debugLog(`Erro ao buscar usuário ${id}:`, error);
      throw this.handleUserError(error);
    }
  }

  /**
   * Criar novo usuário
   * @param {Object} userData - Dados do usuário
   * @returns {Promise<Object>} Usuário criado
   */
  async createUser(userData) {
    try {
      ConfigUtils.debugLog('Criando novo usuário...');
      
      const response = await this.api.post(API_CONFIG.endpoints.users.create, userData);
      
      ConfigUtils.debugLog('Usuário criado com sucesso');
      return response;
    } catch (error) {
      ConfigUtils.debugLog('Erro ao criar usuário:', error);
      throw this.handleUserError(error);
    }
  }

  /**
   * Atualizar usuário
   * @param {string} id - ID do usuário
   * @param {Object} userData - Dados atualizados
   * @returns {Promise<Object>} Usuário atualizado
   */
  async updateUser(id, userData) {
    try {
      ConfigUtils.debugLog(`Atualizando usuário ID: ${id}`);
      const url = ConfigUtils.replaceUrlParams(API_CONFIG.endpoints.users.update, { id });
      // Troca PATCH por PUT
      const response = await this.api.put(url, userData);
      ConfigUtils.debugLog(`Usuário ${id} atualizado com sucesso`);
      return response;
    } catch (error) {
      ConfigUtils.debugLog(`Erro ao atualizar usuário ${id}:`, error);
      throw this.handleUserError(error);
    }
  }

  /**
   * Deletar usuário
   * @param {string} id - ID do usuário
   * @returns {Promise<void>}
   */
  async deleteUser(id) {
    try {
      ConfigUtils.debugLog(`Deletando usuário ID: ${id}`);
      
      const url = ConfigUtils.replaceUrlParams(API_CONFIG.endpoints.users.delete, { id });
      await this.api.delete(url);
      
      ConfigUtils.debugLog(`Usuário ${id} deletado com sucesso`);
    } catch (error) {
      ConfigUtils.debugLog(`Erro ao deletar usuário ${id}:`, error);
      throw this.handleUserError(error);
    }
  }

  // ===================
  // Métodos de Busca Avançada
  // ===================

  /**
   * Buscar usuários com filtros
   * @param {Object} filters - Filtros de busca
   * @returns {Promise<Array>} Lista de usuários filtrados
   */
  async searchUsers(filters = {}) {
    try {
      ConfigUtils.debugLog('Buscando usuários com filtros:', filters);
      
      const response = await this.api.get(API_CONFIG.endpoints.users.list, {
        params: filters
      });
      
      ConfigUtils.debugLog('Busca de usuários concluída');
      return response;
    } catch (error) {
      ConfigUtils.debugLog('Erro na busca de usuários:', error);
      throw this.handleUserError(error);
    }
  }

  /**
   * Buscar usuário por email
   * @param {string} email - Email do usuário
   * @returns {Promise<Object|null>} Usuário encontrado ou null
   */
  async getUserByEmail(email) {
    try {
      ConfigUtils.debugLog(`Buscando usuário por email: ${email}`);
      
      const users = await this.searchUsers({ email });
      const user = users.find(u => u.email === email);
      
      if (user) {
        ConfigUtils.debugLog(`Usuário encontrado: ${user.name}`);
      } else {
        ConfigUtils.debugLog('Usuário não encontrado');
      }
      
      return user || null;
    } catch (error) {
      ConfigUtils.debugLog('Erro ao buscar usuário por email:', error);
      throw this.handleUserError(error);
    }
  }

  // ===================
  // Métodos de Validação
  // ===================

  /**
   * Validar dados do usuário antes de enviar
   * @param {Object} userData - Dados do usuário
   * @returns {Object} Dados validados
   */
  validateUserData(userData) {
    const errors = {};
    
    // Validar nome
    if (!userData.name || userData.name.trim().length < 2) {
      errors.name = 'Nome deve ter pelo menos 2 caracteres';
    }
    
    // Validar email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!userData.email || !emailRegex.test(userData.email)) {
      errors.email = 'Email deve ter um formato válido';
    }
    
    // Validar senha (se fornecida)
    if (userData.password && userData.password.length < 6) {
      errors.password = 'Senha deve ter pelo menos 6 caracteres';
    }
    
    // Validar URL da imagem de perfil (se fornecida)
    if (userData.profileImage) {
      const urlRegex = /^https?:\/\/.+/;
      if (!urlRegex.test(userData.profileImage)) {
        errors.profileImage = 'URL da imagem deve ser válida (http ou https)';
      }
    }
    
    // Validar endereço (se fornecido)
    if (userData.address && userData.address.trim().length < 5) {
      errors.address = 'Endereço deve ter pelo menos 5 caracteres';
    }
    
    if (Object.keys(errors).length > 0) {
      throw {
        type: 'validation',
        message: 'Dados inválidos',
        errors
      };
    }
    
    return {
      name: userData.name.trim(),
      email: userData.email.toLowerCase().trim(),
      ...(userData.password && userData.password.trim() && { password: userData.password }),
      ...(userData.profileImage && { profileImage: userData.profileImage.trim() }),
      ...(userData.address && { address: userData.address.trim() })
    };
  }

  // ===================
  // Métodos Utilitários
  // ===================

  /**
   * Formatar dados do usuário para exibição
   * @param {Object} user - Dados do usuário
   * @returns {Object} Dados formatados
   */
  formatUserData(user) {
    return {
      id: user.id,
      name: user.name,
      email: user.email,
      profileImage: user.profileImage || null,
      address: user.address || null,
      createdAt: user.createdAt ? new Date(user.createdAt).toLocaleDateString('pt-BR') : null,
      updatedAt: user.updatedAt ? new Date(user.updatedAt).toLocaleDateString('pt-BR') : null
    };
  }

  /**
   * Formatar lista de usuários para exibição
   * @param {Array} users - Lista de usuários
   * @returns {Array} Lista formatada
   */
  formatUsersList(users) {
    return users.map(user => this.formatUserData(user));
  }

  /**
   * Verificar se usuário pode ser editado
   * @param {string} userId - ID do usuário
   * @returns {boolean} Se pode ser editado
   */
  canEditUser(userId) {
    // Implementar lógica de permissões aqui
    // Por exemplo, verificar se é o próprio usuário ou admin
    const currentUser = this.api.getUser();
    return currentUser && (currentUser.id === userId || currentUser.role === 'admin');
  }

  /**
   * Verificar se usuário pode ser deletado
   * @param {string} userId - ID do usuário
   * @returns {boolean} Se pode ser deletado
   */
  canDeleteUser(userId) {
    // Implementar lógica de permissões aqui
    const currentUser = this.api.getUser();
    return currentUser && currentUser.role === 'admin' && currentUser.id !== userId;
  }

  // ===================
  // Tratamento de Erros
  // ===================

  /**
   * Tratar erros específicos de usuário
   * @param {Error} error - Erro ocorrido
   * @returns {Object} Erro formatado
   */
  handleUserError(error) {
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

      case 404:
        return {
          type: 'not_found',
          message: errorData?.message || 'Usuário não encontrado'
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
  // Cache e Performance
  // ===================

  /**
   * Cache simples para usuários
   */
  _userCache = new Map();
  _cacheTimeout = 5 * 60 * 1000; // 5 minutos

  /**
   * Obter usuário do cache ou buscar no servidor
   * @param {string} id - ID do usuário
   * @returns {Promise<Object>} Dados do usuário
   */
  async getUserFromCache(id) {
    const cacheKey = `user_${id}`;
    const cached = this._userCache.get(cacheKey);
    
    if (cached && (Date.now() - cached.timestamp < this._cacheTimeout)) {
      ConfigUtils.debugLog(`Usuário ${id} obtido do cache`);
      return cached.data;
    }
    
    const user = await this.getUserById(id);
    
    // Salvar no cache
    this._userCache.set(cacheKey, {
      data: user,
      timestamp: Date.now()
    });
    
    return user;
  }

  /**
   * Limpar cache de usuários
   */
  clearUserCache() {
    this._userCache.clear();
    ConfigUtils.debugLog('Cache de usuários limpo');
  }

  /**
   * Invalidar cache de usuário específico
   * @param {string} id - ID do usuário
   */
  invalidateUserCache(id) {
    this._userCache.delete(`user_${id}`);
    ConfigUtils.debugLog(`Cache do usuário ${id} invalidado`);
  }
}

// Exportar instância única (Singleton)
export default new UserService(); 