<template>
  <div class="app-container">
    <div class="tasks-header-content">
      <div class="tasks-header-title">Tarefas</div>
    </div>
    <div class="tasks-card">
      <div class="tasks-header-bar" style="justify-content: flex-end; gap: 1rem; margin-bottom: 1.5rem;">
        <button class="tasks-header-btn" @click="goDashboard">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:middle;"><polyline points="15 18 9 12 15 6"/><line x1="9" y1="12" x2="21" y2="12"/></svg>
          Voltar
        </button>
        <button class="tasks-header-btn" @click="logout">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:middle;"><path d="M17 16l4-4m0 0l-4-4m4 4H7"/><path d="M9 20H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h4"/></svg>
          Logout
        </button>
      </div>
      <!-- Campo de busca -->
      <div class="search-bar">
        <input v-model="searchTerm" class="search-input" placeholder="Buscar por título..." />
        <button class="search-btn" @click="searchTasks">Buscar</button>
      </div>
      <Tasks :tasks="tasks" @addTask="addTask" @editTask="editTask" @cancelTask="handleCancelTask" :isAdmin="user.role === 'isAdmin'" />
      <div v-if="showModal && foundTasks.length > 1" class="modal-overlay" @click="closeModal">
        <div class="modal-content task-modal-content custom-task-modal" @click.stop>
          <div class="modal-header custom-modal-header">
            <h2 class="custom-modal-title">Tarefas Encontradas</h2>
            <button class="close-button custom-close-btn" @click="closeModal">
              <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
            </button>
          </div>
          <div class="custom-modal-body" style="gap: 1.5rem;">
            <div v-for="task in foundTasks" :key="task.id" class="modal-field" style="flex-direction: column; align-items: flex-start; background: #f3f4f6; border-radius: 0.7rem; padding: 1rem 1.2rem; margin-bottom: 0.5rem; width: 100%;">
              <div><span class="modal-label">Título:</span> <span class="modal-value">{{ task.title }}</span></div>
              <div><span class="modal-label">Data de Vencimento:</span> <span class="modal-value">{{ formatDate(task.due_date) }}</span></div>
              <div><span class="modal-label">Status:</span> <span class="modal-value">{{ getStatusLabel(task.status) }}</span></div>
              <button class="search-btn" style="margin-top: 0.7rem;" @click="showTaskDetails(task)">Ver detalhes</button>
            </div>
          </div>
        </div>
      </div>
      <div v-if="showModal && foundTasks.length === 1 && selectedTask" class="modal-overlay" @click="closeModal">
        <div class="modal-content task-modal-content custom-task-modal" @click.stop>
          <div class="modal-header custom-modal-header">
            <h2 class="custom-modal-title">Detalhes da Tarefa</h2>
            <button class="close-button custom-close-btn" @click="closeModal">
              <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
            </button>
          </div>
          <div class="modal-body custom-modal-body">
            <div class="modal-field"><span class="modal-label">Título:</span> <span class="modal-value">{{ selectedTask.title }}</span></div>
            <div class="modal-field"><span class="modal-label">Descrição:</span> <span class="modal-value">{{ selectedTask.description }}</span></div>
            <div class="modal-field"><span class="modal-label">Data de Vencimento:</span> <span class="modal-value">{{ formatDate(selectedTask.due_date) }}</span></div>
            <div class="modal-field"><span class="modal-label">Status:</span> <span class="modal-value">{{ getStatusLabel(selectedTask.status) }}</span></div>
            <div class="modal-field"><span class="modal-label">Usuário:</span> <span class="modal-value">{{ selectedTask.user?.fullName || 'Desconhecido' }}</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import '@/assets/css/task.css';
import Tasks from '@/components/Tasks.vue';
import { useTasks } from '@/composables/useTasks';
import { useRouter } from 'vue-router';
import { useAuth } from '@/composables/useAuth';
import { onMounted, ref } from 'vue';

export default {
  name: 'TasksView',
  components: { Tasks },
  setup() {
    const { tasks, addTask, editTask, deleteTask, fetchTasks } = useTasks();
    const router = useRouter();
    const { logout: doLogout, user } = useAuth();
    const searchTerm = ref('');
    const showModal = ref(false);
    const foundTasks = ref([]);
    const selectedTask = ref(null);
    function goDashboard() {
      router.push('/dashboard');
    }
    function logout() {
      doLogout()
        .catch(() => {})
        .finally(() => {
          router.push({ path: '/login', query: { logoutSuccess: 1 } });
        });
    }
    function searchTasks() {
      const term = searchTerm.value.trim().toLowerCase();
      const taskList = Array.isArray(tasks) ? tasks : (tasks.value ? tasks.value : []);
      if (!term) {
        showModal.value = false;
        foundTasks.value = [];
        selectedTask.value = null;
        return;
      }
      const matches = taskList.filter(t => t.title && t.title.toLowerCase().includes(term));
      if (matches.length === 1) {
        foundTasks.value = [matches[0]];
        selectedTask.value = matches[0];
        showModal.value = true;
      } else if (matches.length > 1) {
        foundTasks.value = matches;
        selectedTask.value = null;
        showModal.value = true;
      } else {
        showModal.value = false;
        foundTasks.value = [];
        selectedTask.value = null;
      }
    }
    function showTaskDetails(task) {
      selectedTask.value = task;
      foundTasks.value = [task];
      showModal.value = true;
    }
    function closeModal() {
      showModal.value = false;
      foundTasks.value = [];
      selectedTask.value = null;
    }
    function formatDate(dateStr) {
      if (!dateStr) return '';
      const d = new Date(dateStr);
      if (isNaN(d)) return dateStr;
      const day = String(d.getDate()).padStart(2, '0');
      const month = String(d.getMonth() + 1).padStart(2, '0');
      const year = d.getFullYear();
      return `${day}/${month}/${year}`;
    }
    function getStatusLabel(status) {
      switch (status) {
        case 'pending': return 'Pendente';
        case 'in_progress': return 'Em andamento';
        case 'completed': return 'Concluída';
        case 'cancelled': return 'Cancelada';
        default: return status;
      }
    }
    function handleCancelTask(task) {
      if (!task) return;
      const updatedTask = { ...task, status: 'cancelled' };
      editTask(updatedTask);
    }
    onMounted(() => {
      fetchTasks();
    });
    return { tasks, addTask, editTask, deleteTask, goDashboard, logout, user, searchTerm, searchTasks, showModal, foundTasks, selectedTask, showTaskDetails, closeModal, formatDate, getStatusLabel, handleCancelTask };
  }
};
</script> 