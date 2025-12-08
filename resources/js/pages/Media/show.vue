<template>
  <DashboardLayout>
    <template #header>
      Détails du média
    </template>

    <template #description>
      Informations complètes sur le média
    </template>

    <div class="p-6 max-w-2xl mx-auto">
      <!-- Carte principale -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- En-tête avec icône -->
        <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-6 py-6 border-b border-gray-200">
          <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
              <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center text-white">
                  <component :is="getMediaIcon(media.type_media?.nom_media || '')" class="w-8 h-8" />
              </div>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-900">{{ media.chemin }}</h1>
              <p class="text-gray-600 mt-1">{{ media.type_media?.nom_media }}</p>
            </div>
          </div>
        </div>

        <!-- Corps avec les informations -->
        <div class="px-6 py-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informations principales -->
            <div class="space-y-6">
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <InfoIcon class="w-5 h-5 mr-2 text-orange-500" />
                  Informations générales
                </h3>

                <dl class="space-y-4">
                  <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Chemin du fichier</dt>
                    <dd class="text-sm font-mono text-gray-900 bg-gray-50 p-2 rounded border">{{ media.chemin }}</dd>
                  </div>

                  <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Type de média</dt>
                    <dd class="text-sm text-gray-900">
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ media.type_media?.nom_media || 'Non spécifié' }}
                      </span>
                    </dd>
                  </div>
                </dl>
              </div>
            </div>

            <!-- Métadonnées -->
            <div class="space-y-6">
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <FileTextIcon class="w-5 h-5 mr-2 text-orange-500" />
                  Métadonnées
                </h3>

                <dl class="space-y-4">
                  <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Contenu associé</dt>
                    <dd class="text-sm text-gray-900">
                      {{ media.contenu?.titre || 'Aucun contenu associé' }}
                    </dd>
                  </div>

                  <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Description</dt>
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                      <p class="text-sm text-gray-700 leading-relaxed">
                        {{ media.description || 'Aucune description disponible.' }}
                      </p>
                    </div>
                  </div>
                </dl>
              </div>
            </div>
          </div>

          <!-- Aperçu du média (optionnel) -->
          <div class="mt-8 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
              <EyeIcon class="w-5 h-5 mr-2 text-orange-500" />
              Aperçu
            </h3>
            <div class="bg-gray-100 rounded-lg p-8 text-center border-2 border-dashed border-gray-300">
                <component :is="getMediaIcon(media.type_media?.nom_media || '')" class="w-12 h-12 text-gray-400 mx-auto mb-3" />
              <p class="text-sm text-gray-500">Aperçu non disponible</p>
              <p class="text-xs text-gray-400 mt-1">Le média sera affiché ici si supporté</p>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
          <div class="flex flex-wrap gap-3 justify-end">
            <Link
              :href="route('media.index')"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200"
            >
              <ArrowLeftIcon class="w-4 h-4 mr-2" />
              Retour à la liste
            </Link>

            <Link
              :href="route('media.edit', media.id)"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 transition-colors duration-200"
            >
              <EditIcon class="w-4 h-4 mr-2" />
              Modifier le média
            </Link>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup lang="ts">
import { route } from 'ziggy-js';
import {
  ImageIcon,
  InfoIcon,
  FileTextIcon,
  EyeIcon,
  EditIcon,
  ArrowLeftIcon,
  Music2Icon,
  VideoIcon
} from 'lucide-vue-next';
// Fonction utilitaire pour choisir l'icône selon le type de média
function getMediaIcon(type: string) {
  switch (type) {
    case 'image':
      return ImageIcon;
    case 'audio':
      return Music2Icon;
    case 'video':
      return VideoIcon;
    default:
      return ImageIcon;
  }
}

const props = defineProps<{
  media: {
    id: number;
    chemin: string;
    description?: string;
    type_media?: { nom_media: string };
    contenu?: { titre: string };
  }
}>();
</script>
