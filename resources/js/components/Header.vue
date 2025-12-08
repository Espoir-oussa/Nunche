<template>
    <header class="bg-white shadow-sm border-b border-gray-200 fixed top-0 right-0 left-0 z-40"
            :class="isSidebarOpen ? 'lg:left-64' : 'lg:left-0'">
        <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
            <!-- Left side - Menu button -->
            <div class="flex items-center space-x-4">
                <!-- Bouton pour ouvrir/fermer la sidebar -->
                <button
                    @click="$emit('toggle-sidebar')"
                    class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors duration-200"
                >
                    <MenuIcon class="w-5 h-5" />
                </button>
            </div>

            <!-- Right side - User menu -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    <BellIcon class="w-5 h-5" />
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>

                <!-- User profile -->
                <div class="relative">
                    <button
                        @click="isUserMenuOpen = !isUserMenuOpen"
                        class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200"
                    >
                        <div class="w-8 h-8 rounded-full flex items-center justify-center overflow-hidden">
                            <img
                                v-if="$page.props.auth && $page.props.auth.user && $page.props.auth.user.profile_photo_url"
                                :src="$page.props.auth.user.profile_photo_url"
                                :alt="$page.props.auth.user.nom || 'Avatar'"
                                class="w-8 h-8 rounded-full object-cover border-2 border-orange-300"
                            />
                            <img
                                v-else
                                src="/images/default-avatar.png"
                                alt="Avatar"
                                class="w-8 h-8 rounded-full object-cover border-2 border-orange-300"
                            />
                        </div>
                        <div class="hidden md:block text-left">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $page.props.auth.user.prenom }} {{ $page.props.auth.user.nom }}
                            </p>
                            <p class="text-xs text-gray-500">
                                Membre
                            </p>
                        </div>
                        <ChevronDownIcon class="w-4 h-4 text-gray-400" />
                    </button>

                    <!-- User dropdown menu -->
                    <div v-if="isUserMenuOpen"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                        <Link href="/profile" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" @click="isUserMenuOpen = false">
                            Mon profil
                        </Link>
                        <button @click="logout" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Déconnexion
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import {
    MenuIcon,
    BellIcon,
    ChevronDownIcon
} from 'lucide-vue-next';

const isUserMenuOpen = ref(false);

// Définir les props pour savoir si la sidebar est ouverte
defineProps<{
    isSidebarOpen?: boolean;
}>();

defineEmits<{
    'toggle-sidebar': [];
}>();

// Déconnexion
const logout = () => {
    router.post('/logout');
};

// Fermer le menu en cliquant à l'extérieur
const handleClickOutside = (event: Event) => {
    if (!(event.target as Element).closest('.relative')) {
        isUserMenuOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>
