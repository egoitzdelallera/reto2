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
import { decodificarToken } from '../utils/decodificarToken'; // Asegúrate de que la ruta sea correcta

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

        const token = response.data.access_token;

        // Guardar el token JWT en el almacenamiento local
        localStorage.setItem('jwt_token', token);

        const userData = decodificarToken(token);

        if (userData) {
            console.log('Datos del usuario:', userData);

            const userToStore = {
                id: userData.id_usuario, // Usando el id del payload
                correo: userData.correo,  // Usando el correo del payload
                rol: userData.rol, // Usando el rol del payload
            };

            // Guardar los datos del usuario
            localStorage.setItem('user_data', JSON.stringify(userToStore));
        } else {
            console.error('Error al decodificar los datos del usuario');
        }

        // Establecer el token en las cabeceras de axios para futuras solicitudes
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        // Redirigir al usuario a la página de incidencias
        this.$router.push('/incidencias');
      } catch (error) {
        console.error('Error en el inicio de sesión:', error);
        alert('Correo o contraseña incorrectos');
      }
    },

    checkAuth() {
      const token = localStorage.getItem('jwt_token');
      if (token) {
        const userData = decodificarToken(token);
        if (userData) {
          axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
          this.$router.push('/incidencias');
        } else {
          console.error('Token no válido');
          localStorage.removeItem('jwt_token');
          this.$router.push('/');
        }
      }
    }
  },
  created() {
    this.checkAuth();
  }
};
</script>
