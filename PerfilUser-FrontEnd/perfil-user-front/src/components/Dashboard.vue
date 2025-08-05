<template>
  <div class="dashboard-card">
    <h1 class="dashboard-title">Olá, {{ nomeUsuario }}!</h1>
    <div class="dashboard-info">
      Bem-vindo ao seu painel. Aqui você pode acessar suas tarefas e informações.
    </div>
    <div class="dashboard-actions">
      <button class="dashboard-action-btn" @click="$emit('manageUsers')">Gerenciar Usuários</button>
      <button class="dashboard-action-btn" @click="$emit('goTasks')">Ver Tarefas</button>
      <button class="dashboard-action-btn" @click="$emit('logout')">Logout</button>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue';
export default {
  name: 'AppDashboard',
  props: {
    user: {
      type: Object,
      required: false,
      default: () => ({})
    }
  },
  setup(props) {
    const nomeUsuario = computed(() => props.user.fullName || 'Usuário');
    return { nomeUsuario };
  }
};
</script>

<style src="@/assets/css/dashboard.css"></style> 