<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Sidebar -->
        <Sidebar :is-open="isSidebarOpen" @close="closeSidebar" />

        <!-- Header fixe -->
        <Header
            :is-sidebar-open="isSidebarOpen"
            @toggle-sidebar="toggleSidebar"
        />

        <!-- Main Content -->
        <div :class="[
            'transition-all duration-300 pt-16', // pt-16 pour compenser le header fixe
            isSidebarOpen ? 'lg:ml-64' : ''
        ]">
            <!-- Flash messages -->
            <div v-if="$page.props.flash.success" class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border border-green-300">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash.error" class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border border-red-300">
                {{ $page.props.flash.error }}
            </div>
            <!-- Main content area -->
            <main class="p-4 sm:p-6 lg:p-8">
                <!-- Page Header -->
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">
                        <slot name="header">
                            Tableau de bord
                        </slot>
                    </h1>
                    <p class="mt-2 text-gray-600">
                        <slot name="description">
                            Bienvenue sur votre espace personnel
                        </slot>
                    </p>
                </div>

                <!-- Page Content -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup lang ="ts" lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import Sidebar from '@/components/Sidebar.vue';
import Header from '@/components/Header.vue';

// État persistant de la sidebar avec localStorage
const isSidebarOpen = ref(true); // Ouvert par défaut sur desktop

// Vérifier la taille de l'écran et l'état sauvegardé
const checkScreenSize = () => {
    const isMobile = window.innerWidth < 1024; // lg breakpoint

    // Récupérer l'état sauvegardé depuis localStorage
    const savedState = localStorage.getItem('sidebarOpen');

    if (savedState !== null) {
        // Utiliser l'état sauvegardé
        isSidebarOpen.value = JSON.parse(savedState);
    } else {
        // État par défaut : ouvert sur desktop, fermé sur mobile
        isSidebarOpen.value = !isMobile;
    }

    // Sur mobile, forcer la fermeture si sauvegardé comme ouvert
    if (isMobile && isSidebarOpen.value) {
        isSidebarOpen.value = false;
    }
};

// Sauvegarder l'état dans localStorage
const saveSidebarState = (state: boolean) => {
    localStorage.setItem('sidebarOpen', JSON.stringify(state));
};

// Basculer la sidebar
const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
    saveSidebarState(isSidebarOpen.value);
};

// Fermer la sidebar (pour mobile)
const closeSidebar = () => {
    const isMobile = window.innerWidth < 1024;
    if (isMobile) {
        isSidebarOpen.value = false;
        saveSidebarState(false);
    }
};

// Écouter les changements de taille d'écran
onMounted(() => {
    checkScreenSize();
    window.addEventListener('resize', checkScreenSize);
});

onUnmounted(() => {
    window.removeEventListener('resize', checkScreenSize);
});
</script>
