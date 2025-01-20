<template>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <div class="sidebar p-2" :class="{ 'collapsed': isCollapsed }">
    <h1 class="mb-4 text-center">
      <a href="#" class="logo">
        <span class="logo-full">NovaByte</span>
        <span class="logo-short">N.</span>
      </a>
    </h1>
    <nav class="nav flex-column">
      <div v-for="(item, index) in menuItems" :key="index" class="nav-item"
           @mouseenter="showSubmenu(index)"
           @mouseleave="hideSubmenu(index)">
        <a href="#" class="nav-link" @click.prevent="navigateTo(item.route)">
          <i :class="item.icon" ></i>
          <span class="nav-link-text">{{ item.title }}</span>
        </a>
        <div v-if="item.submenu" class="submenu" :class="{ 'show': item.isHovered }">
          <h2 class="submenu-title">{{ item.submenuTitle }}</h2>
          <p class="submenu-subtitle">{{ item.submenuSubtitle }}</p>
          <h3 class="submenu-section-title">{{ item.sectionTitle }}</h3>
          <div class="workshops-container">
            <div v-for="(subItem, subIndex) in item.submenu" :key="subIndex" class="item-card" @click="navigateTo(subItem.route)">
              <span>{{ subItem.name }}</span>
              <span v-if="subItem.active" class="activity-dot"></span>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import '../assets/css/Nav.css';
import { ref, computed, onMounted } from 'vue';
import useTalleres from '@/composables/useTalleres';

const router = useRouter();
const { talleres, loadTalleres } = useTalleres();
const isCollapsed = ref(true);
const activeSubmenu = ref(null);
const submenuTimeout = ref(null);

const navigateTo = (route) => {
  if (route) {
    router.push(route);
  }
};

const menuItems = computed(() => [
  {
      title: 'Incidencias',
      icon: 'bi bi-shield',
      route: '/incidencias',
      submenuTitle: 'Gestión de Incidencias',
      submenuSubtitle: 'Selecciona una sección.',
      sectionTitle: 'Incidencias Activas',
      isHovered: ref(false),
      submenu: [
        { name: 'Todos los Talleres', active: true, route: '/incidencias' },
        ...(talleres.value || []).map(taller => ({
          name: taller.nombre,
          active: true,
           route: `/incidencias/taller/${taller.id_taller}`
        })),
      ]
    },
    {
      title: 'Mantenimientos',
      icon: 'bi bi-calendar-event',
      route: '/mantenimientos',
      submenuTitle: 'Talleres de Mekatrónica',
      submenuSubtitle: 'Selecciona el taller.',
      sectionTitle: 'Talleres',
      isHovered: ref(false),
      submenu: [
        { name: 'Todos los Talleres', active: true, route: '/mantenimientos' },
        ...(talleres.value || []).map(taller => ({
              name: taller.nombre,
             active: true,
            route: `/mantenimientos/taller/${taller.id_taller}`
           })),
      ]
    },
    {
        title: 'Máquinas',
        icon: 'bi bi-pc-display-horizontal',
        route: '/maquinas',
        submenuTitle: 'Inventario de Máquinas',
        submenuSubtitle: 'Gestión de equipamiento.',
        sectionTitle: 'Equipos',
        isHovered: ref(false),
        submenu: [
            { name: 'Activas', active: true, route: '/maquinas/activas' },
            { name: 'Mantenimiento', active: true, route: '/maquinas/mantenimiento' },
            { name: 'Historial', active: false, route: '/maquinas/historial' },
            { name: 'Inventario', active: false, route: '/maquinas/inventario' }
        ]
        },
       {
        title: 'Usuarios',
        icon: 'bi bi-people',
        submenuTitle: 'Gestión de Usuarios',
        submenuSubtitle: 'Administración de accesos.',
         sectionTitle: 'Usuarios del Sistema',
         isHovered: ref(false),
        submenu: [
          { name: 'Todos', active: false, route: '/usuarios' },
         { name: 'Administradores', active: false, route: '/usuarios/administradores' },
            { name: 'Técnicos', active: false, route: '/usuarios/tecnicos' },
           { name: 'Operarios', active: false, route: '/usuarios/operarios' },
          ]
       },
       {
        title: 'Perfil',
        icon: 'bi bi-person',
        route: '/perfil',
        submenuTitle: 'Mi Perfil',
         submenuSubtitle: 'Gestión de cuenta.',
        sectionTitle: 'Configuración',
          isHovered: ref(false),
        submenu: [
           { name: 'Datos Personales', active: false, route: '/perfil/datos-personales' },
            { name: 'Preferencias', active: false, route: '/perfil/preferencias' },
            { name: 'Seguridad', active: false, route: '/perfil/seguridad' },
             { name: 'Notificaciones', active: false, route: '/perfil/notificaciones' }
          ]
        },
        {
            title: 'Ayuda',
            icon: 'bi bi-info-circle',
            route: '/ayuda',
            submenuTitle: 'Centro de Ayuda',
           submenuSubtitle: 'Soporte y recursos.',
            sectionTitle: 'Recursos',
            isHovered: ref(false),
           submenu: [
             { name: 'Guías', active: false, route: '/ayuda/guias' },
              { name: 'FAQ', active: false, route: '/ayuda/faq' },
              { name: 'Contacto', active: false, route: '/ayuda/contacto' },
              { name: 'Reportar Error', active: false, route: '/ayuda/reportar-error' }
             ]
        }
   ]);

   const showSubmenu = (index) => {
  clearTimeout(submenuTimeout.value);
  submenuTimeout.value = setTimeout(() => {
    menuItems.value[index].isHovered = true;
  }, 100);
};

const hideSubmenu = (index) => {
  clearTimeout(submenuTimeout.value);
  submenuTimeout.value = setTimeout(() => {
    menuItems.value[index].isHovered = false;
  }, 100);
};
  const toggleSidebar = () => {
    isCollapsed.value = !isCollapsed.value;
  };
  onMounted(() => {
    loadTalleres();
  });
</script>

<style scoped>

</style>