<!-- resources/js/Pages/Discover.vue -->
<template>
  <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60">
    <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full p-6 relative">
      <button @click="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-orange-600 text-xl">&times;</button>
      <div v-if="modalContenu">
        <div class="mb-4">
          <template v-if="modalContenu.media && modalContenu.media.length">
            <template v-if="isImage(modalContenu.media[0].chemin)">
              <img :src="'/storage/' + modalContenu.media[0].chemin" alt="Image du contenu" class="w-full h-64 object-cover rounded-xl" />
            </template>
            <template v-else-if="isVideo(modalContenu.media[0].chemin)">
              <video :src="'/storage/' + modalContenu.media[0].chemin" controls class="w-full h-64 rounded-xl" />
            </template>
            <template v-else-if="isAudio(modalContenu.media[0].chemin)">
              <audio :src="'/storage/' + modalContenu.media[0].chemin" controls class="w-full" />
            </template>
          </template>
          <template v-else>
            <img src="/images/card.png" alt="Image par défaut" class="w-full h-64 object-cover rounded-xl" />
          </template>
        </div>
        <h2 class="text-2xl font-bold mb-2">{{ modalContenu.titre }}</h2>
        <p class="text-gray-700 mb-4">{{ modalContenu.texte || modalContenu.description }}</p>
      </div>
    </div>
  </div>

  <div class="min-h-screen bg-gradient-to-b from-white to-amber-50 overflow-hidden">
    <Navbar />

    <!-- HERO SECTION -->
    <section class="relative h-96 flex items-center justify-center overflow-hidden pt-16">
      <!-- Formes artistiques béninoises -->
      <div class="absolute inset-0 z-5">
        <div class="absolute top-10 left-10 w-20 h-20 border-2 border-orange-400/30 rounded-full animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-16 h-16 border-2 border-orange-600/40 rotate-45 animate-pulse delay-1000"></div>
        <div class="absolute top-1/2 left-1/4 w-12 h-12 border-2 border-orange-500/30 rounded-full animate-pulse delay-500"></div>
      </div>

      <!-- Image de fond -->
      <div
        class="absolute top-0 left-0 w-full h-full object-cover bg-cover bg-center"
        style="background-image: linear-gradient(to bottom, rgba(0,0,0,0.7), rgba(0,0,0,0.5)), url('/images/discover-hero.jpg')"
      ></div>

      <!-- Overlay avec dégradé -->
      <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/60 to-black/80 z-0"></div>

      <!-- Texte principal -->
      <div class="relative z-10 text-center text-white px-4 sm:px-6 max-w-5xl mx-auto">
        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 leading-tight">
          Explorez le
          <span class="text-orange-400 bg-gradient-to-r from-orange-400 to-amber-400 bg-clip-text text-transparent">
            patrimoine béninois
          </span>
        </h1>
        <p class="text-lg sm:text-xl md:text-2xl mb-6 text-gray-200 leading-relaxed max-w-3xl mx-auto px-4">
          Découvrez la richesse culturelle et linguistique de notre pays à travers des contes, recettes et traditions uniques.
        </p>
        <div class="inline-flex items-center justify-center px-6 py-3 bg-white/20 backdrop-blur-sm rounded-full">
          <BookOpen class="w-5 h-5 mr-2 text-amber-300" />
          <span class="font-semibold">{{ contentsCount.toLocaleString('fr-FR') }} contenus disponibles</span>
        </div>
      </div>
    </section>

    <!-- SECTION : FILTRES ET CONTENUS -->
    <section class="py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-white to-amber-50">
      <div class="max-w-7xl mx-auto">
        <!-- Barre de recherche et filtres améliorée -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 border border-gray-100">
          <!-- Barre de recherche principale -->
          <div class="relative mb-6">
            <div class="relative flex items-center">
              <SearchIcon class="absolute left-4 w-5 h-5 text-gray-400 z-10" />
              <input
                type="text"
                v-model="searchInput"
                @keyup.enter="applyFilters"
                placeholder="Rechercher un conte, une recette, une tradition..."
                class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300"
              />
              <button
                @click="applyFilters"
                class="absolute right-2 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors"
              >
                Rechercher
              </button>
            </div>
          </div>

          <!-- Filtres principaux -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Filtre par type -->
            <div class="relative">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <FileText class="inline w-4 h-4 mr-1" />
                Type de contenu
              </label>
              <select
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 appearance-none bg-white"
                v-model="selectedType"
                @change="applyFilters"
              >
                <option value="">Tous les types</option>
                <option v-for="type in types" :key="type.id" :value="type.id">
                  {{ type.nom_contenu }}
                </option>
              </select>
            </div>

            <!-- Filtre par région -->
            <div class="relative">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <MapPin class="inline w-4 h-4 mr-1" />
                Région
              </label>
              <select
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 appearance-none bg-white"
                v-model="selectedRegion"
                @change="applyFilters"
              >
                <option value="">Toutes les régions</option>
                <option v-for="region in regions" :key="region.id" :value="region.id">
                  {{ region.nom_region || region.name }}
                </option>
              </select>
            </div>

            <!-- Filtre par langue -->
            <div class="relative">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                <Languages class="inline w-4 h-4 mr-1" />
                Langue
              </label>
              <select
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-300 appearance-none bg-white"
                v-model="selectedLangue"
                @change="applyFilters"
              >
                <option value="">Toutes les langues</option>
                <option v-for="langue in langues" :key="langue.id" :value="langue.id">
                  {{ langue.nom_langue }}
                </option>
              </select>
            </div>
          </div>

          <!-- Filtres rapides -->
          <div class="pt-4 border-t border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Filtres rapides :</h3>
            <div class="flex flex-wrap gap-2">
              <button
                class="px-4 py-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-full hover:from-orange-600 hover:to-amber-600 transition-all flex items-center gap-2"
                :class="{ 'ring-2 ring-orange-400 ring-offset-2': selectedSort === 'populaires' }"
                @click="setSort('populaires')"
              >
                <TrendingUp class="w-4 h-4" />
                Populaires
              </button>
              <button
                class="px-4 py-2 bg-white border-2 border-gray-200 text-gray-700 rounded-full hover:border-orange-300 hover:bg-orange-50 transition-all flex items-center gap-2"
                :class="{ 'border-orange-400 bg-orange-50 text-orange-700 ring-2 ring-orange-100': selectedSort === 'recents' }"
                @click="setSort('recents')"
              >
                <Clock class="w-4 h-4" />
                Plus récents
              </button>
              <button
                class="px-4 py-2 bg-white border-2 border-gray-200 text-gray-700 rounded-full hover:border-orange-300 hover:bg-orange-50 transition-all flex items-center gap-2"
                :class="{ 'border-orange-400 bg-orange-50 text-orange-700 ring-2 ring-orange-100': selectedMedia === 'audio' }"
                @click="toggleMedia('audio')"
              >
                <Music class="w-4 h-4" />
                Avec audio
              </button>
              <button
                class="px-4 py-2 bg-white border-2 border-gray-200 text-gray-700 rounded-full hover:border-orange-300 hover:bg-orange-50 transition-all flex items-center gap-2"
                :class="{ 'border-orange-400 bg-orange-50 text-orange-700 ring-2 ring-orange-100': selectedMedia === 'video' }"
                @click="toggleMedia('video')"
              >
                <Video class="w-4 h-4" />
                Avec vidéo
              </button>
              <button
                v-if="selectedSort || selectedMedia"
                @click="resetFilters"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors flex items-center gap-2"
              >
                <FilterX class="w-4 h-4" />
                Réinitialiser
              </button>
            </div>
          </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
          <div class="bg-white rounded-xl p-6 text-center border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="text-3xl font-bold text-orange-600">{{ contentsCount }}</div>
            <div class="text-sm text-gray-600 mt-1">Contenus</div>
          </div>
          <div class="bg-white rounded-xl p-6 text-center border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="text-3xl font-bold text-blue-600">{{ regions.length }}</div>
            <div class="text-sm text-gray-600 mt-1">Régions</div>
          </div>
          <div class="bg-white rounded-xl p-6 text-center border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="text-3xl font-bold text-green-600">{{ types.length }}</div>
            <div class="text-sm text-gray-600 mt-1">Types</div>
          </div>
          <div class="bg-white rounded-xl p-6 text-center border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="text-3xl font-bold text-purple-600">{{ langues.length }}</div>
            <div class="text-sm text-gray-600 mt-1">Langues</div>
          </div>
        </div>

        <!-- Résultats -->
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold text-gray-900">
            Contenus disponibles
            <span v-if="searchInput || selectedType || selectedRegion" class="text-orange-600"> ({{ filteredCount }})</span>
          </h2>
          <div class="text-sm text-gray-500">
            Page {{ contents.current_page }} sur {{ contents.last_page }}
          </div>
        </div>

        <!-- Grille des contenus -->
        <div v-if="contents.data && contents.data.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <div v-for="contenu in contents.data" :key="contenu.id"
               class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 border border-gray-100 animate-fade-in">
            <!-- En-tête avec badges -->
            <div class="flex justify-between items-center p-4 border-b">
              <span class="px-3 py-1 bg-orange-100 text-orange-800 text-sm font-medium rounded-full">
                {{ contenu.type_contenu?.nom_contenu || contenu.type }}
              </span>
              <span class="px-3 py-1 bg-blue-50 text-blue-800 text-sm font-medium rounded-full">
                {{ contenu.region?.nom_region || contenu.region?.name || 'Général' }}
              </span>
            </div>

            <!-- Media -->
            <div class="p-6">
              <template v-if="contenu.media && contenu.media.length">
                <template v-if="isImage(contenu.media[0].chemin)">
                  <img :src="'/storage/' + contenu.media[0].chemin"
                       alt="Image du contenu"
                       class="w-full h-40 object-cover rounded-xl mb-4 hover:scale-105 transition-transform duration-300 cursor-pointer"
                       @click="openModal(contenu)" />
                </template>
                <template v-else-if="isVideo(contenu.media[0].chemin)">
                  <div class="relative w-full h-40 rounded-xl mb-4 overflow-hidden">
                    <video :src="'/storage/' + contenu.media[0].chemin"
                           class="w-full h-full object-cover"
                           @click="openModal(contenu)" />
                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                      <div class="w-12 h-12 bg-white/80 rounded-full flex items-center justify-center">
                        <Play class="w-6 h-6 text-orange-600 ml-1" />
                      </div>
                    </div>
                  </div>
                </template>
                <template v-else-if="isAudio(contenu.media[0].chemin)">
                  <div class="w-full h-40 bg-gradient-to-r from-orange-100 to-amber-100 rounded-xl mb-4 flex items-center justify-center">
                    <Music class="w-12 h-12 text-orange-600" />
                  </div>
                </template>
              </template>
              <template v-else>
                <img src="/images/card.png"
                     alt="Image par défaut"
                     class="w-full h-40 object-cover rounded-xl mb-4 hover:scale-105 transition-transform duration-300 cursor-pointer"
                     @click="openModal(contenu)" />
              </template>

              <!-- Titre et description -->
              <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-orange-600 transition-colors cursor-pointer"
                  @click="openModal(contenu)">
                {{ contenu.titre || contenu.title }}
              </h3>
              <p class="text-gray-600 mb-4 line-clamp-3 text-sm">
                {{ contenu.texte?.substring(0, 120) || contenu.description }}...
              </p>

              <!-- Langues -->
              <div class="flex items-center gap-2 mb-4">
                <span class="text-sm text-gray-500">Langue :</span>
                <span v-if="contenu.langue" class="px-2 py-1 bg-amber-100 text-amber-800 text-xs rounded">
                  {{ contenu.langue.nom_langue || contenu.langue.name }}
                </span>
              </div>

              <!-- Auteur et date -->
              <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center text-white font-bold">
                    {{ contenu.auteur?.nom?.charAt(0) || contenu.auteur?.name?.charAt(0) || 'A' }}
                  </div>
                  <span>{{ contenu.auteur?.nom || contenu.auteur?.name || 'Anonyme' }}</span>
                </div>
                <span>{{ formatDate(contenu.date_creation || contenu.created_at) }}</span>
              </div>

              <!-- Bouton d'action -->
              <button @click="openModal(contenu)"
                      class="w-full py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-medium rounded-lg hover:from-orange-600 hover:to-amber-600 transition-all flex items-center justify-center gap-2">
                <Eye class="w-4 h-4" />
                Voir le contenu
              </button>
            </div>
          </div>
        </div>

        <!-- Message si aucun contenu -->
        <div v-else class="text-center py-16 animate-fade-in-up">
          <div class="inline-flex items-center justify-center w-24 h-24 bg-orange-100 rounded-full mb-6">
            <FileSearch class="w-12 h-12 text-orange-600" />
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucun contenu trouvé</h3>
          <p class="text-gray-600 mb-6 max-w-md mx-auto">
            Aucun contenu ne correspond à vos critères de recherche.
          </p>
          <button @click="resetFilters"
                  class="px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-full font-semibold hover:from-orange-600 hover:to-amber-600 transition-all">
            Voir tous les contenus
          </button>
        </div>

        <!-- Pagination améliorée -->
        <div class="flex justify-center mt-12" v-if="contents.data && contents.data.length">
          <div class="flex items-center space-x-2">
            <button
              v-if="contents.current_page > 1"
              @click="goToPage(contents.current_page - 1)"
              class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2"
            >
              <ChevronLeft class="w-4 h-4" />
              Précédent
            </button>

            <div class="flex items-center space-x-1">
              <button
                v-for="page in visiblePages"
                :key="page"
                @click="goToPage(page)"
                :class="[
                  'px-3 py-1 rounded-lg transition-colors',
                  contents.current_page === page
                    ? 'bg-orange-500 text-white'
                    : 'bg-white border border-gray-300 hover:bg-gray-50'
                ]"
              >
                {{ page }}
              </button>
            </div>

            <button
              v-if="contents.current_page < contents.last_page"
              @click="goToPage(contents.current_page + 1)"
              class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2"
            >
              Suivant
              <ChevronRight class="w-4 h-4" />
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION : APPEL À ACTION -->
    <!-- <section class="py-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-r from-orange-500 to-amber-500">
      <div class="max-w-4xl mx-auto text-center text-white">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
          Partagez votre patrimoine culturel
        </h2>
        <p class="text-xl text-orange-100 mb-10 max-w-3xl mx-auto">
          Vous avez une histoire familiale, une recette traditionnelle ou une connaissance à partager ?
          Contribuez à la préservation de notre culture.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <Link href="/contribute"
                class="bg-white text-orange-600 px-8 py-4 rounded-full font-bold text-lg hover:bg-orange-50 hover:scale-105 transition-all shadow-2xl flex items-center justify-center gap-2">
            <Plus class="w-5 h-5" />
            Proposer un contenu
          </Link>
          <Link href="/register"
                class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-white/10 transition-all">
            Créer un compte
          </Link>
        </div>
      </div>
    </section> -->

    <Footer />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'
