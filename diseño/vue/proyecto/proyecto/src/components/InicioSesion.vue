<template>
    <div class="login-container">
      <form @submit.prevent="handleLogin" class="login-form">
        <h2>Iniciar Sesión</h2>
        <div class="form-group">
          <label for="username">Usuario:</label>
          <input type="text" id="username" v-model="username" required>
        </div>
        <div class="form-group">
          <label for="password">Contraseña:</label>
          <input type="password" id="password" v-model="password" required>
        </div>
        <a href="">entrar</a>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import { useRouter } from 'vue-router'
  
  const username = ref('')
  const password = ref('')
  const router = useRouter()
  
  const handleLogin = () => {
    // Here you would typically validate the credentials
    // For this example, we'll just redirect to the home page
    router.push('/')
  }
  // Asegúrate de que la ruta sea correcta

  </script>
  
  <style scoped>
  
  </style>