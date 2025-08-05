<template>
  <div class="pagination">
    <button 
      class="tasks-header-btn" 
      @click="$emit('prev-page')" 
      :disabled="currentPage === 1" 
      :style="{ marginRight: '0.7rem', opacity: currentPage === 1 ? 0.6 : 1 }"
    >
      Anterior
    </button>
    <span style="font-weight: 600; color: #6366f1; margin: 0 0.7rem;">
      Página {{ currentPage }} de {{ totalPages }}
    </span>
    <button 
      class="tasks-header-btn" 
      @click="$emit('next-page')" 
      :disabled="currentPage === totalPages" 
      :style="{ marginLeft: '0.7rem', opacity: currentPage === totalPages ? 0.6 : 1 }"
    >
      Próxima
    </button>
  </div>
</template>

<script>
export default {
  name: 'UsersPagination',
  props: {
    currentPage: {
      type: Number,
      required: true
    },
    totalPages: {
      type: Number,
      required: true
    }
  },
  emits: ['prev-page', 'next-page']
};
</script> 