import {
  SearchIcon,
  FileText,
  MapPin,
  Languages,
  TrendingUp,
  Clock,
  Music,
  Video,
  FilterX,
  BookOpen,
  Play,
  Eye,
  FileSearch,
  ChevronLeft,
  ChevronRight,
  Plus
} from 'lucide-vue-next'

const { contents, contentsCount, regions, types, search, langues: propLangues } = defineProps<{
  contents: any
  contentsCount: number
  regions: any[]
  types: any[]
  search?: string
  langues?: any[]
}>()

// Modal state
const showModal = ref(false)
const modalContenu = ref<any>(null)

// Filter states
const searchInput = ref(search || '')
const selectedType = ref('')
const selectedRegion = ref('')
const selectedLangue = ref('')
const selectedSort = ref('recents')
const selectedMedia = ref('')

// Extraire les langues uniques des contenus si non fournies
const langues = computed(() => {
  if (propLangues) return propLangues

  if (!contents.data) return []

  const uniqueLangues = new Map()
  contents.data.forEach(contenu => {
    if (contenu.langue) {
      uniqueLangues.set(contenu.langue.id, contenu.langue)
    }
  })
  return Array.from(uniqueLangues.values())
})

// Compter le nombre de résultats filtrés
const filteredCount = computed(() => {
  if (!contents.data) return 0

  if (searchInput.value || selectedType.value || selectedRegion.value || selectedLangue.value) {
    return contents.data.length
  }
  return contentsCount
})

