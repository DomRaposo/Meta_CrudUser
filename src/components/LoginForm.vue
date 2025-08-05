<template>
  <form @submit.prevent="onSubmit" class="login-card">
    <h2 class="login-title">Bem-vindo</h2>
    <p class="login-subtitle">Entre com seu e-mail e senha</p>
    <div class="form-group">
      <label class="form-label" for="email">Email</label>
      <input class="form-input" id="email" v-model="email" type="email" required placeholder="seu@email.com" />
    </div>
    <div class="form-group">
      <label class="form-label" for="password">Senha</label>
      <input class="form-input" id="password" v-model="password" type="password" required placeholder="••••••••" />
    </div>
    <button class="login-button" type="submit">Entrar</button>
    <div class="login-footer">
      <slot name="footer"></slot>
    </div>
  </form>
</template>

<script setup>
import { ref } from 'vue';
const emit = defineEmits(['submit']);
const email = ref('');
const password = ref('');
function onSubmit() {
  emit('submit', { email: email.value, password: password.value });
}
</script>

<style src="@/assets/css/login.css"></style> 