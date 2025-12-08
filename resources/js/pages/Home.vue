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
  <div class="bg-white min-h-screen text-gray-900 overflow-hidden">
    <!-- Navbar component -->
    <Navbar />

    <!-- HERO / Section principale -->
    <section class="relative h-screen flex items-center justify-center overflow-hidden pt-16">
      <!-- Formes artistiques béninoises -->
      <div class="absolute inset-0 z-5">
        <div class="absolute top-10 left-4 sm:left-10 w-16 h-16 sm:w-20 sm:h-20 border-2 border-orange-400/30 rounded-full animate-pulse"></div>
        <div class="absolute bottom-20 right-4 sm:right-10 w-12 h-12 sm:w-16 sm:h-16 border-2 border-orange-600/40 rotate-45 animate-pulse delay-1000"></div>
        <div class="absolute top-1/2 left-1/4 w-10 h-10 sm:w-12 sm:h-12 border-2 border-orange-500/30 rounded-full animate-pulse delay-500"></div>
      </div>

      <!-- Vidéo de fond -->
      <img
        src="/videos/nunche.png"
        alt="Animation d'ambiance Nunche"
        class="absolute top-0 left-0 w-full h-full object-cover"
      />

      <!-- Overlay noir avec dégradé -->
      <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/60 to-black/80 z-0"></div>

      <!-- Texte -->
      <div class="relative z-10 text-center text-white px-4 sm:px-6 max-w-5xl mx-auto">
        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-7xl font-bold mb-4 sm:mb-6 leading-tight">
          Bienvenue sur la plateforme
          <span class="text-orange-400 bg-gradient-to-r from-orange-400 to-amber-400 bg-clip-text text-transparent">
            des cultures du Bénin
          </span>
        </h1>
        <p class="text-lg sm:text-xl md:text-2xl mb-6 sm:mb-8 text-gray-200 leading-relaxed max-w-3xl mx-auto px-4">
          Découvrez, écoutez et partagez la richesse culturelle et linguistique de notre pays.
        </p>
        <Link
          href="/discover"
          class="inline-flex items-center gap-3 bg-orange-500 text-white px-6 sm:px-8 py-3 sm:py-4 rounded-full text-base sm:text-lg font-semibold hover:bg-orange-600 transition-all duration-300 hover:scale-105 hover:shadow-2xl shadow-lg"
        >
          <BookOpen class="w-4 h-4 sm:w-5 sm:h-5" />
          Explorer les contenus
        </Link>
      </div>
    </section>

        <!-- SECTION : DERNIERS CONTENUS -->
    <section class="py-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-white to-amber-50">
      <div class="max-w-7xl mx-auto">
        <!-- En-tête de section -->
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Découvrez les derniers trésors partagés
          </h2>
          <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Plongez dans les histoires, recettes et savoirs récemment enrichis par notre communauté.
          </p>
        </div>

        <!-- Filtres rapides (Tags) -->
        <div class="flex flex-wrap justify-center gap-3 mb-10">
          <button class="px-4 py-2 bg-white border border-orange-200 text-orange-700 rounded-full hover:bg-orange-50 transition-colors" @click="selectedType = null; selectedRegion = null">
            Tous les contenus
          </button>
          <template v-for="type in types" :key="type.id">
            <button class="px-4 py-2 bg-white border border-orange-200 text-gray-700 rounded-full hover:bg-orange-50 transition-colors" @click="selectedType = type.id">
              {{ type.nom_contenu || type.nom }}
            </button>
          </template>
          <select class="px-4 py-2 bg-white border border-orange-200 text-gray-700 rounded-full hover:bg-orange-50 transition-colors appearance-none" v-model="selectedRegion">
            <option :value="null">Toutes les régions</option>
            <option v-for="region in regions" :key="region.id" :value="region.id">{{ region.nom_region || region.nom }}</option>
          </select>
        </div>

        <!-- Grille des contenus dynamiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <template v-if="filteredContenus.length">
            <div v-for="contenu in filteredContenus" :key="contenu.id" class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 border border-gray-100">
              <div class="flex justify-between items-center p-4 border-b">
                <span class="px-3 py-1 bg-orange-100 text-orange-800 text-sm font-medium rounded-full">{{ contenu.type_contenu?.nom_contenu || contenu.type }}</span>
                <span class="px-3 py-1 bg-blue-50 text-blue-800 text-sm font-medium rounded-full">{{ contenu.region?.nom_region || contenu.region?.name }}</span>
              </div>
              <div class="p-6">
                <img v-if="contenu.media && contenu.media.length" :src="'/storage/' + contenu.media[0].chemin" alt="Image du contenu" class="w-full h-40 object-cover rounded-xl mb-4" />
                <img v-else src="/images/card.png" alt="Image par défaut" class="w-full h-40 object-cover rounded-xl mb-4" />
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ contenu.titre || contenu.title }}</h3>
                <p class="text-gray-600 mb-4 line-clamp-3">
                  {{ contenu.texte?.substring(0, 120) || contenu.description }}...
                </p>
                <div class="flex items-center gap-2 mb-4">
                  <span class="text-sm text-gray-500">Disponible en :</span>
                  <span v-if="contenu.langue" class="px-2 py-1 bg-amber-100 text-amber-800 text-xs rounded">{{ contenu.langue.nom_langue || contenu.langue.name }}</span>
                  <template v-if="contenu.langues">
                    <span v-for="lang in contenu.langues" :key="lang" class="px-2 py-1 bg-emerald-100 text-emerald-800 text-xs rounded">{{ lang }}</span>
                  </template>
                </div>
                <div class="flex items-center justify-between text-sm text-gray-500">
                  <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-purple-200 rounded-full flex items-center justify-center">
                      <span class="text-purple-700 font-bold">{{ contenu.auteur?.nom?.charAt(0) || contenu.auteur?.name?.charAt(0) }}</span>
                    </div>
                    <span>{{ contenu.auteur?.nom || contenu.auteur?.name }}</span>
                  </div>
                  <span>{{ formatDate(contenu.date_creation || contenu.created_at) }}</span>
                </div>
              </div>
              <div class="px-6 pb-6">
                <template v-if="contenu.user_is_subscribed">
                  <button @click="openModal(contenu)" class="block text-center w-full py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-medium rounded-lg hover:from-orange-600 hover:to-amber-600 transition-all">
                    Voir le contenu
                  </button>
                </template>
                <template v-else>
                  <a href="/abonnement" class="block text-center w-full py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-medium rounded-lg hover:from-orange-600 hover:to-amber-600 transition-all">
                    S’abonner pour voir le contenu
                  </a>
                </template>
              </div>
            </div>
          </template>
          <div v-else class="col-span-full text-center text-gray-500 py-12 text-lg">
            Aucun contenu disponible pour le moment.
          </div>
        </div>

        <!-- Lien vers la page de découverte -->
        <div class="text-center mt-12">
          <Link href="/discover" class="inline-flex items-center gap-2 text-orange-600 font-semibold hover:text-orange-800 transition-colors text-lg">
            Voir tous les contenus
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
          </Link>
        </div>
      </div>
    </section>


        <!-- SECTION : EXPLOREZ PAR RÉGION -->
    <section class="py-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-amber-50 to-white">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Voyagez à travers les régions
          </h2>
          <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            Chaque région du Bénin possède ses trésors culturels uniques. Explorez-les.
          </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="region in regions" :key="region.id" class="relative overflow-hidden rounded-2xl shadow-lg group">
            <img :src="'/images/regions/' + region.id + '.jpg'" @error="onImgError" alt="Image de la région" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
              <h3 class="text-2xl font-bold mb-2">{{ region.nom_region }}</h3>
              <p class="text-gray-200 mb-3">{{ region.description }}</p>
              <div class="flex flex-wrap gap-2 mb-4">
                <span v-for="langue in region.langues" :key="langue.id" class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm">{{ langue.nom_langue }}</span>
              </div>
              <Link :href="'/region/' + region.id + '/contenus'" class="inline-flex items-center text-orange-300 font-semibold hover:text-orange-200">
                Voir tous les contenus de cette région
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </section>

        <!-- SECTION : APPEL À CONTRIBUTION -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-r from-amber-900 via-orange-800 to-amber-900 text-white">
      <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
          Votre savoir a de la valeur
        </h2>
        <p class="text-xl text-amber-100 mb-10 max-w-3xl mx-auto">
          Partagez une histoire de votre grand-mère, une recette familiale, ou documentez une tradition.
          Ensemble, préservons la mémoire vivante du Bénin.
        </p>
        <div class="flex flex-col sm:flex-row gap-6 justify-center">
          <span class="bg-white text-amber-900 px-8 py-4 rounded-full font-bold text-lg hover:bg-amber-100 hover:scale-105 transition-all shadow-2xl cursor-not-allowed opacity-70">
            Proposer un contenu
          </span>
          <span class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-white/10 transition-all cursor-not-allowed opacity-70">
            Comment contribuer ?
          </span>
        </div>
        <p class="mt-10 text-amber-200 text-sm">
          ✨ Toute contribution est modérée par notre équipe pour garantir la qualité et l'authenticité.
        </p>
      </div>
    </section>



    <!-- Footer component -->
    <Footer />
  </div>
