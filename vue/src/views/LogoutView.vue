<template>
  <div class="logout-view">
    <p>Cerrando sesión...</p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'LogoutView',
  async created() {
    try {
      // Llamar al backend para cerrar sesión
      await axios.post(
        'http://localhost:8000/api/logout',
        {},
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('jwt_token')}`,
          },
        }
      );

      // Eliminar datos del localStorage
      localStorage.removeItem('jwt_token');
      localStorage.removeItem('user_data');

      // Redirigir al usuario a la página de inicio de sesión
      this.$router.push('/');
    } catch (error) {
      console.error('Error al cerrar sesión:', error.response?.data || error);
      alert('No se pudo cerrar la sesión. Intente nuevamente.');

      // Incluso si falla, elimina los datos locales y redirige al login
      localStorage.removeItem('jwt_token');
      localStorage.removeItem('user_data');
      this.$router.push('/login');
    }
  },
};
</script>

<style scoped>
.logout-view {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  font-size: 1.5rem;
  color: #555;
}
</style>
