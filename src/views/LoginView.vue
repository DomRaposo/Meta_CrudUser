<template>
  <div class="login-container">
    <LoginForm @submit="handleLogin">
      <template #footer>
        <span v-if="error" style="color: #f87171">{{ error }}</span>
      </template>
    </LoginForm>
  </div>
</template>

<script setup>
import LoginForm from '@/components/LoginForm.vue';
import { useAuth } from '@/composables/useAuth';
import { ref } from 'vue';

const { login } = useAuth();
const error = ref('');

async function handleLogin({ email, password }) {
  error.value = '';
  const result = await login(email, password);
  if (!result.success) {
    error.value = result.message;
  }
}
</script> 