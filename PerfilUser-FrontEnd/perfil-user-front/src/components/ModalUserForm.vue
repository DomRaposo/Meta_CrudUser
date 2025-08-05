<template>
  <div class="modal-overlay" @click="closeModal">
    <div class="modal-content" @click.stop>
      <div class="modal-header">
        <h2>{{ isEdit ? 'Editar' : 'Criar' }} Usuário</h2>
        <button class="close-button" @click="closeModal">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>
      <form @submit.prevent="submitForm" class="modal-body">
        <div class="form-section">
          <div class="form-group">
            <label for="fullName">Nome Completo</label>
            <input
              id="fullName"
              v-model="formData.fullName"
              type="text"
              required
              placeholder="Digite seu nome completo"
            />
          </div>
          <div class="form-group">
            <label for="age">Idade</label>
            <input
              id="age"
              v-model="formData.age"
              type="number"
              min="0"
              required
              placeholder="Digite sua idade"
            />
          </div>
          <div class="form-group">
            <label for="profile_image">Foto de Perfil (URL)</label>
            <input
              id="profile_image"
              v-model="formData.profile_image"
              type="url"
              placeholder="https://exemplo.com/sua-foto.jpg"
            />
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input
              id="email"
              v-model="formData.email"
              type="email"
              required
              placeholder="Digite seu email"
            />
          </div>
          <div class="form-group">
            <label for="password">{{ isEdit ? 'Nova Senha (opcional)' : 'Senha' }}</label>
            <input
              id="password"
              v-model="formData.password"
              type="password"
              :required="!isEdit"
              placeholder="Digite sua senha"
            />
          </div>
          <div class="form-group">
            <label for="confirmPassword">Confirmar Senha</label>
            <input
              id="confirmPassword"
              v-model="formData.confirmPassword"
              type="password"
              :required="!isEdit"
              placeholder="Confirme sua senha"
            />
          </div>
          <div class="form-group">
            <label for="role">Tipo de Usuário</label>
            <select id="role" v-model="formData.role" required>
              <option value="user">Usuário</option>
              <option value="isAdmin">Administrador</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <div v-if="passwordError" style="color: #dc2626; margin-bottom: 0.7rem; font-weight: 600;">{{ passwordError }}</div>
          <button type="button" @click="closeModal" class="btn-secondary">
            Cancelar
          </button>
          <button type="submit" class="btn-primary">
            {{ isEdit ? 'Atualizar' : 'Criar' }} Usuário
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import '@/assets/css/modal-user-form.css';
export default {
  name: 'ModalUserForm',
  props: {
    isEdit: {
      type: Boolean,
      default: false
    },
    user: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      formData: {
        fullName: '',
        age: '',
        profile_image: '',
        email: '',
        password: '',
        role: 'user',
        confirmPassword: ''
      },
      passwordError: ''
    }
  },
  watch: {
    user: {
      handler(newUser) {
        if (newUser && Object.keys(newUser).length > 0) {
          this.formData = {
            fullName: newUser.fullName || '',
            age: newUser.age || '',
            profile_image: newUser.profile_image || '',
            email: newUser.email || '',
            password: '',
            role: newUser.role || 'user',
            confirmPassword: ''
          }
        } else {
          this.resetForm()
        }
      },
      immediate: true
    }
  },
  methods: {
    resetForm() {
      this.formData = {
        fullName: '',
        age: '',
        profile_image: '',
        email: '',
        password: '',
        role: 'user',
        confirmPassword: ''
      }
    },
    closeModal() {
      this.$emit('close')
    },
    submitForm() {
      const userData = { ...this.formData }
      // Validação de confirmação de senha
      if (!this.isEdit && userData.password !== userData.confirmPassword) {
        this.passwordError = 'As senhas não coincidem.';
        return;
      } else {
        this.passwordError = '';
      }
      if (this.isEdit && !userData.password) {
        delete userData.password
      }
      delete userData.confirmPassword;
      // Adiciona o id ao editar
      if (this.isEdit && this.user && this.user.id) {
        userData.id = this.user.id;
      }
      this.$emit('submit', userData)
    }
  }
}
</script>
