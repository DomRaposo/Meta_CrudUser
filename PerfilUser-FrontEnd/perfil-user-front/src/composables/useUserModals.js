import { ref } from 'vue';
import UserService from '@/services/UserService';

export function useUserModals(users, fetchUsers) {
  const editModalOpen = ref(false);
  const editUserData = ref({ 
    id: null, 
    fullName: '', 
    email: '', 
    age: '', 
    profile_image: '', 
    role: '', 
    password: '', 
    confirmPassword: '' 
  });
  const editError = ref('');
  const isCreatingUser = ref(false);
  
  const showUserFormModal = ref(false);
  const userFormError = ref('');
  const userFormLoading = ref(false);

  function openEditModal(user) {
    // Corrige o valor do campo role para 'isAdmin' ou 'user'
    let roleValue = user.role;
    if (roleValue === 'isAdmin' || roleValue === 'admin') {
      roleValue = 'isAdmin';
    } else {
      roleValue = 'user';
    }
    
    editUserData.value = { ...user, role: roleValue, password: '', confirmPassword: '' };
    editModalOpen.value = true;
    editError.value = '';
    isCreatingUser.value = false;
  }

  function closeEditModal() {
    editModalOpen.value = false;
    editError.value = '';
  }

  async function handleEditUserSubmit(userData) {
    try {
      // Checagem extra para evitar campos obrigatórios vazios ou nulos
      if (
        !userData.fullName ||
        String(userData.fullName).trim() === '' ||
        !userData.age ||
        String(userData.age).trim() === '' ||
        !userData.email ||
        String(userData.email).trim() === '' ||
        !userData.role ||
        String(userData.role).trim() === ''
      ) {
        editError.value = 'Preencha todos os campos obrigatórios!';
        return;
      }
      
      // Se senha for informada, confirmar
      if (userData.password && userData.password !== userData.confirmPassword) {
        editError.value = 'As senhas não coincidem.';
        return;
      }
      
      const payload = {
        fullName: userData.fullName,
        age: userData.age,
        profile_image: userData.profile_image,
        email: userData.email,
        role: userData.role
      };
      
      if (userData.password) payload.password = userData.password;
      
      console.log('Atualizando usuário:', userData.id, payload);
      const updatedUser = await UserService.updateUser(userData.id, payload);
      console.log('Usuário atualizado com sucesso:', updatedUser);

      // Atualize a lista local de usuários reativamente
      const idx = users.value.findIndex(u => u.id === userData.id);
      if (idx !== -1) {
        // Atualizar com os dados retornados da API
        const updatedUserData = updatedUser.data?.user || updatedUser.user || { ...userData, ...payload };
        users.value.splice(idx, 1, updatedUserData);
        console.log('Lista de usuários atualizada localmente');
      }

      // Fechar modal
      editModalOpen.value = false;
      
      // Recarregar lista completa para garantir sincronização
      console.log('Recarregando lista de usuários...');
      await fetchUsers();
      
      console.log('Página atualizada com sucesso!');
    } catch (e) {
      console.error('Erro ao atualizar usuário:', e);
      editError.value = e.response?.data?.message || 'Erro ao atualizar usuário';
    }
  }

  function openCreateUserModal() {
    showUserFormModal.value = true;
    userFormError.value = '';
  }

  function closeUserFormModal() {
    showUserFormModal.value = false;
    userFormError.value = '';
  }

  async function handleUserFormSubmit(userData) {
    userFormLoading.value = true;
    try {
      // Checagem extra para evitar campos obrigatórios vazios ou nulos
      if (
        !userData.fullName ||
        String(userData.fullName).trim() === '' ||
        !userData.age ||
        String(userData.age).trim() === '' ||
        !userData.email ||
        String(userData.email).trim() === '' ||
        !userData.password ||
        String(userData.password).trim() === '' ||
        !userData.role ||
        String(userData.role).trim() === ''
      ) {
        userFormError.value = 'Preencha todos os campos obrigatórios!';
        userFormLoading.value = false;
        return;
      }
      
      // Envia exatamente os campos da model
      const payload = {
        fullName: userData.fullName || '',
        age: userData.age || '',
        profile_image: userData.profile_image || '',
        email: userData.email || '',
        password: userData.password || '',
        role: userData.role || 'user'
      };
      
      console.log('Criando novo usuário:', payload);
      const newUser = await UserService.createUser(payload);
      console.log('Usuário criado com sucesso:', newUser);
      
      // Fechar modal
      showUserFormModal.value = false;
      
      // Recarregar lista completa para garantir sincronização
      console.log('Recarregando lista de usuários...');
      await fetchUsers();
      
      console.log('Página atualizada com sucesso!');
    } catch (e) {
      console.error('Erro ao criar usuário:', e);
      userFormError.value = e.response?.data?.message || 'Erro ao cadastrar usuário';
    } finally {
      userFormLoading.value = false;
    }
  }

  return {
    editModalOpen,
    editUserData,
    editError,
    showUserFormModal,
    userFormError,
    userFormLoading,
    openEditModal,
    closeEditModal,
    openCreateUserModal,
    closeUserFormModal,
    handleEditUserSubmit,
    handleUserFormSubmit
  };
} 