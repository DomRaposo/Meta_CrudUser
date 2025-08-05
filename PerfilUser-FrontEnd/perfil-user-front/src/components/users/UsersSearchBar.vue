<template>
  <div class="search-bar">
    <input 
      :value="searchTerm" 
      @input="$emit('update:searchTerm', $event.target.value)"
      class="search-input" 
      placeholder="Buscar por nome..." 
    />
    <button class="search-btn" @click="$emit('search')">Buscar</button>
    <button class="tasks-header-btn" @click="$emit('create-user')" style="display: flex; align-items: center; gap: 0.5rem;">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:middle;">
        <path d="M12 4v16m8-8H4"/>
      </svg>
      Criar novo usuário
    </button>
  </div>
</template>

<script>
export default {
  name: 'UsersSearchBar',
  props: {
    searchTerm: {
      type: String,
      default: ''
    }
  },
  emits: ['update:searchTerm', 'search', 'create-user']
};
</script> 