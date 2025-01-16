<template>
  <div class="d-flex min-vh-100 align-items-center justify-content-center aling-items-center bg-light">
    <div class="card p-4 shadow-lg" style="max-width: 400px; width: 100%;">
      <div class="text-center">
        <h2 class="mt-3 mb-4">Iniciar Sesión</h2>
      </div>
      <form @submit.prevent="handleLogin">
        <div class="mb-3">
          <label for="username" class="form-label">Usuario</label>
          <input
            id="username"
            name="username"
            type="text"
            required
            class="form-control"
            placeholder="Usuario"
          />
          
          <div>
            <label for="password" class="sr-only">Contraseña</label>
            <input
              id="password"
              name="password"
              type="password"
              required
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Contraseña"
            />
          </div>
        </div>

        <div>
          <button
            type="submit"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Entrar
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const handleLogin = () => {
  // Navegar a la página de incidentes utilizando el path
  router.push('/incidencias')
}
</script>
