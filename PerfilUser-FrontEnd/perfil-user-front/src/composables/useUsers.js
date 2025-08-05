import { ref } from 'vue';
import UserService from '@/services/UserService';

export function useUsers() {
  const users = ref([]);
  const showError = ref(false);

  async function fetchUsers() {
    try {
      console.log('Buscando lista de usuários...');
      const res = await UserService.getUsers();
      console.log('Resposta da API:', res);
      
      users.value = Array.isArray(res) ? res : (res.data || []);
      console.log('Lista de usuários atualizada:', users.value.length, 'usuários');
      
      showError.value = false;
    } catch (e) {
      console.error('Erro ao buscar usuários:', e);
      showError.value = true;
    }
  }

  async function removeUser(id) {
    if (confirm('Tem certeza que deseja remover este usuário?')) {
      try {
        console.log('Removendo usuário:', id);
        await UserService.deleteUser(id);
        console.log('Usuário removido com sucesso');
        
        // Recarregar lista completa para garantir sincronização
        console.log('Recarregando lista de usuários...');
        await fetchUsers();
        
        console.log('Página atualizada com sucesso!');
      } catch (e) {
        console.error('Erro ao remover usuário:', e);
        
        // Tratar diferentes tipos de erro
        let errorMessage = 'Erro ao remover usuário';
        
        if (e.type === 'network') {
          errorMessage = 'Erro de conexão. Verifique sua internet.';
        } else if (e.type === 'unauthorized') {
          errorMessage = 'Sessão expirada. Faça login novamente.';
        } else if (e.type === 'not_found') {
          errorMessage = 'Usuário não encontrado.';
        } else if (e.response?.data?.message) {
          errorMessage = e.response.data.message;
        } else if (e.message) {
          errorMessage = e.message;
        }
        
        alert(errorMessage);
      }
    }
  }

  return {
    users,
    showError,
    fetchUsers,
    removeUser
  };
} 