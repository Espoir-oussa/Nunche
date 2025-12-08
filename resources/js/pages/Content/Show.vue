<!-- resources/js/Pages/Content/Show.vue -->
<template>
  <div class="min-h-screen bg-white">
    <Navbar />

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-12">
      <!-- En-tête -->
      <div class="mb-8">
        <div class="flex items-center gap-2 mb-4">
          <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm">
            {{ content.type }}
          </span>
          <span class="px-3 py-1 bg-blue-50 text-blue-800 rounded-full text-sm">
            {{ content.region.name }}
          </span>
        </div>

        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
          {{ content.title }}
        </h1>

        <!-- Métadonnées -->
        <div class="flex items-center justify-between text-gray-600 mb-6">
          <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
              <UserIcon class="w-5 h-5" />
              <span>{{ content.author.name }}</span>
            </div>
            <div class="flex items-center gap-2">
              <CalendarIcon class="w-5 h-5" />
              <span>{{ formatDate(content.created_at) }}</span>
            </div>
          </div>

          <!-- Langues disponibles -->
          <div class="flex gap-2">
            <span v-for="lang in content.languages" :key="lang"
              class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">
              {{ lang }}
            </span>
          </div>
        </div>
      </div>

      <!-- Médias -->
      <div v-if="content.media.length" class="mb-8">
        <img :src="content.media[0].url" :alt="content.title"
             class="w-full h-64 md:h-96 object-cover rounded-2xl shadow-lg">
      </div>

      <!-- Contenu (partiellement verrouillé) -->
      <div class="prose prose-lg max-w-none mb-12">
        <!-- Extrait gratuit (premier paragraphe) -->
        <div v-html="content.excerpt"></div>

        <!-- Verrou d'abonnement -->
        <div v-if="!$page.props.auth.user?.hasActiveSubscription"
             class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-orange-500 p-6 rounded-lg my-8">
          <div class="flex items-center gap-4">
            <LockIcon class="w-8 h-8 text-orange-600" />
            <div>
              <h3 class="text-lg font-bold text-gray-900">Contenu réservé aux abonnés</h3>
              <p class="text-gray-600">Abonnez-vous pour lire la suite et accéder à tous les contenus premium</p>
            </div>
          </div>

          <!-- Call to Action -->
          <div class="mt-6 flex flex-col sm:flex-row gap-4">
            <Link href="/subscription"
                  class="bg-orange-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-700 text-center">
              Voir les offres d'abonnement
            </Link>
            <Link href="/register"
                  class="border border-orange-600 text-orange-600 px-6 py-3 rounded-lg font-semibold hover:bg-orange-50 text-center">
              Créer un compte gratuit
            </Link>
          </div>
        </div>

        <!-- Contenu complet (visible seulement pour les abonnés) -->
        <div v-else v-html="content.full_content"></div>
      </div>

      <!-- Médias additionnels (audio/vidéo) -->
      <div v-if="content.media.length > 1" class="mb-12">
        <h3 class="text-xl font-bold mb-4">Médias associés</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
          <div v-for="media in content.media.slice(1)" :key="media.id"
               class="border rounded-lg overflow-hidden">
            <img v-if="media.type === 'image'" :src="media.url" :alt="media.caption"
                 class="w-full h-32 object-cover">
            <div v-else class="p-4 bg-gray-50 text-center">
              <MusicIcon v-if="media.type === 'audio'" class="w-8 h-8 mx-auto text-gray-400" />
              <VideoIcon v-else class="w-8 h-8 mx-auto text-gray-400" />
              <p class="text-sm mt-2">{{ media.caption }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Auteur -->
      <div class="border-t pt-8">
        <h3 class="text-xl font-bold mb-4">À propos de l'auteur</h3>
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">
            <span class="text-2xl font-bold text-orange-700">
              {{ content.author.name.charAt(0) }}
            </span>
          </div>
          <div>
            <h4 class="font-bold text-lg">{{ content.author.name }}</h4>
            <p class="text-gray-600">{{ content.author.bio }}</p>
            <p class="text-sm text-gray-500 mt-1">
              {{ content.author.contributions_count }} contributions
            </p>
          </div>
        </div>
      </div>
    </div>

    <Footer />
  </div>
</template>

<script setup lang ="ts" lang="ts">
import { Link } from '@inertiajs/vue3'
import { UserIcon, CalendarIcon, LockIcon, MusicIcon, VideoIcon } from 'lucide-vue-next'
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'

defineProps<{
  content: any
}>()

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}
</script>
