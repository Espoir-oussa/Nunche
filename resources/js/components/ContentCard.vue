<template>
  <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 border border-gray-100">
    <div class="flex justify-between items-center p-4 border-b">
      <span class="px-3 py-1 bg-orange-100 text-orange-800 text-sm font-medium rounded-full">
        {{ content.type_contenu?.nom_contenu || content.type }}
      </span>
      <span class="px-3 py-1 bg-blue-50 text-blue-800 text-sm font-medium rounded-full">
        {{ content.region?.nom_region || content.region?.name }}
      </span>
    </div>
    <div class="p-6">
      <div class="mb-4">
        <template v-if="content.media && content.media.length">
          <template v-if="isImage(content.media[0].chemin)">
            <img :src="'/storage/' + content.media[0].chemin" alt="Image du contenu" class="w-full h-40 object-cover rounded-xl mb-2" />
          </template>
          <template v-else-if="isVideo(content.media[0].chemin)">
            <video :src="'/storage/' + content.media[0].chemin" controls class="w-full h-40 rounded-xl mb-2" />
          </template>
          <template v-else-if="isAudio(content.media[0].chemin)">
            <audio :src="'/storage/' + content.media[0].chemin" controls class="w-full mb-2" />
          </template>
        </template>
        <template v-else>
          <img src="/images/card.png" alt="Image par défaut" class="w-full h-40 object-cover rounded-xl mb-2" />
        </template>
      </div>
      <h3 class="text-xl font-bold text-gray-900 mb-2">{{ content.titre || content.title }}</h3>
      <p class="text-gray-600 mb-4 line-clamp-3">
        {{ content.texte?.substring(0, 120) || content.description }}...
      </p>
      <div class="flex items-center gap-2 mb-4">
        <span class="text-sm text-gray-500">Disponible en :</span>
        <span v-if="content.langue" class="px-2 py-1 bg-amber-100 text-amber-800 text-xs rounded">{{ content.langue.nom_langue || content.langue.name }}</span>
        <template v-if="content.langues">
          <span v-for="lang in content.langues" :key="lang" class="px-2 py-1 bg-emerald-100 text-emerald-800 text-xs rounded">{{ lang }}</span>
        </template>
      </div>
      <div class="flex items-center justify-between text-sm text-gray-500">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 bg-purple-200 rounded-full flex items-center justify-center">
            <span class="text-purple-700 font-bold">{{ content.auteur?.nom?.charAt(0) || content.auteur?.name?.charAt(0) }}</span>
          </div>
          <span>{{ content.auteur?.nom || content.auteur?.name }}</span>
        </div>
        <span>{{ formatDate(content.date_creation || content.created_at) }}</span>
      </div>
    </div>
    <div class="px-6 pb-6">
      <template v-if="content.user_is_subscribed">
        <button @click="openModal(content)" class="block text-center w-full py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-medium rounded-lg hover:from-orange-600 hover:to-amber-600 transition-all">
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

<script setup lang ="ts" lang="ts">
import { ref } from 'vue'

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
defineProps<{ content: any, requiresSubscription?: boolean }>()

// Fonction utilitaire pour formater la date (comme Home.vue)
const formatDate = (dateString: string) => {
  if (!dateString) return '';
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
}
</script>
