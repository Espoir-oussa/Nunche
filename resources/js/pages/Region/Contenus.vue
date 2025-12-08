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
    <!-- Navbar -->
    <Navbar />

    <!-- HERO SECTION -->
    <section class="relative h-96 flex items-center justify-center overflow-hidden pt-16">
      <!-- Formes artistiques béninoises -->
      <div class="absolute inset-0 z-5">
        <div class="absolute top-10 left-10 w-20 h-20 border-2 border-orange-400/30 rounded-full animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-16 h-16 border-2 border-orange-600/40 rotate-45 animate-pulse delay-1000"></div>
        <div class="absolute top-1/2 left-1/4 w-12 h-12 border-2 border-orange-500/30 rounded-full animate-pulse delay-500"></div>
      </div>

      <!-- Image de fond de la région -->
      <div
        class="absolute top-0 left-0 w-full h-full object-cover"
        :style="{
          backgroundImage: `linear-gradient(to bottom, rgba(0,0,0,0.7), rgba(0,0,0,0.5)), url('/images/regions/${region.id}.jpg')`,
          backgroundSize: 'cover',
          backgroundPosition: 'center'
        }"
      ></div>

      <!-- Overlay avec dégradé -->
      <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/60 to-black/80 z-0"></div>

      <!-- Texte principal -->
      <div class="relative z-10 text-center text-white px-4 sm:px-6 max-w-5xl mx-auto">
        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 leading-tight">
          Contenus de la région :
          <span class="text-orange-400 bg-gradient-to-r from-orange-400 to-amber-400 bg-clip-text text-transparent">
            {{ region.nom_region }}
          </span>
        </h1>
        <p class="text-lg sm:text-xl md:text-2xl mb-6 text-gray-200 leading-relaxed max-w-3xl mx-auto px-4">
          {{ region.description || 'Découvrez la richesse culturelle et linguistique de cette région.' }}
        </p>
        <div class="flex flex-wrap justify-center gap-3">
          <span v-for="langue in region.langues" :key="langue.id"
                class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-white text-sm font-medium">
            {{ langue.nom_langue }}
          </span>
        </div>
      </div>
    </section>

    <!-- SECTION : FILTRES ET CONTENUS -->
    <section class="py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-white to-amber-50">
      <div class="max-w-7xl mx-auto">

        <!-- Grille des contenus -->
        <div v-if="filteredContenus.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="contenu in filteredContenus" :key="contenu.id"
               class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 border border-gray-100 animate-fade-in">
            <div class="flex justify-between items-center p-4 border-b">
              <span class="px-3 py-1 bg-orange-100 text-orange-800 text-sm font-medium rounded-full">
                {{ contenu.type_contenu?.nom_contenu || contenu.type }}
              </span>
              <span class="px-3 py-1 bg-blue-50 text-blue-800 text-sm font-medium rounded-full">
                {{ region.nom_region }}
              </span>
            </div>

            <div class="p-6">
              <!-- Media -->
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
              <p class="text-gray-600 mb-4 line-clamp-3">
                {{ contenu.texte?.substring(0, 120) || contenu.description }}...
              </p>

              <!-- Langues -->
              <div class="flex items-center gap-2 mb-4">
                <span class="text-sm text-gray-500">Disponible en :</span>
                <span v-if="contenu.langue" class="px-2 py-1 bg-amber-100 text-amber-800 text-xs rounded">
                  {{ contenu.langue.nom_langue || contenu.langue.name }}
                </span>
                <template v-if="contenu.langues">
                  <span v-for="lang in contenu.langues" :key="lang"
                        class="px-2 py-1 bg-emerald-100 text-emerald-800 text-xs rounded">
                    {{ lang }}
                  </span>
                </template>
              </div>

              <!-- Auteur et date -->
              <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center text-white font-bold">
                    {{ contenu.auteur?.nom?.charAt(0) || contenu.auteur?.name?.charAt(0) || 'A' }}
                  </div>
                  <div>
                    <div class="font-medium">{{ contenu.auteur?.nom || contenu.auteur?.name || 'Anonyme' }}</div>
                    <div class="text-xs text-gray-400">{{ contenu.auteur?.role || 'Contributeur' }}</div>
                  </div>
                </div>
                <div class="text-right">
                  <div class="font-medium">{{ formatDate(contenu.date_creation || contenu.created_at) }}</div>
                  <div class="text-xs text-gray-400">{{ contenu.vues || 0 }} vues</div>
                </div>
              </div>

              <!-- Bouton d'action -->
              <template v-if="contenu.user_is_subscribed || !contenu.est_abonnement">
                <button @click="openModal(contenu)"
                        class="w-full py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-medium rounded-lg hover:from-orange-600 hover:to-amber-600 transition-all flex items-center justify-center gap-2">
                  <Eye class="w-4 h-4" />
                  Voir le contenu
                </button>
              </template>
              <template v-else>
                <Link href="/abonnement"
                      class="block w-full py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-medium rounded-lg hover:from-orange-600 hover:to-amber-600 transition-all text-center">
                  S'abonner pour voir le contenu
                </Link>
              </template>
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
            Aucun contenu ne correspond à vos filtres pour la région {{ region.nom_region }}.
          </p>
          <button @click="selectedType = null; selectedLangue = null"
                  class="px-6 py-3 bg-orange-500 text-white rounded-full font-semibold hover:bg-orange-600 transition-colors">
            Voir tous les contenus
          </button>
        </div>
      </div>
    </section>

    <!-- SECTION : SAVOIR PLUS SUR LA RÉGION -->
    <section class="py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-amber-50 to-white">
      <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
          <div class="md:flex">
            <div class="md:w-1/3 bg-gradient-to-br from-orange-500 to-amber-500 p-8 text-white flex items-center justify-center">
              <div class="text-center">
                <MapPin class="w-16 h-16 mx-auto mb-4" />
                <h3 class="text-2xl font-bold mb-2">{{ region.nom_region }}</h3>
                <div class="text-amber-100">Région du Bénin</div>
              </div>
            </div>
            <div class="md:w-2/3 p-8">
              <h4 class="text-xl font-bold text-gray-900 mb-4">À propos de cette région</h4>
              <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                  <div class="text-sm text-gray-500 mb-1">Population</div>
                  <div class="text-lg font-semibold text-gray-900">
                    {{ region.population ? region.population.toLocaleString('fr-FR') + ' hab.' : 'Non spécifiée' }}
                  </div>
                </div>
                <div>
                  <div class="text-sm text-gray-500 mb-1">Superficie</div>
                  <div class="text-lg font-semibold text-gray-900">
                    {{ region.superficie ? region.superficie.toLocaleString('fr-FR') + ' km²' : 'Non spécifiée' }}
                  </div>
                </div>
              </div>
              <p class="text-gray-700 mb-6">{{ region.description }}</p>
              <div class="flex flex-wrap gap-3">
                <span class="px-4 py-2 bg-orange-50 text-orange-700 rounded-full text-sm font-medium">
                  {{ filteredContenus.length }} contenus
                </span>
                <span class="px-4 py-2 bg-blue-50 text-blue-700 rounded-full text-sm font-medium">
                  {{ langues.length }} langues
                </span>
                <span class="px-4 py-2 bg-green-50 text-green-700 rounded-full text-sm font-medium">
                  {{ uniqueAuteurs }} auteurs
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<script setup lang ="ts" lang="ts">
import { ref, computed } from 'vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';
import {
  Play,
  Music,
  Eye,
  FileSearch,
  MapPin
} from 'lucide-vue-next';

