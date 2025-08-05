<template>
  <div class="login-container">
    <transition name="fade">
      <div v-if="showLogoutSuccess" class="logout-success-pop">Logout Realizado com Sucesso!</div>
    </transition>
    <LoginForm @login="handleLogin" :loading="loading" :error="error">
      <template #footer>
        <div class="register-footer">
          <span class="register-message">Você ainda não tem cadastro?</span>
          <button class="register-link" @click="goToRegister" type="button">Cadastre-se</button>
        </div>
      </template>
    </LoginForm>
  </div>
</template>

<script>
import '@/assets/css/login.css';
import LoginForm from '@/components/LoginForm.vue';
import { useAuth } from '@/composables/useAuth';
import { useRouter, useRoute } from 'vue-router';
import { ref, onMounted } from 'vue';

export default {
  name: 'LoginView',
  components: { LoginForm },
  setup() {
    const { login, loading, error } = useAuth();
    const router = useRouter();
    const route = useRoute();
    const showLogoutSuccess = ref(false);
    const handleLogin = async (credentials) => {
      try {
        await login(credentials);
        // Se chegou aqui, o login foi bem-sucedido
        console.log('Login bem-sucedido, redirecionando para dashboard...');
        router.push({ path: '/dashboard', query: { loginSuccess: 1 } });
      } catch (e) {
        console.error('Erro no login:', e);
        // O erro já está sendo tratado no useAuth
      }
    };
    const goToRegister = () => {
      router.push('/register');
    };
    onMounted(() => {
      if (route.query.logoutSuccess) {
        showLogoutSuccess.value = true;
        setTimeout(() => {
          showLogoutSuccess.value = false;
        }, 2500);
        // Limpa o query param para não exibir novamente
        router.replace({ query: { ...route.query, logoutSuccess: undefined } });
      }
    });
    return { handleLogin, loading, error, showLogoutSuccess, goToRegister };
  }
};
</script> 