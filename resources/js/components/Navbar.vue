<template>
  <nav
    class="fixed top-0 left-0 w-full z-50 transition-all duration-500"
    :class="scrolled ? 'bg-white/80 backdrop-blur-lg shadow-lg py-4' : 'bg-transparent py-6'"
  >
    <div class="max-w-6xl mx-auto px-4 sm:px-6 flex justify-between items-center">
      <Link href="/" class="flex items-center">
        <!-- Logo blanc (visible quand non scrollé) -->
        <img
          v-if="!scrolled"
          src="/logo_blanc.png"
          alt="nunche - Logo blanc"
          class="h-8 sm:h-8 transition-opacity duration-300"
        />
        <!-- Logo noir (visible quand scrollé) -->
        <img
          v-else
          src="/logo_noir.png"
          alt="nunche - Logo noir"
          class="h-8 sm:h-8 transition-opacity duration-300"
        />
      </Link>

      <!-- Menu burger pour toutes les tailles d'écran -->
      <div class="relative">
        <button
          @click="isMenuOpen = !isMenuOpen"
          class="p-2 rounded-lg transition-all duration-300 flex items-center space-x-2"
          :class="scrolled
            ? 'text-orange-600 bg-white/80 border border-gray-200'
            : 'text-white bg-white/10 backdrop-blur-sm border border-white/20'
          "
        >
          <MenuIcon class="w-6 h-6" />
          <span class="text-sm font-medium hidden sm:inline">
            {{ user ? 'Mon compte' : 'Menu' }}
          </span>
        </button>

        <!-- Menu dropdown -->
        <div
          v-if="isMenuOpen"
          class="absolute top-full right-0 mt-2 bg-white/95 backdrop-blur-lg rounded-xl shadow-xl border border-gray-200 py-3 animate-fade-in w-48 sm:w-56"
          @mousedown.self="isMenuOpen = false"
        >
          <div class="flex flex-col space-y-2 px-3">
            <!-- Liens pour utilisateur connecté -->
            <template v-if="user">
              <div class="px-3 py-2 border-b border-gray-100">
                <p class="text-sm font-semibold text-gray-700">Bonjour, {{ user.name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ user.email }}</p>
              </div>

              <!-- Affiche le lien 'Tableau de bord' uniquement pour les utilisateurs admin -->
              <template v-if="isAdmin">
                <Link
                  href="/dashboard"
                  class="w-full text-center px-4 py-3 rounded-lg hover:bg-orange-50 transition-all duration-300 text-gray-700 hover:text-orange-600 flex items-center justify-center space-x-2"
                  @click="isMenuOpen = false"
                >
                  <LayoutDashboard class="w-4 h-4" />
                  <span>Tableau de bord</span>
                </Link>
              </template>

              <!-- <Link
                href="/profile"
                class="w-full text-center px-4 py-3 rounded-lg hover:bg-orange-50 transition-all duration-300 text-gray-700 hover:text-orange-600 flex items-center justify-center space-x-2"
                @click="isMenuOpen = false"
              >
                <User class="w-4 h-4" />
                <span>Mon profil</span>
              </Link> -->

              <Link
                href="/logout"
                method="post"
                as="button"
                class="w-full text-center bg-red-500 text-white px-4 py-3 rounded-lg hover:bg-red-600 transition-all duration-300 font-semibold flex items-center justify-center space-x-2 mt-2"
                @click="isMenuOpen = false"
              >
                <LogOut class="w-4 h-4" />
                <span>Déconnexion</span>
              </Link>
            </template>

            <!-- Liens pour utilisateur non connecté -->
            <template v-else>
              <Link
                href="/login"
                class="w-full text-center px-4 py-3 rounded-lg hover:bg-orange-50 transition-all duration-300 text-gray-700 hover:text-orange-600 flex items-center justify-center space-x-2"
                @click="isMenuOpen = false"
              >
                <LogIn class="w-4 h-4" />
                <span>Se connecter</span>
              </Link>

              <Link
                href="/register"
                class="w-full text-center bg-orange-500 text-white px-4 py-3 rounded-lg hover:bg-orange-600 transition-all duration-300 font-semibold flex items-center justify-center space-x-2"
                @click="isMenuOpen = false"
              >
                <UserPlus class="w-4 h-4" />
                <span>S'inscrire</span>
              </Link>

              <!-- Optionnel: Autres liens pour visiteurs -->
              <div class="pt-2 border-t border-gray-100">
                <Link
                  href="/about"
                  class="w-full text-center px-4 py-2 rounded-lg hover:bg-gray-50 transition-all duration-300 text-sm text-gray-600"
                  @click="isMenuOpen = false"
                >
                  À propos
                </Link>
                <Link
                  href="/contact"
                  class="w-full text-center px-4 py-2 rounded-lg hover:bg-gray-50 transition-all duration-300 text-sm text-gray-600"
                  @click="isMenuOpen = false"
                >
                  Contact
                </Link>
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup lang ="ts">
import { Link, usePage } from '@inertiajs/vue3'
import {
  MenuIcon,
  LayoutDashboard,
  LogOut,
  LogIn,
  UserPlus
} from 'lucide-vue-next'
import { ref, onMounted, onUnmounted, computed } from 'vue'

const scrolled = ref(false)
const isMenuOpen = ref(false)

const handleScroll = () => {
  scrolled.value = window.scrollY > 50
}

const handleClickOutside = (event: MouseEvent) => {
  const target = event.target as HTMLElement
  if (!target.closest('.relative')) {
    isMenuOpen.value = false
  }
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
  document.removeEventListener('click', handleClickOutside)
})

const $page = usePage()
const user = computed(() => $page.props.auth.user as any)
const isAdmin = computed(() => user.value && user.value.role === 'admin')
</script>

<style scoped>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(10px) scale(0.95); }
  to { opacity: 1; transform: translateY(0) scale(1); }
}

.animate-fade-in {
  animation: fade-in 0.2s ease-out;
}

/* Style pour le dropdown qui s'affiche correctement */
.absolute {
  z-index: 9999;
}
</style>
