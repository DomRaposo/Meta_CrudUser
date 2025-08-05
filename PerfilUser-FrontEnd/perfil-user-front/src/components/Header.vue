<template>
  <header class="header">
    <div class="header-content">
      <div class="user-info">
        <h1 class="header-title">{{ user ? 'Dashboard' : 'Gestão de Usuários' }}</h1>
        <p v-if="user" class="header-subtitle">Bem-vindo, {{ user.name }}!</p>
      </div>
      
      <div v-if="user" class="header-actions">
        <button @click="$emit('profile')" class="header-button profile-button">
          <svg class="header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          Perfil
        </button>
        <button @click="$emit('logout')" class="header-button logout-button">
          <svg class="header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          Sair
        </button>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: 'AppHeader',
  props: ['user'],
  emits: ['logout', 'profile']
};
</script>

<style src="@/assets/css/header.css"></style>
