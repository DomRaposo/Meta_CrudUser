<template>
  <div class="table-wrapper">
    <table class="users-table">
      <thead>
        <tr>
          <th>Foto</th>
          <th>Nome</th>
          <th>Email</th>
          <th>Idade</th>
          <th>Tipo de Usuário</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>
            <img 
              v-if="user.profile_image" 
              :src="user.profile_image" 
              alt="Foto de Perfil" 
              style="width:40px;height:40px;border-radius:50%;object-fit:cover;box-shadow:0 2px 8px #e0e7ef;" 
            />
            <div 
              v-else 
              style="width:40px;height:40px;border-radius:50%;background:#e0e7ef;display:flex;align-items:center;justify-content:center;font-weight:bold;color:#6366f1;font-size:1.2rem;"
            >
              {{ user.fullName ? user.fullName[0] : '?' }}
            </div>
          </td>
          <td>{{ user.fullName }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.age }}</td>
          <td>{{ user.role }}</td>
          <td class="actions-cell">
            <button 
              @click="$emit('edit-user', user)" 
              class="action-btn action-complete bg-yellow-400 hover:bg-yellow-500 text-black" 
              title="Alterar"
            >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="22" height="22">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.1 2.1 0 1 1 2.97 2.97L7.5 19.79l-4 1 1-4 14.362-14.303z" />
              </svg>
            </button>
            <button 
              @click="$emit('remove-user', user.id)" 
              class="action-btn action-remove" 
              title="Remover"
            >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="22" height="22">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9.5 7V5.75A1.75 1.75 0 0 1 11.25 4h1.5A1.75 1.75 0 0 1 14.5 5.75V7m-7 0v10.25A2.25 2.25 0 0 0 9.75 19.5h4.5A2.25 2.25 0 0 0 16.5 17.25V7" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 11v4m4-4v4" />
              </svg>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: 'UsersTable',
  props: {
    users: {
      type: Array,
      default: () => []
    }
  },
  emits: ['edit-user', 'remove-user']
};
</script> 