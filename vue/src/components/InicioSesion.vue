<template>
  <div class="d-flex min-vh-100 align-items-center justify-content-center bg-light">
    <div class="card p-4 shadow-lg" style="max-width: 400px; width: 100%;">
      <div class="text-center">
        <h2 class="mt-3 mb-4">Iniciar Sesión</h2>
      </div>
      <form @submit.prevent="handleLogin">
        <div class="mb-3">
          <label for="correo" class="form-label">Correo</label>
          <input
            id="correo"
            name="correo"
            type="email"
            v-model="correo"
            required
            class="form-control"
            placeholder="Correo"
          />
        </div>
        
        <div class="mb-3">
          <label for="contrasena" class="form-label">Contraseña</label>
          <input
            id="contrasena"
            name="contrasena"
            type="password"
            v-model="contrasena"
            required
            class="form-control"
            placeholder="Contraseña"
          />
        </div>

        <div>
          <button
            type="submit"
            class="btn btn-primary w-100"
          >
            Entrar
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from '../axios'; // Importa la instancia de axios configurada si tienes un archivo axios.js

export default {
  data() {
    return {
      correo: '',
      contrasena: '',
    };
  },
  methods: {
    async handleLogin() {
      try {
        // Realizar la solicitud de login a la API de Laravel
        const response = await axios.post('login', {
          correo: this.correo,
          password: this.contrasena, // Debe coincidir con el campo de Laravel 'password'
        });

        // Guardar el token JWT en el almacenamiento local
        localStorage.setItem('jwt_token', response.data.access_token);

        // Establecer el token en las cabeceras de axios para futuras solicitudes
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`;

        // Redirigir al usuario a la página de incidencias
        this.$router.push('/incidencias');
      } catch (error) {
        console.error('Error en el inicio de sesión:', error);
        alert('Correo o contraseña incorrectos');
      }
    }
  }
};
</script>
