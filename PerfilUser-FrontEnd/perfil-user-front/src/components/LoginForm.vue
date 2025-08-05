<template>
  <div>
    <transition name="fade">
      <div v-if="error" class="login-error-pop">{{ error }}</div>
    </transition>
    <form @submit.prevent="onSubmit" class="login-card">
      <h1 class="login-title">Bem-vindo</h1>
      <p class="login-subtitle">Entre com seu e-mail e senha</p>
      <div class="form-group">
        <label class="form-label" for="email">E-mail</label>
        <input class="form-input" id="email" type="email" v-model="email" required placeholder="seu@email.com" />
      </div>
      <div class="form-group">
        <label class="form-label" for="password">Senha</label>
        <input class="form-input" id="password" type="password" v-model="password" required placeholder="••••••••" />
      </div>
      <button class="login-button" type="submit">Entrar</button>
      <div class="login-footer">
        <slot name="footer"></slot>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  name: 'LoginForm',
  props: {
    loading: Boolean,
    error: String
  },
  data() {
    return {
      email: '',
      password: ''
    };
  },
  methods: {
    onSubmit() {
      this.$emit('login', { email: this.email, password: this.password });
    }
  }
};
</script>

<style src="@/assets/css/login.css"></style> 