</template>

<script setup lang="ts">
const showModal = ref(false)
const modalContenu = ref<any>(null)

function openModal(contenu: any) {
  modalContenu.value = contenu
  showModal.value = true
}
function closeModal() {
  showModal.value = false
  modalContenu.value = null
}

function isImage(path: string) {
  return /\.(jpg|jpeg|png|gif)$/i.test(path)
}
function isVideo(path: string) {
  return /\.(mp4|webm|ogg)$/i.test(path)
}
function isAudio(path: string) {
  return /\.(mp3|wav|ogg)$/i.test(path)
}
import { ref, computed } from 'vue'

function onImgError(e: Event) {
  const target = e.target as HTMLImageElement | null
  if (target) target.src = '/images/card.png'
}
const props = defineProps<{
  contenus: any[],
  types: any[],
  regions: any[]
}>()

const selectedType = ref<null | number>(null)
const selectedRegion = ref<null | number>(null)

const filteredContenus = computed(() => {
  return props.contenus.filter((c) => {
    const typeMatch = selectedType.value ? c.type_contenu_id === selectedType.value : true
    const regionMatch = selectedRegion.value ? c.region_id === selectedRegion.value : true
    return typeMatch && regionMatch
  })
})
// Fonction utilitaire pour formater la date
const formatDate = (dateString: string) => {
  if (!dateString) return '';
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
}

import { Link } from '@inertiajs/vue3'
import { BookOpen } from 'lucide-vue-next'

// Import des composants
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'

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
  animation: fade-in 0.8s ease-out;
}

.animate-fade-in-up {
  animation: fade-in-up 0.6s ease-out;
}

.delay-500 {
  animation-delay: 0.5s;
}

.delay-1000 {
  animation-delay: 1s;
}
</style>
