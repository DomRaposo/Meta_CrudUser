<template>
  <div class="app-container">
    <!-- Header Bar -->
    <UsersHeaderBar @go-dashboard="goDashboard" @logout="logout" />
    
    <!-- Main Content -->
    <div class="users-header-content">
      <div class="users-header-title">Usuários Cadastrados</div>
    </div>
    
    <div class="users-card">
      <!-- Search Bar -->
      <UsersSearchBar 
        :search-term="searchTerm"
        @update:search-term="searchTerm = $event"
        @search="searchUsers"
        @create-user="openCreateUserModal"
      />
      
      <!-- Search Results Modal -->
      <UsersSearchModal 
        v-if="showModal"
        :found-users="foundUsers"
        :selected-user="selectedUser"
        @close="closeModal"
        @show-details="showUserDetails"
      />
      
      <!-- Error Toast -->
      <div v-if="showError" class="users-error-toast">Usuários não encontrados</div>
      
      <!-- Users Table -->
      <UsersTable 
        :users="paginatedUsers"
        @edit-user="openEditModal"
        @remove-user="removeUser"
      />
      
      <!-- Pagination -->
      <UsersPagination 
        v-if="totalPages > 1"
        :current-page="currentPage"
        :total-pages="totalPages"
        @prev-page="prevPage"
        @next-page="nextPage"
      />
      
      <!-- Empty State -->
      <UsersEmptyState v-if="users.length === 0 && !showError" />
    </div>
    
    <!-- Modals -->
    <ModalUserForm 
      v-if="showUserFormModal" 
      :is-edit="false" 
      @close="closeUserFormModal" 
      @submit="handleUserFormSubmit" 
    />
    
    <ModalUserForm 
      v-if="editModalOpen" 
      :is-edit="true" 
      :user="editUserData" 
      @close="closeEditModal" 
      @submit="handleEditUserSubmit" 
    />
  </div>
</template>

<script>
import '@/assets/css/user.css';
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '@/composables/useAuth';
import { useUsers } from '@/composables/useUsers';
import { useUserSearch } from '@/composables/useUserSearch';
import { useUserModals } from '@/composables/useUserModals';
import { usePagination } from '@/composables/usePagination';
import ModalUserForm from '@/components/ModalUserForm.vue';
import UsersHeaderBar from '@/components/users/UsersHeaderBar.vue';
import UsersSearchBar from '@/components/users/UsersSearchBar.vue';
import UsersSearchModal from '@/components/users/UsersSearchModal.vue';
import UsersTable from '@/components/users/UsersTable.vue';
import UsersPagination from '@/components/users/UsersPagination.vue';
import UsersEmptyState from '@/components/users/UsersEmptyState.vue';

export default {
  name: 'UsersView',
  components: { 
    ModalUserForm,
    UsersHeaderBar,
    UsersSearchBar,
    UsersSearchModal,
    UsersTable,
    UsersPagination,
    UsersEmptyState
  },
  setup() {
    const router = useRouter();
    const { logout: doLogout } = useAuth();
    
    // Composables
    const { 
      users, 
      showError, 
      fetchUsers, 
      removeUser 
    } = useUsers();
    
    const {
      searchTerm,
      showModal,
      foundUsers,
      selectedUser,
      searchUsers,
      showUserDetails,
      closeModal
    } = useUserSearch(users);
    
    const {
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
    } = useUserModals(users, fetchUsers);
    
    const {
      currentPage,
      totalPages,
      paginatedUsers,
      nextPage,
      prevPage
    } = usePagination(users);
    
    // Navigation methods
    function goDashboard() {
      router.push('/dashboard');
    }
    
    function logout() {
      doLogout()
        .catch(() => {})
        .finally(() => {
          router.push({ path: '/login', query: { logoutSuccess: 1 } });
        });
    }
    
    // Initialize
    onMounted(fetchUsers);
    
    return {
      // Users data
      users,
      showError,
      paginatedUsers,
      
      // Search functionality
      searchTerm,
      showModal,
      foundUsers,
      selectedUser,
      searchUsers,
      showUserDetails,
      closeModal,
      
      // Modal functionality
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
      handleUserFormSubmit,
      
      // Pagination
      currentPage,
      totalPages,
      nextPage,
      prevPage,
      
      // Actions
      removeUser,
      goDashboard,
      logout
    };
  }
};
</script> 