<template>
  <DashboardLayout>
    <template #header>
      Détails du contenu
    </template>

    <template #description>
      Informations complètes sur le contenu
    </template>

    <div class="p-6 max-w-6xl mx-auto">
      <!-- Carte principale -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- En-tête avec icône -->
        <div class="bg-gradient-to-r from-purple-50 to-violet-50 px-6 py-6 border-b border-gray-200">
          <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
              <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center text-white">
                <FileText class="w-8 h-8" />
              </div>
            </div>
            <div class="flex-1">
              <h1 class="text-2xl font-bold text-gray-900">{{ contenu.titre }}</h1>
              <p class="text-gray-600 mt-1">Créé le {{ formatDate(contenu.date_creation) }}</p>
            </div>
            <div :class="[
              'px-3 py-1 rounded-full text-sm font-medium',
              contenu.statut === 'publié' ? 'bg-green-100 text-green-800' :
              contenu.statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' :
              'bg-gray-100 text-gray-800'
            ]">
              {{ getStatusLabel(contenu.statut) }}
            </div>
          </div>
        </div>

        <!-- Corps avec les informations -->
        <div class="px-6 py-6">
          <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            <!-- Contenu et métadonnées -->
            <div class="space-y-6">
              <!-- Contenu -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <FileText class="w-5 h-5 mr-2 text-purple-500" />
                  Contenu
                </h3>

                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                  <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                    {{ contenu.texte }}
                  </p>
                </div>
              </div>

              <!-- Métadonnées -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <Calendar class="w-5 h-5 mr-2 text-blue-500" />
                  Métadonnées
                </h3>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Date de création</dt>
                    <dd class="text-sm font-semibold text-gray-900">{{ formatDate(contenu.date_creation) }}</dd>
                  </div>

                  <div v-if="contenu.date_validation">
                    <dt class="text-sm font-medium text-gray-500 mb-1">Date de validation</dt>
                    <dd class="text-sm font-semibold text-gray-900">{{ formatDate(contenu.date_validation) }}</dd>
                  </div>

                  <div v-if="contenu.parent">
                    <dt class="text-sm font-medium text-gray-500 mb-1">Contenu parent</dt>
                    <dd class="text-sm font-semibold text-gray-900">{{ contenu.parent.titre }}</dd>
                  </div>
                </dl>
              </div>
            </div>

            <!-- Classification et responsables -->
            <div class="space-y-6">
              <!-- Classification -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <Tag class="w-5 h-5 mr-2 text-green-500" />
                  Classification
                </h3>

                <div class="space-y-4">
                  <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Région</span>
                    <span class="text-sm font-semibold text-gray-900">{{ contenu.region?.nom_region || 'Non spécifiée' }}</span>
                  </div>

                  <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Langue</span>
                    <span class="text-sm font-semibold text-gray-900">{{ contenu.langue?.nom_langue || 'Non spécifiée' }}</span>
                  </div>

                  <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Type de contenu</span>
                    <span class="text-sm font-semibold text-gray-900">{{ contenu.type_contenu?.nom_type || 'Non spécifié' }}</span>
                  </div>
                </div>
              </div>

              <!-- Responsables -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <Users class="w-5 h-5 mr-2 text-orange-500" />
                  Responsables
                </h3>

                <div class="space-y-4">
                  <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Auteur</span>
                    <span class="text-sm font-semibold text-gray-900">{{ contenu.auteur?.nom || 'Non spécifié' }}</span>
                  </div>

                  <div v-if="contenu.moderateur" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Modérateur</span>
                    <span class="text-sm font-semibold text-gray-900">{{ contenu.moderateur.nom }}</span>
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
                  <div class="text-sm">
                    <div class="flex justify-between mb-2">
                      <span class="text-gray-600">ID du contenu</span>
                      <span class="font-medium">#{{ contenu.id }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-gray-600">Longueur du texte</span>
                      <span class="font-medium">{{ contenu.texte?.length || 0 }} caractères</span>
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
              :href="route('contenus.index')"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200"
            >
              <ArrowLeft class="w-4 h-4 mr-2" />
              Retour à la liste
            </Link>

            <Link
              :href="route('contenus.edit', contenu.id)"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 transition-colors duration-200"
            >
              <Edit class="w-4 h-4 mr-2" />
              Modifier le contenu
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
  FileText,
  Calendar,
  Tag,
  Users,
  Info,
  Edit,
  ArrowLeft
} from 'lucide-vue-next';

const props = defineProps<{
  contenu: {
    id: number;
    titre: string;
    texte: string;
    date_creation: string;
    statut: string;
    date_validation?: string;
    parent?: { titre: string };
    region?: { nom_region: string };
    langue?: { nom_langue: string };
    moderateur?: { nom: string };
    type_contenu?: { nom_type: string };
    auteur?: { nom: string };
  }
}>();

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const getStatusLabel = (statut: string) => {
  const statusMap: { [key: string]: string } = {
    'publié': 'Publié',
    'en_attente': 'En attente',
    'brouillon': 'Brouillon',
    'archivé': 'Archivé'
  };
  return statusMap[statut] || statut;
};
</script>
