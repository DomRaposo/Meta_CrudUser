<template>
  <div class="modal-overlay" @click="$emit('close')">
    <div class="modal-content task-modal-content custom-task-modal" @click.stop>
      <div class="modal-header custom-modal-header">
        <h2 class="custom-modal-title">
          {{ foundUsers.length > 1 ? 'Usuários Encontrados' : 'Detalhes do Usuário' }}
        </h2>
        <button class="close-button custom-close-btn" @click="$emit('close')">
          <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
      
      <!-- Multiple users found -->
      <div v-if="foundUsers.length > 1" class="custom-modal-body" style="gap: 1.5rem;">
        <div 
          v-for="user in foundUsers" 
          :key="user.id" 
          class="modal-field" 
          style="flex-direction: column; align-items: flex-start; background: #f3f4f6; border-radius: 0.7rem; padding: 1rem 1.2rem; margin-bottom: 0.5rem; width: 100%;"
        >
          <div><span class="modal-label">Nome:</span> <span class="modal-value">{{ user.fullName }}</span></div>
          <div><span class="modal-label">Email:</span> <span class="modal-value">{{ user.email }}</span></div>
          <button class="search-btn" style="margin-top: 0.7rem;" @click="$emit('show-details', user)">Ver detalhes</button>
        </div>
      </div>
      
      <!-- Single user details -->
      <div v-else-if="foundUsers.length === 1 && selectedUser" class="modal-body custom-modal-body">
        <div class="modal-field"><span class="modal-label">Nome:</span> <span class="modal-value">{{ selectedUser.fullName }}</span></div>
        <div class="modal-field"><span class="modal-label">Email:</span> <span class="modal-value">{{ selectedUser.email }}</span></div>
        <div class="modal-field"><span class="modal-label">Idade:</span> <span class="modal-value">{{ selectedUser.age }}</span></div>
        <div class="modal-field"><span class="modal-label">Tipo de Usuário:</span> <span class="modal-value">{{ selectedUser.role }}</span></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UsersSearchModal',
  props: {
    foundUsers: {
      type: Array,
      default: () => []
    },
    selectedUser: {
      type: Object,
      default: null
    }
  },
  emits: ['close', 'show-details']
};
</script> 