const props = defineProps<{
  region: any,
  contenus: any[],
  types?: any[],
  langues?: any[]
}>();

// Modal state
const showModal = ref(false);
const modalContenu = ref<any>(null);

// Filter states
const selectedType = ref<number | null>(null);
const selectedLangue = ref<number | null>(null);

// Compute types and langues from contenus if not provided
const types = computed(() => {
  if (props.types) return props.types;
  const uniqueTypes = new Map();
  props.contenus.forEach(contenu => {
    if (contenu.type_contenu) {
      uniqueTypes.set(contenu.type_contenu.id, contenu.type_contenu);
    }
  });
  return Array.from(uniqueTypes.values());
});

const langues = computed(() => {
  if (props.langues) return props.langues;
  const uniqueLangues = new Map();
  props.contenus.forEach(contenu => {
    if (contenu.langue) {
      uniqueLangues.set(contenu.langue.id, contenu.langue);
    }
  });
  return Array.from(uniqueLangues.values());
});

// Filter contenus
const filteredContenus = computed(() => {
  return props.contenus.filter((contenu) => {
    const typeMatch = selectedType.value ?
      contenu.type_contenu?.id === selectedType.value : true;
    const langueMatch = selectedLangue.value ?
      contenu.langue?.id === selectedLangue.value : true;
    return typeMatch && langueMatch;
  });
});

// Count unique authors
const uniqueAuteurs = computed(() => {
  const auteurs = new Set();
  props.contenus.forEach(contenu => {
    if (contenu.auteur?.id) {
      auteurs.add(contenu.auteur.id);
    }
  });
  return auteurs.size;
});

// Media type detection
function isImage(path: string) {
  return /\.(jpg|jpeg|png|gif|webp|bmp|svg)$/i.test(path);
}

function isVideo(path: string) {
  return /\.(mp4|webm|ogg|mov|avi|wmv)$/i.test(path);
}

function isAudio(path: string) {
  return /\.(mp3|wav|ogg|flac|m4a|aac)$/i.test(path);
}

// Modal functions
function openModal(contenu: any) {
  modalContenu.value = contenu;
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
  modalContenu.value = null;
}

// Format date
const formatDate = (dateString: string) => {
  if (!dateString) return '';
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
};

// Navigation
const voirContenu = (id: number) => {
  router.visit(`/contenu/${id}`);
};
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
</style>
