import { ref } from 'vue';

export function useUserSearch(users) {
  const searchTerm = ref('');
  const showModal = ref(false);
  const foundUsers = ref([]);
  const selectedUser = ref(null);

  function searchUsers() {
    const term = searchTerm.value.trim().toLowerCase();
    const userList = Array.isArray(users) ? users : (users.value ? users.value : []);
    
    if (!term) {
      showModal.value = false;
      foundUsers.value = [];
      selectedUser.value = null;
      return;
    }
    
    const matches = userList.filter(u => u.fullName && u.fullName.toLowerCase().includes(term));
    
    if (matches.length === 1) {
      foundUsers.value = [matches[0]];
      selectedUser.value = matches[0];
      showModal.value = true;
    } else if (matches.length > 1) {
      foundUsers.value = matches;
      selectedUser.value = null;
      showModal.value = true;
    } else {
      showModal.value = false;
      foundUsers.value = [];
      selectedUser.value = null;
    }
  }

  function showUserDetails(user) {
    selectedUser.value = user;
    foundUsers.value = [user];
    showModal.value = true;
  }

  function closeModal() {
    showModal.value = false;
    foundUsers.value = [];
    selectedUser.value = null;
  }

  return {
    searchTerm,
    showModal,
    foundUsers,
    selectedUser,
    searchUsers,
    showUserDetails,
    closeModal
  };
} 