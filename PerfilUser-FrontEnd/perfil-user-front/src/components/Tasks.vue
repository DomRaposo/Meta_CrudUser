<template>
  <div class="tasks-list-container">
    <div class="list-header">
      <h2>Minhas Tarefas</h2>
      <!-- Formulário de adicionar tarefa -->
      <form v-if="isAdmin" class="add-task-form" @submit.prevent="handleAddTask">
        <input class="add-task-input" v-model="newTask.title" placeholder="Título da tarefa" required />
        <input class="add-task-input" v-model="newTask.description" placeholder="Descrição" required />
        <input class="add-task-input" v-model="newTask.dueDate" type="date" required />
        <select class="add-task-input" v-model="newTask.status" required>
          <option value="pending">Pendente</option>
          <option value="in_progress">Em andamento</option>
          <option value="completed">Concluída</option>
          <option value="cancelled">Cancelada</option>
        </select>
        <button class="add-task-btn" type="submit">Adicionar</button>
      </form>
    </div>
    <div class="table-wrapper">
      <table class="tasks-table">
        <thead>
          <tr>
            <th>Usuário</th>
            <th>Título</th>
            <th>Descrição</th>
            <th>Data de Vencimento</th>
            <th>Status</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="task in paginatedTasks" :key="task.id" class="task-row">
            <td :data-label="'Usuário'">
              {{ task.user?.fullName || task.user?.name || task.user?.email || task.user?.id || 'Desconhecido' }}
            </td>
            <td :data-label="'Título'">{{ task.title }}</td>
            <td :data-label="'Descrição'">{{ task.description }}</td>
            <td :data-label="'Data de Vencimento'">{{ formatDate(task.due_date) }}</td>
            <td :data-label="'Status'" class="status-cell">{{ getStatusLabel(task.status) }}</td>
            <td :data-label="'Ações'" class="actions-cell">
              <button v-if="task.status !== 'completed'" class="action-btn action-complete" @click="markAsCompleted(task)" title="Concluir">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="22" height="22">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
              </button>
              <button class="action-btn action-remove" @click="$emit('cancelTask', task)" title="Remover">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="22" height="22">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9.5 7V5.75A1.75 1.75 0 0 1 11.25 4h1.5A1.75 1.75 0 0 1 14.5 5.75V7m-7 0v10.25A2.25 2.25 0 0 0 9.75 19.5h4.5A2.25 2.25 0 0 0 16.5 17.25V7" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M10 11v4m4-4v4" />
                </svg>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="pagination" v-if="totalPages > 1">
      <button class="tasks-header-btn" @click="prevPage" :disabled="currentPage === 1" :style="{ marginRight: '0.7rem', opacity: currentPage === 1 ? 0.6 : 1 }">Anterior</button>
      <span style="font-weight: 600; color: #6366f1; margin: 0 0.7rem;">Página {{ currentPage }} de {{ totalPages }}</span>
      <button class="tasks-header-btn" @click="nextPage" :disabled="currentPage === totalPages" :style="{ marginLeft: '0.7rem', opacity: currentPage === totalPages ? 0.6 : 1 }">Próxima</button>
    </div>
    <div v-if="tasks.length === 0" class="empty-state">
      <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
        <circle cx="12" cy="7" r="4"/>
      </svg>
      <h3>Nenhuma tarefa cadastrada</h3>
      <p>Adicione uma nova tarefa para começar.</p>
    </div>
    <!-- Modal de edição de tarefa -->
    <div v-if="editModalOpen" class="modal-overlay" @click="closeEditModal">
      <div class="modal-content task-modal-content" @click.stop>
        <div class="modal-header">
          <h2>Editar Tarefa</h2>
          <button class="close-button" @click="closeEditModal">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>
        <form @submit.prevent="submitEditTask" class="modal-body">
          <div class="form-section">
            <h3 class="section-title">Informações da Tarefa</h3>
            <div class="section-divider"></div>
            <div class="form-group">
              <label for="edit-title">Título</label>
              <input id="edit-title" v-model="editTaskData.title" type="text" required placeholder="Título da tarefa" class="modal-input" />
            </div>
            <div class="form-group">
              <label for="edit-description">Descrição</label>
              <input id="edit-description" v-model="editTaskData.description" type="text" required placeholder="Descrição" class="modal-input" />
            </div>
            <div class="form-group">
              <label for="edit-dueDate">Data de Vencimento</label>
              <input id="edit-dueDate" v-model="editTaskData.dueDate" type="date" required class="modal-input" />
            </div>
            <div class="form-group">
              <label for="edit-status">Status</label>
              <select id="edit-status" v-model="editTaskData.status" required class="modal-input">
                <option value="pending">Pendente</option>
                <option value="in_progress">Em andamento</option>
                <option value="completed">Concluída</option>
                <option value="cancelled">Cancelada</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" @click="closeEditModal" class="btn-secondary">Cancelar</button>
            <button type="submit" class="btn-primary">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import '@/assets/css/task.css';
import '@/assets/css/modal-task.css';
export default {
  name: 'AppTasks',
  props: {
    tasks: {
      type: Array,
      required: true
    },
    isAdmin: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      newTask: {
        title: '',
        description: '',
        dueDate: '',
        status: 'pending'
      },
      editModalOpen: false,
      editTaskData: {
        id: null,
        title: '',
        description: '',
        dueDate: '',
        status: 'pending'
      },
      currentPage: 1,
      itemsPerPage: 5
    };
  },
  computed: {
    paginatedTasks() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.tasks.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.tasks.length / this.itemsPerPage) || 1;
    }
  },
  methods: {
    handleAddTask() {
      if (!this.newTask.title || !this.newTask.description || !this.newTask.dueDate || !this.newTask.status) {
        alert('Preencha todos os campos obrigatórios!');
        return;
      }
      this.$emit('addTask', { ...this.newTask });
      this.newTask.title = '';
      this.newTask.description = '';
      this.newTask.dueDate = '';
      this.newTask.status = 'pending';
    },
    openEditModal(task) {
      this.editTaskData = { ...task };
      this.editModalOpen = true;
    },
    closeEditModal() {
      this.editModalOpen = false;
    },
    submitEditTask() {
      this.$emit('editTask', { ...this.editTaskData });
      this.closeEditModal();
    },
    markAsCompleted(task) {
      this.$emit('editTask', { ...task, status: 'completed' });
    },
    getStatusLabel(status) {
      switch (status) {
        case 'pending': return 'Pendente';
        case 'in_progress': return 'Em andamento';
        case 'completed': return 'Concluída';
        case 'cancelled': return 'Cancelada';
        default: return status;
      }
    },
    formatDate(dateStr) {
      if (!dateStr) return '';
      const d = new Date(dateStr);
      if (isNaN(d)) return dateStr;
      const day = String(d.getDate()).padStart(2, '0');
      const month = String(d.getMonth() + 1).padStart(2, '0');
      const year = d.getFullYear();
      return `${day}/${month}/${year}`;
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    }
  }
};
</script> 