<template>
  <DashboardLayout>
    <template #header>
      Détails de la région
    </template>

    <template #description>
      Informations complètes sur la région
    </template>

    <div class="p-6 max-w-4xl mx-auto">
      <!-- Carte principale -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- En-tête avec icône -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-6 border-b border-gray-200">
          <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
              <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center text-white">
                <MapPin class="w-8 h-8" />
              </div>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-900">{{ region.nom_region }}</h1>
              <p class="text-gray-600 mt-1">{{ region.localisation || 'Localisation non spécifiée' }}</p>
            </div>
          </div>
        </div>

        <!-- Corps avec les informations -->
        <div class="px-6 py-6">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informations principales -->
            <div class="space-y-6">
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <Info class="w-5 h-5 mr-2 text-green-500" />
                  Informations générales
                </h3>

                <dl class="space-y-4">
                  <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Nom de la région</dt>
                    <dd class="text-lg font-semibold text-gray-900">{{ region.nom_region }}</dd>
                  </div>

                  <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Localisation</dt>
                    <dd class="text-lg font-semibold text-gray-900">
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ region.localisation || 'Non spécifiée' }}
                      </span>
                    </dd>
                  </div>

                  <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Description</dt>
                    <dd class="text-gray-700 leading-relaxed">
                      {{ region.description || 'Aucune description disponible pour cette région.' }}
                    </dd>
                  </div>
                </dl>
              </div>
            </div>

            <!-- Démographie et statistiques -->
            <div class="space-y-6">
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <Users class="w-5 h-5 mr-2 text-blue-500" />
                  Démographie
                </h3>

                <div class="grid grid-cols-2 gap-4">
                  <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="text-2xl font-bold text-green-600">{{ formatNumber(region.population) }}</div>
                    <div class="text-sm text-green-700">Habitants</div>
                  </div>
                  <div class="text-center p-4 bg-orange-50 rounded-lg border border-orange-200">
                    <div class="text-2xl font-bold text-orange-600">{{ formatSuperficie(region.superficie) }}</div>
                    <div class="text-sm text-orange-700">Superficie</div>
                  </div>
                </div>
              </div>

              <!-- Densité de population (calculée) -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <BarChart3 class="w-5 h-5 mr-2 text-purple-500" />
                  Statistiques
                </h3>

                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                  <div class="space-y-3">
                    <div class="flex justify-between items-center">
                      <span class="text-sm font-medium text-gray-600">Densité de population</span>
                      <span class="text-sm font-semibold text-gray-900">
                        {{ calculateDensity(region.population, region.superficie) }} hab/km²
                      </span>
                    </div>
                    <div class="flex justify-between items-center">
                      <span class="text-sm font-medium text-gray-600">ID de la région</span>
                      <span class="text-sm font-semibold text-gray-900">#{{ region.id }}</span>
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
              :href="route('regions.index')"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200"
            >
              <ArrowLeft class="w-4 h-4 mr-2" />
              Retour à la liste
            </Link>

            <Link
              :href="route('regions.edit', region.id)"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 transition-colors duration-200"
            >
              <Edit class="w-4 h-4 mr-2" />
              Modifier la région
            </Link>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup lang ="ts" lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import {
  MapPin,
  Info,
  Users,
  BarChart3,
  Edit,
  ArrowLeft
} from 'lucide-vue-next';

const props = defineProps<{
  region: {
    id: number;
    nom_region: string;
    description?: string;
    population?: number;
    superficie?: number;
    localisation?: string;
  }
}>();

const formatNumber = (value: number | undefined) => {
  if (!value) return '0';
  return new Intl.NumberFormat('fr-FR').format(value);
};

const formatSuperficie = (value: number | undefined) => {
  if (!value) return '0 km²';
  return `${new Intl.NumberFormat('fr-FR').format(value)} km²`;
};

const calculateDensity = (population: number | undefined, superficie: number | undefined) => {
  if (!population || !superficie || superficie === 0) return '0';
  const density = population / superficie;
  return new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 1 }).format(density);
};
</script>