// Pagination visible
const visiblePages = computed(() => {
  if (!contents.last_page) return []

  const current = contents.current_page
  const last = contents.last_page
  const delta = 2
  const range = []
  const rangeWithDots = []

  for (let i = 1; i <= last; i++) {
    if (i === 1 || i === last || (i >= current - delta && i <= current + delta)) {
      range.push(i)
    }
  }

  range.forEach((i, index) => {
    if (index) {
      const previous = range[index - 1]
      if (i - previous === 2) {
        rangeWithDots.push(previous + 1)
      } else if (i - previous !== 1) {
        rangeWithDots.push('...')
      }
    }
    rangeWithDots.push(i)
  })

  return rangeWithDots
})

// Functions
function applyFilters() {
  router.get('/discover', {
    search: searchInput.value,
    type: selectedType.value,
    region: selectedRegion.value,
    langue: selectedLangue.value,
    sort: selectedSort.value,
    media: selectedMedia.value
  }, {
    preserveState: true,
    replace: true
  })
}

function setSort(sort: string) {
  selectedSort.value = sort
  applyFilters()
}

function toggleMedia(media: string) {
  selectedMedia.value = selectedMedia.value === media ? '' : media
  applyFilters()
}

function resetFilters() {
  searchInput.value = ''
  selectedType.value = ''
  selectedRegion.value = ''
  selectedLangue.value = ''
  selectedSort.value = 'recents'
  selectedMedia.value = ''
  applyFilters()
}

