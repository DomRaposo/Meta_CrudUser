import { ref } from 'vue';
import axios from 'axios';

export function useTasks() {
  const tasks = ref([]);
  const loading = ref(false);
  const error = ref(null);

  async function fetchTasks() {
    loading.value = true;
    try {
      const response = await axios.get('http://localhost:8000/api/tasks');
      tasks.value = response.data;
    } catch (e) {
      error.value = e.response?.data?.message || 'Erro ao buscar tarefas';
      console.error(error.value);
    } finally {
      loading.value = false;
    }
  }

  async function addTask({ title, description, dueDate, status }) {
    try {
      const response = await axios.post('http://localhost:8000/api/tasks', {
        title,
        description,
        due_date: dueDate, // corrigido para snake_case
        status // agora recebido do formulário
      });
      let task = response.data.task;
      // Garante que o campo user está presente para exibição imediata
      if (!task.user) {
        const user = JSON.parse(localStorage.getItem('user'));
        if (user) {
          task = { ...task, user: { fullName: user.fullName } };
        }
      }
      tasks.value.push(task);
    } catch (e) {
      error.value = e.response?.data?.message || 'Erro ao adicionar tarefa';
      console.error(error.value);
    }
  }

  async function editTask(editedTask) {
    try {
      const response = await axios.put(`http://localhost:8000/api/tasks/${editedTask.id}`, editedTask);
      const idx = tasks.value.findIndex(t => t.id === editedTask.id);
      if (idx !== -1) {
        tasks.value.splice(idx, 1, response.data.task); // garante reatividade
      }
    } catch (e) {
      error.value = e.response?.data?.message || 'Erro ao editar tarefa';
      console.error(error.value);
    }
  }

  async function deleteTask(id) {
    try {
      await axios.delete(`http://localhost:8000/api/tasks/${id}`);
      tasks.value = tasks.value.filter(t => t.id !== id);
    } catch (e) {
      error.value = e.response?.data?.message || 'Erro ao remover tarefa';
      console.error(error.value);
    }
  }

  return { tasks, loading, error, fetchTasks, addTask, editTask, deleteTask };
} 