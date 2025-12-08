<template>
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out overflow-y-auto"
        :class="isOpen ? 'translate-x-0' : '-translate-x-full'">
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 px-4 border-b border-gray-200 flex-shrink-0">
            <Link href="/dashboard" class="flex items-center space-x-2">
                <img src="/logo_noir.png" alt="ContesDuBénin - Logo blanc"
                    class="h-8 sm:h-8 transition-opacity duration-300" />
            </Link>
        </div>

        <!-- Navigation -->
        <nav class="px-4 py-4 space-y-1">
            <!-- Lien vers la page Home -->
            <Link href="/" class="flex items-center w-full px-3 py-2.5 rounded-lg transition-all duration-200"
                :class="isActive('/') ? 'bg-orange-50 border border-orange-200 text-orange-700' : 'text-gray-600 hover:bg-gray-100'">
                <HomeIcon class="w-4 h-4 mr-3" :class="isActive('/') ? 'text-orange-600' : 'text-gray-500'" />
                <span class="text-sm font-medium">Accueil</span>
            </Link>

            <!-- Dashboard -->
            <Link href="/dashboard" class="flex items-center w-full px-3 py-2.5 rounded-lg transition-all duration-200"
                :class="isActive('/dashboard') ? 'bg-orange-50 border border-orange-200 text-orange-700' : 'text-gray-600 hover:bg-gray-100'">
                <LayoutDashboardIcon class="w-4 h-4 mr-3"
                    :class="isActive('/dashboard') ? 'text-orange-600' : 'text-gray-500'" />
                <span class="text-sm font-medium">Tableau de bord</span>
            </Link>

            <!-- Section: Gestion du Contenu -->
            <div class="pt-1">
                <button
                    @click="toggleSection('content')"
                    class="flex items-center justify-between w-full px-3 py-2.5 text-xs font-semibold text-gray-700 uppercase tracking-wider hover:bg-gray-50 rounded-lg transition-all duration-200"
                >
                    <div class="flex items-center">
                        <FileTextIcon class="w-3.5 h-3.5 mr-3" />
                        <span class="text-xs font-medium">Gestion du Contenu</span>
                    </div>
                    <ChevronDownIcon
                        class="w-3.5 h-3.5 transition-transform duration-200 flex-shrink-0"
                        :class="{ 'transform rotate-180': openSections.content }"
                    />
                </button>

                <!-- Sous-menu Gestion du Contenu -->
                <div v-if="openSections.content" class="mt-1 space-y-0.5 ml-2">
                    <div class="grid grid-cols-1 gap-0.5">
                        <Link href="/contenus"
                            class="flex items-center w-full px-3 py-1.5 rounded-lg transition-all duration-200"
                            :class="isActive('/contenus') ? 'bg-orange-50 border border-orange-200 text-orange-700' : 'text-gray-600 hover:bg-gray-100'">
                            <div class="flex items-center w-full">
                                <div class="w-1.5 h-1.5 rounded-full bg-orange-400 mr-2.5"></div>
                                <FileTextIcon class="w-3.5 h-3.5 mr-2.5"
                                    :class="isActive('/contenus') ? 'text-orange-600' : 'text-gray-500'" />
                                <span class="text-xs">Contenus</span>
                            </div>
                        </Link>

                        <Link href="/commentaires"
                            class="flex items-center w-full px-3 py-1.5 rounded-lg transition-all duration-200"
                            :class="isActive('/commentaires') ? 'bg-orange-50 border border-orange-200 text-orange-700' : 'text-gray-600 hover:bg-gray-100'">
                            <div class="flex items-center w-full">
                                <div class="w-1.5 h-1.5 rounded-full bg-orange-400 mr-2.5"></div>
                                <MessageSquareIcon class="w-3.5 h-3.5 mr-2.5"
                                    :class="isActive('/commentaires') ? 'text-orange-600' : 'text-gray-500'" />
                                <span class="text-xs">Commentaires</span>
                            </div>
                        </Link>

                        <Link href="/media"
                            class="flex items-center w-full px-3 py-1.5 rounded-lg transition-all duration-200"
                            :class="isActive('/media') ? 'bg-orange-50 border border-orange-200 text-orange-700' : 'text-gray-600 hover:bg-gray-100'">
                            <div class="flex items-center w-full">
                                <div class="w-1.5 h-1.5 rounded-full bg-orange-400 mr-2.5"></div>
                                <ImageIcon class="w-3.5 h-3.5 mr-2.5"
                                    :class="isActive('/media') ? 'text-orange-600' : 'text-gray-500'" />
                                <span class="text-xs">Médias</span>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Section: Géographie -->
            <div class="pt-1">
                <button
                    @click="toggleSection('geography')"
                    class="flex items-center justify-between w-full px-3 py-2.5 text-xs font-semibold text-gray-700 uppercase tracking-wider hover:bg-gray-50 rounded-lg transition-all duration-200"
                >
                    <div class="flex items-center">
                        <GlobeIcon class="w-3.5 h-3.5 mr-3" />
                        <span class="text-xs font-medium">Géographie</span>
                    </div>
                    <ChevronDownIcon
                        class="w-3.5 h-3.5 transition-transform duration-200 flex-shrink-0"
                        :class="{ 'transform rotate-180': openSections.geography }"
                    />
                </button>

                <!-- Sous-menu Géographie -->
                <div v-if="openSections.geography" class="mt-1 space-y-0.5 ml-2">
                    <div class="grid grid-cols-1 gap-0.5">
                        <Link href="/regions"
                            class="flex items-center w-full px-3 py-1.5 rounded-lg transition-all duration-200"
                            :class="isActive('/regions') ? 'bg-orange-50 border border-orange-200 text-orange-700' : 'text-gray-600 hover:bg-gray-100'">
                            <div class="flex items-center w-full">
                                <div class="w-1.5 h-1.5 rounded-full bg-blue-400 mr-2.5"></div>
                                <MapPinIcon class="w-3.5 h-3.5 mr-2.5"
                                    :class="isActive('/regions') ? 'text-orange-600' : 'text-gray-500'" />
                                <span class="text-xs">Régions</span>
                            </div>
                        </Link>

                        <Link href="/langues"
                            class="flex items-center w-full px-3 py-1.5 rounded-lg transition-all duration-200"
                            :class="isActive('/langues') ? 'bg-orange-50 border border-orange-200 text-orange-700' : 'text-gray-600 hover:bg-gray-100'">
                            <div class="flex items-center w-full">
                                <div class="w-1.5 h-1.5 rounded-full bg-blue-400 mr-2.5"></div>
                                <LanguagesIcon class="w-3.5 h-3.5 mr-2.5"
                                    :class="isActive('/langues') ? 'text-orange-600' : 'text-gray-500'" />
                                <span class="text-xs">Langues</span>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Section: Administration -->
            <div class="pt-1">
                <button
                    @click="toggleSection('administration')"
                    class="flex items-center justify-between w-full px-3 py-2.5 text-xs font-semibold text-gray-700 uppercase tracking-wider hover:bg-gray-50 rounded-lg transition-all duration-200"
                >
                    <div class="flex items-center">
                        <ShieldIcon class="w-3.5 h-3.5 mr-3" />
                        <span class="text-xs font-medium">Administration</span>
                    </div>
                    <ChevronDownIcon
                        class="w-3.5 h-3.5 transition-transform duration-200 flex-shrink-0"
                        :class="{ 'transform rotate-180': openSections.administration }"
                    />
                </button>

                <!-- Sous-menu Administration -->
                <div v-if="openSections.administration" class="mt-1 space-y-0.5 ml-2">
                    <div class="grid grid-cols-1 gap-0.5">
                        <Link href="/users"
                            class="flex items-center w-full px-3 py-1.5 rounded-lg transition-all duration-200"
                            :class="isActive('/users') ? 'bg-orange-50 border border-orange-200 text-orange-700' : 'text-gray-600 hover:bg-gray-100'">
                            <div class="flex items-center w-full">
                                <div class="w-1.5 h-1.5 rounded-full bg-red-400 mr-2.5"></div>
                                <UsersIcon class="w-3.5 h-3.5 mr-2.5"
                                    :class="isActive('/users') ? 'text-orange-600' : 'text-gray-500'" />
                                <span class="text-xs">Utilisateurs</span>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Séparateur et Déconnexion -->
            <div class="border-t border-gray-200 pt-3 mt-3">
                <button @click="logout"
                    class="flex items-center w-full px-3 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg transition-all duration-200">
                    <LogOutIcon class="w-4 h-4 mr-3" />
                    <span class="text-sm">Déconnexion</span>
                </button>
            </div>
        </nav>
    </div>

    <!-- Overlay pour mobile -->
    <div v-if="isOpen" @click="$emit('close')" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden">
    </div>
