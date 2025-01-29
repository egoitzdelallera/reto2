<template>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <div class="sidebar py-2" :class="{ 'collapsed': isCollapsed }">
    <h1 class="mb-4 text-center">
      <a href="#" class="logo" @click.prevent="toggleSidebar">
        <img :src="logoSrc" alt="Logo" class="logo-img" />
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
import faviconLogo from '../assets/logos/favicon.png';
import egibideLogo from '../assets/logos/egibide.png';

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
        roles: ['Administrador', 'Tecnico', 'Operario']
      },
      {
        title: 'Máquinas',
        icon: 'bi bi-pc-display-horizontal',
        route: '/maquinas',
        roles: ['Administrador']
        
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
          { name: 'Técnicos', active: false, route: '/usuarios', role: 'Tecnico' },
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
        roles: ['Administrador', 'Tecnico', 'Operario']
      },
      {
        title: 'Cerrar Sesión',
        icon: 'bi bi-box-arrow-left',
        route: '/logout',
        roles: ['Administrador', 'Tecnico', 'Operario']
      }
    ]);

    const isHovered = reactive(menuItems.value.map(() => false));

    onMounted(() => {
      fetchTalleres();
      const userData = JSON.parse(localStorage.getItem('user_data'));
      if (userData && userData.rol) {
        userRole.value = userData.rol;
      }
       
       console.log('Sidebar mounted with isCollapsed:', isCollapsed.value);
    });

    const filteredMenuItems = computed(() => {
        return menuItems.value.filter(item => item.roles.includes(userRole.value));
      });
  
      const updatedMenuItems = computed(() => {
  const incidenciasTalleres = (talleres.value || [])
    .filter(taller => taller.estado === 'Habilitado') // Filter out disabled talleres
    .map(taller => ({
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
     const logoSrc = computed(() => {
            console.log('Calculating logoSrc. isCollapsed:', isCollapsed.value);
            return isCollapsed.value ? faviconLogo : egibideLogo;
        });
 const getMultimediaUrl = (path) => {
    const baseURL = import.meta.env.VITE_API_URL || "http://localhost:8000";
    return path.startsWith("/storage") ? `${baseURL}${path}` : path;
};
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
        console.log('toggleSidebar called, isCollapsed is now:', isCollapsed.value);
    };

    const navigateTo = (route, role, name) => {
        if (route.endsWith('.pdf')) {
             const baseURL = import.meta.env.VITE_URL || window.location.origin;
              const fullPath = `${baseURL}${route}`
           window.open(fullPath, '_blank');
           return
        }
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
      navigateTo,
      logoSrc,
        getMultimediaUrl
    };
  }
};
</script>

<style scoped>
.logo-img {
    width: 50px;
    height: auto;
    transition: width 0.3s ease;
}
.sidebar.collapsed .logo-img {
    width: 30px;
}

</style>