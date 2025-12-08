<template>
  <DashboardLayout>
    <template #header>
      Détails de la langue
    </template>

    <template #description>
      Informations complètes sur la langue
    </template>

    <div class="p-6 max-w-2xl mx-auto">
      <!-- Carte principale -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- En-tête avec icône -->
        <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-6 py-6 border-b border-gray-200">
          <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
              <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center text-white">
                <LanguagesIcon class="w-8 h-8" />
              </div>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-900">{{ langue.nom_langue }}</h1>
              <p class="text-gray-600 mt-1">Code: {{ langue.code_langue }}</p>
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
                    <dt class="text-sm font-medium text-gray-500 mb-1">Nom complet</dt>
                    <dd class="text-lg font-semibold text-gray-900">{{ langue.nom_langue }}</dd>
                  </div>

                  <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Code ISO</dt>
                    <dd class="text-lg font-semibold text-gray-900">
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ langue.code_langue }}
                      </span>
                    </dd>
                  </div>
                </dl>
              </div>
            </div>

            <!-- Description -->
            <div class="space-y-6">
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <FileTextIcon class="w-5 h-5 mr-2 text-orange-500" />
                  Description
                </h3>

                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                  <p class="text-gray-700 leading-relaxed">
                    {{ langue.description || 'Aucune description disponible pour cette langue.' }}
                  </p>
                </div>
              </div>

              <!-- Statistiques (optionnel) -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <BarChart3Icon class="w-5 h-5 mr-2 text-orange-500" />
                  Utilisation
                </h3>

                <div class="grid grid-cols-2 gap-4">
                  <div class="text-center p-3 bg-orange-50 rounded-lg border border-orange-200">
                    <div class="text-2xl font-bold text-orange-600">0</div>
                    <div class="text-sm text-orange-700">Utilisateurs</div>
                  </div>
                  <div class="text-center p-3 bg-green-50 rounded-lg border border-green-200">
                    <div class="text-2xl font-bold text-green-600">0</div>
                    <div class="text-sm text-green-700">Contenus</div>
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
              :href="route('langues.index')"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200"
            >
              <ArrowLeftIcon class="w-4 h-4 mr-2" />
              Retour à la liste
            </Link>

            <Link
              :href="route('langues.edit', langue.id)"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 transition-colors duration-200"
            >
              <EditIcon class="w-4 h-4 mr-2" />
              Modifier la langue
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
import {
  LanguagesIcon,
  InfoIcon,
  FileTextIcon,
  BarChart3Icon,
  EditIcon,
  ArrowLeftIcon
} from 'lucide-vue-next';

const props = defineProps<{
  langue: {
    id: number;
    nom_langue: string;
    code_langue: string;
    description?: string;
  }
}>();
</script>
