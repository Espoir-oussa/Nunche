<template>
  <DashboardLayout>
    <template #header>
      Détails du commentaire
    </template>

    <template #description>
      Informations complètes sur le commentaire
    </template>

    <div class="p-6 max-w-4xl mx-auto">
      <!-- Carte principale -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- En-tête avec icône -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-6 border-b border-gray-200">
          <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
              <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center text-white">
                <MessageSquare class="w-8 h-8" />
              </div>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-900">Commentaire #{{ commentaire.id }}</h1>
              <p class="text-gray-600 mt-1">Posté le {{ formatDate(commentaire.date) }}</p>
            </div>
          </div>
        </div>

        <!-- Corps avec les informations -->
        <div class="px-6 py-6">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Contenu du commentaire -->
            <div class="space-y-6">
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <MessageSquare class="w-5 h-5 mr-2 text-blue-500" />
                  Commentaire
                </h3>

                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                  <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ commentaire.texte }}
                  </p>
                </div>
              </div>

              <!-- Évaluation -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <Star class="w-5 h-5 mr-2 text-yellow-500" />
                  Évaluation
                </h3>

                <div class="flex items-center space-x-4 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                  <div class="flex items-center space-x-1">
                    <Star
                      v-for="n in 5"
                      :key="n"
                      :class="n <= commentaire.note ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300'"
                      class="w-6 h-6"
                    />
                  </div>
                  <div>
                    <div class="text-2xl font-bold text-yellow-600">{{ commentaire.note }}/5</div>
                    <div class="text-sm text-yellow-700">Note attribuée</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Informations contextuelles -->
            <div class="space-y-6">
              <!-- Relations -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <Users class="w-5 h-5 mr-2 text-green-500" />
                  Relations
                </h3>

                <div class="space-y-4">
                  <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Utilisateur</span>
                    <span class="text-sm font-semibold text-gray-900">
                      {{ commentaire.user?.nom || 'Utilisateur inconnu' }}
                    </span>
                  </div>

                  <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Contenu associé</span>
                    <span class="text-sm font-semibold text-gray-900">
                      {{ commentaire.contenu?.titre || 'Aucun contenu' }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Informations techniques -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <Info class="w-5 h-5 mr-2 text-gray-500" />
                  Informations techniques
                </h3>

                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                  <div class="space-y-3">
                    <div class="flex justify-between items-center">
                      <span class="text-sm font-medium text-gray-600">ID du commentaire</span>
                      <span class="text-sm font-semibold text-gray-900">#{{ commentaire.id }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                      <span class="text-sm font-medium text-gray-600">Date de publication</span>
                      <span class="text-sm font-semibold text-gray-900">{{ formatDate(commentaire.date) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                      <span class="text-sm font-medium text-gray-600">Longueur du texte</span>
                      <span class="text-sm font-semibold text-gray-900">{{ commentaire.texte?.length || 0 }} caractères</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
          <div class="flex flex-wrap gap-3 justify-end">
            <Link
              :href="route('commentaires.index')"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200"
            >
              <ArrowLeft class="w-4 h-4 mr-2" />
              Retour à la liste
            </Link>

            <Link
              :href="route('commentaires.edit', commentaire.id)"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 transition-colors duration-200"
            >
              <Edit class="w-4 h-4 mr-2" />
              Modifier le commentaire
            </Link>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import {
  MessageSquare,
  Star,
  Users,
  Info,
  Edit,
  ArrowLeft
} from 'lucide-vue-next';

const props = defineProps<{
  commentaire: {
    id: number;
    texte: string;
    note: number;
    date: string;
    user?: { id: number; nom: string };
    contenu?: { id: number; titre: string };
  }
}>();

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};
</script>