</template>

<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import {
    LayoutDashboardIcon,
    FileTextIcon,
    MessageSquareIcon,
    ImageIcon,
    GlobeIcon,
    MapPinIcon,
    LanguagesIcon,
    ShieldIcon,
    UsersIcon,
    LogOutIcon,
    ChevronDownIcon,
    HomeIcon
} from 'lucide-vue-next';
import { ref, reactive, watch, onMounted } from 'vue';

defineProps<{
    isOpen: boolean;
}>();

defineEmits<{
    close: [];
}>();

// État des sections déroulantes
const openSections = reactive({
    content: false,
    geography: false,
    administration: false
});

// Fonction pour basculer une section
const toggleSection = (section: keyof typeof openSections) => {
    // Optionnel: fermer les autres sections quand on ouvre une nouvelle
    Object.keys(openSections).forEach(key => {
        if (key !== section) {
            openSections[key] = false;
        }
    });
    openSections[section] = !openSections[section];
};

// Vérifier si la route est active
const isActive = (path: string) => {
    const currentUrl = usePage().url;
    return currentUrl.startsWith(path);
};

// Déconnexion
const logout = () => {
    router.post('/logout');
};

// Ouvrir automatiquement la section correspondant à la page actuelle
onMounted(() => {
    const currentUrl = usePage().url;

    if (currentUrl.includes('/contenus') || currentUrl.includes('/commentaires') || currentUrl.includes('/media')) {
        openSections.content = true;
    }

    if (currentUrl.includes('/regions') || currentUrl.includes('/langues')) {
        openSections.geography = true;
    }

    if (currentUrl.includes('/users')) {
        openSections.administration = true;
    }
});
</script>

<style scoped>
/* Animation pour le dropdown */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

/* Animation pour les sous-menus */
.slide-enter-active,
.slide-leave-active {
    transition: max-height 0.3s ease-out, opacity 0.3s ease;
    overflow: hidden;
}

.slide-enter-from,
.slide-leave-to {
    max-height: 0;
    opacity: 0;
}

.slide-enter-to,
.slide-leave-from {
    max-height: 500px;
    opacity: 1;
}
</style>