function goToPage(page: number | string) {
  if (typeof page === 'number') {
    router.get('/discover', {
      search: searchInput.value,
      type: selectedType.value,
      region: selectedRegion.value,
      langue: selectedLangue.value,
      sort: selectedSort.value,
      media: selectedMedia.value,
      page: page
    }, {
      preserveState: true
    })
  }
}

// Media type detection
function isImage(path: string) {
  return /\.(jpg|jpeg|png|gif|webp|bmp|svg)$/i.test(path)
}

function isVideo(path: string) {
  return /\.(mp4|webm|ogg|mov|avi|wmv)$/i.test(path)
}

function isAudio(path: string) {
  return /\.(mp3|wav|ogg|flac|m4a|aac)$/i.test(path)
}

// Modal functions
function openModal(contenu: any) {
  modalContenu.value = contenu
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  modalContenu.value = null
}

// Format date
const formatDate = (dateString: string) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  const now = new Date()
  const diffTime = Math.abs(now.getTime() - date.getTime())
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))

  if (diffDays === 1) return 'Aujourd\'hui'
  if (diffDays <= 7) return `Il y a ${diffDays} jours`

  return date.toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: date.getFullYear() !== now.getFullYear() ? 'numeric' : undefined
  })
}

// Initialiser les filtres depuis l'URL
onMounted(() => {
  const urlParams = new URLSearchParams(window.location.search)
  selectedSort.value = urlParams.get('sort') || 'recents'
  selectedMedia.value = urlParams.get('media') || ''
})
</script>

<style scoped>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes fade-in-up {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fade-in 0.6s ease-out;
}

.animate-fade-in-up {
  animation: fade-in-up 0.8s ease-out;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.delay-500 {
  animation-delay: 0.5s;
}

.delay-1000 {
  animation-delay: 1s;
}
</style>
