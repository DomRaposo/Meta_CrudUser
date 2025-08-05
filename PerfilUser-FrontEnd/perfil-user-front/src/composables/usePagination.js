import { ref, computed } from 'vue';

export function usePagination(users) {
  const currentPage = ref(1);
  const itemsPerPage = 5;

  const paginatedUsers = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return users.value.slice(start, end);
  });

  const totalPages = computed(() => {
    return Math.ceil(users.value.length / itemsPerPage) || 1;
  });

  function nextPage() {
    if (currentPage.value < totalPages.value) {
      currentPage.value++;
    }
  }

  function prevPage() {
    if (currentPage.value > 1) {
      currentPage.value--;
    }
  }

  return {
    currentPage,
    totalPages,
    paginatedUsers,
    nextPage,
    prevPage
  };
} 