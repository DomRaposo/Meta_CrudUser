<template>
  <div class="users-header-bar" style="justify-content: flex-end; gap: 1rem; margin-bottom: 1.5rem;">
    <button class="tasks-header-btn" @click="$emit('go-dashboard')">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:middle;">
        <polyline points="15 18 9 12 15 6"/>
        <line x1="9" y1="12" x2="21" y2="12"/>
      </svg>
      Voltar
    </button>
    <button class="tasks-header-btn" @click="$emit('logout')">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:middle;">
        <path d="M17 16l4-4m0 0l-4-4m4 4H7"/>
        <path d="M9 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h4"/>
      </svg>
      Logout
    </button>
  </div>
</template>

<script>
export default {
  name: 'UsersHeaderBar',
  emits: ['go-dashboard', 'logout']
};
</script> 