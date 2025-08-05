import axios from 'axios';

export async function loginApi(email, password) {
  // Ajuste a URL para o endpoint real do seu backend
  const response = await axios.post('/api/login', { email, password });
  // Espera-se que o backend retorne { user, token }
  return response.data;
} 