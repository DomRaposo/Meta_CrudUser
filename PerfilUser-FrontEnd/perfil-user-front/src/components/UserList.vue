<template>
  <div class="user-list-container">
    <div class="list-header">
      <h2>Usuários Cadastrados</h2>
      <button @click="$emit('new-user')" class="btn-new-user">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
          <circle cx="9" cy="7" r="4"/>
          <line x1="19" y1="8" x2="19" y2="14"/>
          <line x1="22" y1="11" x2="16" y2="11"/>
        </svg>
        Novo Usuário
      </button>
    </div>

    <div class="table-wrapper">
      <table class="users-table">
        <thead>
          <tr>
            <th>Foto</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Endereço</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id" class="user-row">
            <td class="photo-cell">
              <div class="user-avatar">
                <img v-if="user.profileImage" :src="user.profileImage" :alt="user.name" @error="hideImage" />
                <div v-else class="avatar-placeholder">
                  {{ getInitials(user.name) }}
                </div>
              </div>
            </td>
            <td class="name-cell">
              <div class="user-name">{{ user.name }}</div>
            </td>
            <td class="email-cell">
              <div class="user-email">{{ user.email }}</div>
            </td>
            <td class="address-cell">
              <div class="user-address">
                <div v-if="hasAddress(user)" class="address-content">
                  <div class="address-line">
                    <span v-if="user.street">{{ user.street }}</span>
                    <span v-if="user.number">, {{ user.number }}</span>
                    <span v-if="user.complement"> - {{ user.complement }}</span>
                  </div>
                  <div class="address-line" v-if="user.neighborhood || user.city">
                    <span v-if="user.neighborhood">{{ user.neighborhood }}</span>
                    <span v-if="user.neighborhood && user.city"> - </span>
                    <span v-if="user.city">{{ user.city }}</span>
                    <span v-if="user.state">/{{ user.state }}</span>
                  </div>
                  <div class="address-line" v-if="user.zipCode">
                    <span class="zip-code">{{ user.zipCode }}</span>
                    <span v-if="user.country"> - {{ user.country }}</span>
                  </div>
                </div>
                <div v-else class="no-address">
                  <span>Não informado</span>
                </div>
              </div>
            </td>
            <td class="actions-cell">
              <div class="action-buttons">
                <button @click="$emit('view-user', user)" class="btn-action btn-view" title="Visualizar">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                  </svg>
                </button>
                <button @click="$emit('edit-user', user)" class="btn-action btn-edit bg-yellow-400 hover:bg-yellow-500 text-black" title="Editar">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="m18.5 2.5 1.414 1.414L7 17H3v-4L16.086 2.086z"/>
                  </svg>
                </button>
                <button @click="$emit('delete-user', user.id)" class="btn-action btn-delete" title="Excluir">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3,6 5,6 21,6"/>
                    <path d="m19,6v14a2,2 0 0,1-2,2H7a2,2 0 0,1-2-2V6m3,0V4a2,2 0 0,1,2-2h4a2,2 0 0,1,2,2v2"/>
                    <line x1="10" y1="11" x2="10" y2="17"/>
                    <line x1="14" y1="11" x2="14" y2="17"/>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="users.length === 0" class="empty-state">
      <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
        <circle cx="12" cy="7" r="4"/>
      </svg>
      <h3>Nenhum usuário cadastrado</h3>
      <p>Clique no botão "Novo Usuário" para começar.</p>
    </div>
  </div>
</template>

<script>
import '@/assets/css/user-list.css';
export default {
  name: 'UserList',
  props: {
    users: {
      type: Array,
      required: true
    }
  },
  methods: {
    getInitials(name) {
      if (!name) return '?';
      return name
        .split(' ')
        .map(word => word.charAt(0).toUpperCase())
        .join('')
        .substring(0, 2);
    },
    hasAddress(user) {
      return user.street || user.number || user.neighborhood || user.city || user.state || user.zipCode || user.country;
    },
    hideImage(event) {
      event.target.style.display = 'none';
    },
    viewUser(user) {
      this.$emit('view', user);
    },
    editUser(user) {
      this.$emit('edit', user);
    },
    deleteUser(user) {
      this.$emit('delete', user);
    },
    addNewUser() {
      this.$emit('add');
    }
  }
};
</script>

<style src="@/assets/css/user-list.css"></style>

