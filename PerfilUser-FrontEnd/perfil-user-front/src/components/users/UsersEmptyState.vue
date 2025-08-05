<template>
  <div class="empty-state">
    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
      <circle cx="12" cy="7" r="4"/>
    </svg>
    <h3>Nenhum usuário cadastrado</h3>
    <p>Cadastre um novo usuário para começar.</p>
  </div>
</template>

<script>
export default {
  name: 'UsersEmptyState'
};
</script> 