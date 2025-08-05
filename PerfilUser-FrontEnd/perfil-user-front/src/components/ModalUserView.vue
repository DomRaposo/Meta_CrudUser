<template>
  <div class="modal-overlay" @click="closeModal">
    <div class="modal-content" @click.stop>
      <div class="modal-header">
        <h2>Perfil do Usuário</h2>
        <button class="close-button" @click="closeModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
      
      <div class="modal-body">
        <div class="profile-section">
          <div class="profile-header">
            <div class="profile-avatar">
              <img v-if="user.profileImage" :src="user.profileImage" :alt="user.name" @error="hideImage" />
              <div v-else class="avatar-placeholder">
                {{ getInitials(user.name) }}
              </div>
            </div>
            <div class="profile-info">
              <h3 class="profile-name">{{ user.name }}</h3>
              <p class="profile-email">{{ user.email }}</p>
              <div class="profile-id">ID: {{ user.id }}</div>
            </div>
          </div>
        </div>

        <div class="details-section">
          <div class="section-header">
            <h4>Informações Pessoais</h4>
          </div>
          
          <div class="info-grid">
            <div class="info-item">
              <span class="info-label">Nome Completo</span>
              <span class="info-value">{{ user.name }}</span>
            </div>
            
            <div class="info-item">
              <span class="info-label">Email</span>
              <span class="info-value">{{ user.email }}</span>
            </div>
            
            <div class="info-item">
              <span class="info-label">Foto de Perfil</span>
              <span class="info-value">
                <a v-if="user.profileImage" :href="user.profileImage" target="_blank" class="profile-link">
                  Ver imagem
                </a>
                <span v-else class="no-data">Não informado</span>
              </span>
            </div>
          </div>
        </div>

        <div class="details-section">
          <div class="section-header">
            <h4>Endereço Profissional</h4>
          </div>
          
          <div v-if="hasAddress" class="address-card">
            <div class="address-content">
              <div class="address-line" v-if="user.street || user.number">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                  <circle cx="12" cy="10" r="3"/>
                </svg>
                <span>
                  <span v-if="user.street">{{ user.street }}</span>
                  <span v-if="user.number">, {{ user.number }}</span>
                  <span v-if="user.complement"> - {{ user.complement }}</span>
                </span>
              </div>
              
              <div class="address-line" v-if="user.neighborhood">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M12 2L2 7v10c0 5.55 3.84 10 9 11 1.16-.21 2.31-.48 3.47-.81"/>
                  <path d="M22 12c0 5.55-3.84 10-9 11-1.16-.21-2.31-.48-3.47-.81"/>
                </svg>
                <span>{{ user.neighborhood }}</span>
              </div>
              
              <div class="address-line" v-if="user.city || user.state">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M3 21h18M4 21V7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v14M8 9v2M8 13v2M16 9v2M16 13v2"/>
                </svg>
                <span>
                  <span v-if="user.city">{{ user.city }}</span>
                  <span v-if="user.state"> - {{ user.state }}</span>
                </span>
              </div>
              
              <div class="address-line" v-if="user.zipCode">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                  <polyline points="14,2 14,8 20,8"/>
                  <line x1="16" y1="13" x2="8" y2="13"/>
                  <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                <span class="zip-code">{{ user.zipCode }}</span>
              </div>
              
              <div class="address-line" v-if="user.country">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="12" r="10"/>
                  <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                  <path d="M2 12h20"/>
                </svg>
                <span>{{ user.country }}</span>
              </div>
            </div>
          </div>
          
          <div v-else class="no-address">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
              <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
              <circle cx="12" cy="10" r="3"/>
            </svg>
            <h5>Endereço não informado</h5>
            <p>Nenhuma informação de endereço foi cadastrada para este usuário.</p>
          </div>
        </div>

        <div class="details-section">
          <div class="section-header">
            <h4>Informações do Sistema</h4>
          </div>
          
          <div class="info-grid">
            <div class="info-item">
              <span class="info-label">ID do Usuário</span>
              <span class="info-value">{{ user.id }}</span>
            </div>
            
            <div class="info-item" v-if="user.createdAt">
              <span class="info-label">Data de Cadastro</span>
              <span class="info-value">{{ formatDate(user.createdAt) }}</span>
            </div>
            
            <div class="info-item" v-if="user.updatedAt">
              <span class="info-label">Última Atualização</span>
              <span class="info-value">{{ formatDate(user.updatedAt) }}</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="modal-footer">
        <button @click="closeModal" class="btn-close">
          Fechar
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import '@/assets/css/modal-user.css';
export default {
  name: 'ModalUserView',
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  computed: {
    hasAddress() {
      return this.user.street || this.user.number || this.user.complement || 
             this.user.neighborhood || this.user.city || this.user.state || 
             this.user.zipCode || this.user.country;
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
    closeModal() {
      this.$emit('close');
    },
    hideImage(event) {
      event.target.style.display = 'none';
    },
    formatDate(dateString) {
      if (!dateString) return 'N/A';
      try {
        return new Date(dateString).toLocaleDateString('pt-BR', {
          year: 'numeric',
          month: 'long',
          day: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        });
      } catch {
        return 'N/A';
      }
    }
  }
}
</script>