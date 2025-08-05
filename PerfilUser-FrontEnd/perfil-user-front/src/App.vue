<template>
  <router-view />
</template>

<script>
export default {
  name: 'App'
};
</script>

<style>
/* Estilos globais */
body {
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen',
    'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue',
    sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

/* Transições globais */
* {
  transition: all 0.2s ease-in-out;
}

/* Scrollbar personalizada */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

.app-container {
  min-height: 100vh;
  background: #f8fafc;
}

.dashboard {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.main-content {
  flex: 1;
  background: #f8fafc;
  padding: 2rem 0;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

.dashboard-header {
  margin-bottom: 2rem;
}

.header-info {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.header-text {
  display: flex;
  flex-direction: column;
}

.dashboard-title {
  font-size: 1.875rem;
  font-weight: 700;
  color: #1f2937;
  margin: 0;
}

.dashboard-subtitle {
  margin: 0.5rem 0 0;
  color: #6b7280;
}

.header-actions {
  display: flex;
  gap: 1rem;
}

.btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  text-decoration: none;
}

.btn-primary {
  background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
}

.btn-primary:hover {
  background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
  box-shadow: 0 6px 20px rgba(59, 130, 246, 0.6);
  transform: translateY(-2px);
}

.btn-icon {
  width: 1.25rem;
  height: 1.25rem;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: white;
  border-radius: 0.75rem;
  padding: 1.5rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.stat-content {
  display: flex;
  align-items: center;
}

.stat-icon {
  flex-shrink: 0;
}

.icon-container {
  width: 2rem;
  height: 2rem;
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.icon-blue {
  background: #3b82f6;
}

.icon-green {
  background: #10b981;
}

.icon-purple {
  background: #8b5cf6;
}

.icon-container .icon {
  width: 1.25rem;
  height: 1.25rem;
  color: white;
}

.stat-info {
  margin-left: 1.25rem;
  min-width: 0;
  flex: 1;
}

.stat-label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #6b7280;
  text-transform: none;
  margin: 0;
}

.stat-value {
  font-size: 1.125rem;
  font-weight: 600;
  color: #1f2937;
  margin: 0;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.profile-modal {
  background: white;
  border-radius: 0.75rem;
  padding: 1.5rem;
  max-width: 500px;
  width: 90%;
  margin: 1rem;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.profile-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.5rem;
}

.profile-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
  margin: 0;
}

.profile-close {
  background: none;
  border: none;
  color: #6b7280;
  cursor: pointer;
  padding: 0.25rem;
  border-radius: 0.25rem;
  transition: color 0.2s ease;
}

.profile-close:hover {
  color: #374151;
}

.profile-close .icon {
  width: 1.5rem;
  height: 1.5rem;
}

.profile-content {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.profile-field {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.profile-label {
  font-size: 0.875rem;
  font-weight: 600;
  color: #374151;
}

.profile-value {
  font-size: 0.875rem;
  color: #1f2937;
  margin: 0;
}

.profile-id {
  font-family: 'Courier New', monospace;
  font-size: 0.75rem;
  color: #6b7280;
}

.profile-footer {
  margin-top: 1.5rem;
  display: flex;
  justify-content: flex-end;
}

.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.loading-modal {
  background: white;
  border-radius: 0.75rem;
  padding: 2rem;
  max-width: 24rem;
  width: 90%;
  margin: 1rem;
  text-align: center;
}

.loading-spinner {
  width: 3rem;
  height: 3rem;
  color: #3b82f6;
  margin: 0 auto 1rem;
  animation: spin 1s linear infinite;
}

.loading-text {
  color: #6b7280;
  margin: 0;
}

.toast-container {
  position: fixed;
  bottom: 1rem;
  right: 1rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  z-index: 1000;
}

.toast {
  max-width: 24rem;
  background: white;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  border-radius: 0.5rem;
  pointer-events: auto;
  border: 1px solid rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.toast-success {
  border-left: 4px solid #10b981;
}

.toast-error {
  border-left: 4px solid #ef4444;
}

.toast-info {
  border-left: 4px solid #3b82f6;
}

.toast-content {
  padding: 1rem;
  display: flex;
  align-items: flex-start;
}

.toast-icon {
  flex-shrink: 0;
}

.toast-icon .icon {
  width: 1.5rem;
  height: 1.5rem;
}

.icon-success {
  color: #10b981;
}

.icon-error {
  color: #ef4444;
}

.icon-info {
  color: #3b82f6;
}

.toast-text {
  margin-left: 0.75rem;
  min-width: 0;
  flex: 1;
}

.toast-title {
  font-size: 0.875rem;
  font-weight: 600;
  color: #1f2937;
  margin: 0;
}

.toast-message {
  margin: 0.25rem 0 0;
  font-size: 0.875rem;
  color: #6b7280;
}

.toast-close {
  margin-left: 1rem;
  flex-shrink: 0;
  display: flex;
}

.close-btn {
  background: none;
  border: none;
  color: #9ca3af;
  cursor: pointer;
  padding: 0.25rem;
  border-radius: 0.25rem;
  transition: color 0.2s ease;
}

.close-btn:hover {
  color: #6b7280;
}

.close-btn .icon {
  width: 1.25rem;
  height: 1.25rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.opacity-25 {
  opacity: 0.25;
}

.opacity-75 {
  opacity: 0.75;
}

/* Classes utilitárias */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

@media (max-width: 768px) {
  .container {
    padding: 0 0.5rem;
  }
  
  .header-info {
    flex-direction: column;
    gap: 1rem;
  }
  
  .header-actions {
    width: 100%;
    justify-content: center;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
    gap: 1rem;
  }
  
  .stat-card {
    padding: 1rem;
  }
  
  .main-content {
    padding: 1rem 0;
  }
  
  .dashboard-header {
    margin-bottom: 1rem;
  }
  
  .dashboard-title {
    font-size: 1.5rem;
  }
  
  .profile-modal {
    margin: 0.5rem;
  }
  
  .loading-modal {
    margin: 0.5rem;
  }
  
  .toast-container {
    bottom: 0.5rem;
    right: 0.5rem;
    left: 0.5rem;
  }
  
  .toast {
    max-width: none;
  }
}
</style>
