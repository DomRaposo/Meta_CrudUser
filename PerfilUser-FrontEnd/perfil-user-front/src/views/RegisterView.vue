<template>
  <div class="login-container">
    <div class="register-center-wrapper">
      <form @submit.prevent="onSubmit" class="login-card" enctype="multipart/form-data">
        <h1 class="login-title">Cadastro de Usuário</h1>
        <div class="form-group">
          <label class="form-label" for="fullName">Nome completo</label>
          <input class="form-input" id="fullName" v-model="fullName" type="text" required placeholder="Seu nome completo" />
        </div>
        <div class="form-group">
          <label class="form-label" for="age">Idade</label>
          <input class="form-input" id="age" v-model="age" type="number" min="0" required placeholder="Sua idade" />
        </div>
        <div class="form-group">
          <label class="form-label" for="email">E-mail</label>
          <input class="form-input" id="email" v-model="email" type="email" required placeholder="seu@email.com" />
        </div>
        <div class="form-group">
          <label class="form-label" for="password">Senha</label>
          <input class="form-input" id="password" v-model="password" type="password" required placeholder="••••••••" />
        </div>
        <div class="form-group">
          <label class="form-label" for="confirmPassword">Confirme sua senha</label>
          <input class="form-input" id="confirmPassword" v-model="confirmPassword" type="password" required placeholder="Repita a senha" />
        </div>
        <button class="login-button" type="submit" :disabled="loading">Cadastrar</button>
        <div style="height: 20px;"></div>
        <div v-if="error" class="login-error-pop login-error-pop-fixed">{{ error }}</div>
        <div v-if="success" class="register-success register-success-fixed">Usuario cadastrado com Sucesso!</div>
      </form>
    </div>
  </div>
</template>

<script>
import '@/assets/css/register.css';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

export default {
  name: 'RegisterView',
  setup() {
    const fullName = ref('');
    const age = ref('');
    const email = ref('');
    const password = ref('');
    const confirmPassword = ref('');
    const loading = ref(false);
    const error = ref('');
    const success = ref(false);
    const router = useRouter();

    const onSubmit = async () => {
      if (password.value !== confirmPassword.value) {
        error.value = 'As senhas não coincidem. Por favor, verifique e tente novamente.';
        return;
      }
      loading.value = true;
      error.value = '';
      try {
        const formData = new FormData();
        formData.append('fullName', fullName.value);
        formData.append('age', age.value);
        formData.append('email', email.value);
        formData.append('password', password.value);
        await axios.post('http://localhost:8000/api/users', formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
        success.value = true;
        setTimeout(() => {
          router.push('/login');
        }, 3000);
      } catch (e) {
        error.value = e.response?.data?.message || 'Erro ao cadastrar usuário';
      } finally {
        loading.value = false;
      }
    };

    return { fullName, age, email, password, confirmPassword, loading, error, success, onSubmit };
  }
};
</script> 