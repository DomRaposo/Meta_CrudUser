<template>
  <div class="dashboard-container">
    <div class="dashboard-header-content">
      <div class="dashboard-header-title">Dashboard</div>
      <div class="dashboard-header-user">Bem-vindo, {{ nomeUsuario }}!</div>
    </div>
    <transition name="fade">
      <div v-if="showSuccess" class="dashboard-success-pop">Login realizado com sucesso.</div>
    </transition>
    <Dashboard :user="user" @logout="logout" @manageUsers="goUsers" />
  </div>
</template>

<script>
import '@/assets/css/dashboard.css';
import Dashboard from '@/components/Dashboard.vue';
import { useAuth } from '@/composables/useAuth';
import { useRouter, useRoute } from 'vue-router';
import { ref, onMounted, computed } from 'vue';

export default {
  name: 'DashboardView',
  components: { Dashboard },
  setup() {
    // eslint-disable-next-line no-unused-vars
    const { user, logout: doLogout } = useAuth();
    const router = useRouter();
    const route = useRoute();
    const showSuccess = ref(false);
    const nomeUsuario = computed(() => user?.fullName || user?.name || 'Usuário');
    
    function logout() {
      doLogout()
        .catch(() => {})
        .finally(() => {
          router.push({ path: '/login', query: { logoutSuccess: 1 } });
        });
    }
    function goUsers() {
      router.push('/users');
    }
    onMounted(() => {
      if (route.query.loginSuccess) {
        showSuccess.value = true;
        setTimeout(() => {
          showSuccess.value = false;
        }, 2500);
        router.replace({ query: { ...route.query, loginSuccess: undefined } });
      }
    });
    return { user, logout, showSuccess, nomeUsuario, goUsers };
  }
};
</script> 
