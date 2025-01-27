<template>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <div class="sidebar p-2" :class="{ 'collapsed': isCollapsed }">
      <h1 class="mb-4 text-center">
        <a href="#" class="logo">
          <span class="logo-full"></span>
          <span class="logo-short">E.</span>
        </a>
      </h1>
      <nav class="nav flex-column">
        <div v-for="(item, index) in filteredMenuItems" :key="index" class="nav-item"
             @mouseenter="showSubmenu(index)"
             @mouseleave="hideSubmenu(index)">
          <a href="#" class="nav-link" @click.prevent="navigateTo(item.route)">
            <i :class="item.icon"></i>
            <span class="nav-link-text">{{ item.title }}</span>
          </a>
          <div v-if="item.submenu" class="submenu" :class="{ 'show': isHovered[index] }">
            <h2 class="submenu-title">{{ item.submenuTitle }}</h2>
            <p class="submenu-subtitle">{{ item.submenuSubtitle }}</p>
            <h3 class="submenu-section-title">{{ item.sectionTitle }}</h3>
            <div class="workshops-container">
              <div v-for="(subItem, subIndex) in item.submenu" :key="subIndex" class="item-card" @click="navigateTo(subItem.route, subItem.role, item.title === 'Incidencias' ? subItem.name : null)">
                <span>{{ subItem.name }}</span>
                <span v-if="subItem.active" class="activity-dot"></span>
              </div>
            </div>
          </div>
        </div>
      </nav>
    </div>
  </template>
  
  <script>
  import { useRouter, useRoute } from 'vue-router';
  import '../assets/css/Nav.css';
  import useTalleres from '@/composables/useTalleres';
  import { onMounted, ref, reactive, watch, computed } from 'vue';
  
  export default {
    setup() {
      const router = useRouter();
      const route = useRoute();
      const { talleres, fetchTalleres } = useTalleres();
      const isCollapsed = ref(true);
      const activeSubmenu = ref(null);
      const submenuTimeout = ref(null);
  
      const userRole = ref('');
  
      const menuItems = ref([
        {
          title: 'Incidencias',
          icon: 'bi bi-shield',
          route: '/incidencias',
          submenuTitle: 'Gestión de Incidencias',
          submenuSubtitle: 'Selecciona una sección.',
          sectionTitle: 'Talleres',
          submenu: [
            { name: 'Todas', active: false, route: '/incidencias' }
          ],
          roles: ['Administrador', 'Técnico', 'Operario']
        },
        {
          title: 'Máquinas',
          icon: 'bi bi-pc-display-horizontal',
          route: '/maquinas',
          roles: ['Administrador', 'Técnico']
          
        },
        {
          title: 'Usuarios',
          icon: 'bi bi-people',
          route: '/usuarios',
          submenuTitle: 'Gestión de Usuarios',
          submenuSubtitle: 'Administración de accesos.',
          sectionTitle: 'Usuarios del Sistema',
          submenu: [
            { name: 'Todos', active: false, route: '/usuarios', role: 'Todos' },
            { name: 'Administradores', active: false, route: '/usuarios', role: 'Administrador' },
            { name: 'Técnicos', active: false, route: '/usuarios', role: 'Técnico' },
            { name: 'Operarios', active: false, route: '/usuarios', role: 'Operario' },
          ],
          roles: ['Administrador']
        },
        {
          title: 'Más Gestiones',
          icon: 'bi bi-building',
          route: '/campus',
         
          roles: ['Administrador']
        },
        {
          title: 'Perfil',
          icon: 'bi bi-person',
          route: '/perfil',
          roles: ['Administrador', 'Técnico', 'Operario']
        },
        {
          title: 'Ayuda',
          icon: 'bi bi-info-circle',
          route: '/ayuda',
          submenuTitle: 'Centro de Ayuda',
          submenuSubtitle: 'Soporte y recursos.',
          sectionTitle: 'Recursos',
          submenu: [
            { name: 'Guías', active: false, route: '/ayuda/guias' },
            { name: 'FAQ', active: false, route: '/ayuda/faq' },
            { name: 'Contacto', active: false, route: '/ayuda/contacto' },
            { name: 'Reportar Error', active: false, route: '/ayuda/reportar-error' }
          ],
          roles: ['Administrador', 'Técnico', 'Operario']
        },
        {
          title: 'Cerrar Sesión',
          icon: 'bi bi-box-arrow-left',
          route: '/logout',
          roles: ['Administrador', 'Técnico', 'Operario']
        }
      ]);
  
      const isHovered = reactive(menuItems.value.map(() => false));
  
      onMounted(() => {
        fetchTalleres();
        const userData = JSON.parse(localStorage.getItem('user_data'));
        if (userData && userData.rol) {
          userRole.value = userData.rol;
        }
      });
  
      const filteredMenuItems = computed(() => {
        return menuItems.value.filter(item => item.roles.includes(userRole.value));
      });
  
      const updatedMenuItems = computed(() => {
        const incidenciasTalleres = (talleres.value || []).map(taller => ({
          name: taller.nombre,
          active: true,
          route: `/incidencias/`
        }));
        return filteredMenuItems.value.map((item) => {
          if (item.title === 'Incidencias') {
            return { ...item, submenu: [...item.submenu, ...incidenciasTalleres] };
          }
          return item;
        });
      });
  
      const showSubmenu = (index) => {
        clearTimeout(submenuTimeout.value);
        submenuTimeout.value = setTimeout(() => {
          isHovered.fill(false);
          isHovered[index] = true;
        }, 100);
      };
  
      const hideSubmenu = (index) => {
        clearTimeout(submenuTimeout.value);
        submenuTimeout.value = setTimeout(() => {
          isHovered[index] = false;
        }, 100);
      };
  
      const toggleSidebar = () => {
        isCollapsed.value = !isCollapsed.value;
      };
  
      const navigateTo = (route, role, name) => {
        if (route === '/usuarios') {
          router.push({ path: route, query: { role: role || 'Todos' } });
          return;
        }
        if (route === '/incidencias' && name === 'Todas') {
          router.push({ path: route });
          return;
        }
        if (route && route !== route.path) {
          if (name) {
            router.push({ path: route, query: { taller: name } });
          } else {
            router.push(route);
          }
        } else if(name) {
          router.push({ path: '/incidencias', query: { taller: name } });
        }
      };
  
      return {
        route,
        talleres,
        isCollapsed,
        activeSubmenu,
        submenuTimeout,
        isHovered,
        filteredMenuItems: updatedMenuItems,
        showSubmenu,
        hideSubmenu,
        toggleSidebar,
        navigateTo
      };
    }
  };
  </script>
  
  <style scoped>
  /* Tus estilos existentes aquí */
  </style>