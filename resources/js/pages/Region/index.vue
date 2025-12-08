<template>
  <DashboardLayout>
    <template #header>
      Gestion des régions
    </template>
    <template #description>
      Gérez les régions disponibles sur la plateforme
    </template>
    <div class="p-6">
      <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="w-full sm:w-auto">
          <h1 class="text-2xl font-bold text-gray-900">Régions</h1>
          <p class="text-gray-600 mt-1">{{ regions.total }} région(s) au total</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <Search class="h-5 w-5 text-gray-400" />
            </div>
            <input
              v-model="search"
              @input="onSearch"
              type="text"
              placeholder="Rechercher une région..."
              class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 w-full sm:w-64"
            />
          </div>
          <Link
            :href="route('regions.create')"
            class="inline-flex items-center justify-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-200 font-medium"
          >
            <Plus class="w-4 h-4 mr-2" />
            Nouvelle région
          </Link>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Population</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Superficie</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Localisation</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="region in regions.data" :key="region.id" class="hover:bg-gray-50 transition-colors duration-150">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                      <MapPin class="w-5 h-5 text-green-600" />
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ region.nom_region }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-500 max-w-xs truncate">{{ region.description || 'Aucune description' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ formatNumber(region.population) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ formatSuperficie(region.superficie) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-500">{{ region.localisation || 'Non spécifiée' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end items-center space-x-2">
                    <Link :href="route('regions.show', region.id)" class="text-blue-600 hover:text-blue-900 transition-colors duration-200 p-1 rounded" title="Voir">
                      <Eye class="w-4 h-4" />
                    </Link>
                    <Link :href="route('regions.edit', region.id)" class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200 p-1 rounded" title="Modifier">
                      <Edit class="w-4 h-4" />
                    </Link>
                    <button @click="openDeleteModal(region)" class="text-red-600 hover:text-red-900 transition-colors duration-200 p-1 rounded" title="Supprimer">
                      <Trash2 class="w-4 h-4" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-if="regions.data.length === 0" class="text-center py-12">
          <MapPin class="mx-auto h-12 w-12 text-gray-400" />
          <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune région</h3>
          <p class="mt-1 text-sm text-gray-500">Commencez par créer une nouvelle région.</p>
        </div>
        <div v-if="regions.data.length > 0" class="bg-white px-6 py-3 border-t border-gray-200">
          <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-700">
              Affichage de
              <span class="font-medium">{{ regions.from }}</span>
              à
              <span class="font-medium">{{ regions.to }}</span>
              sur
              <span class="font-medium">{{ regions.total }}</span>
              résultats
            </div>
            <div class="flex items-center space-x-1">
              <button @click="goToPage(1)" :disabled="regions.current_page === 1" :class="['px-3 py-1 rounded border text-sm font-medium', regions.current_page === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300']">«</button>
              <button @click="goToPage(regions.current_page - 1)" :disabled="regions.current_page === 1" :class="['px-3 py-1 rounded border text-sm font-medium', regions.current_page === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300']">Précédent</button>
              <button v-for="page in visiblePages" :key="page" @click="goToPage(page)" :disabled="page === '...'" :class="['px-3 py-1 rounded border text-sm font-medium min-w-10', page === '...' ? 'bg-white text-gray-500 border-gray-300 cursor-default' : regions.current_page === page ? 'bg-orange-600 text-white border-orange-600' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300']">{{ page }}</button>
              <button @click="goToPage(regions.current_page + 1)" :disabled="regions.current_page === regions.last_page" :class="['px-3 py-1 rounded border text-sm font-medium', regions.current_page === regions.last_page ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300']">Suivant</button>
              <button @click="goToPage(regions.last_page)" :disabled="regions.current_page === regions.last_page" :class="['px-3 py-1 rounded border text-sm font-medium', regions.current_page === regions.last_page ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300']">»</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="closeDeleteModal"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative inline-block w-full max-w-md px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:p-6">
          <div class="sm:flex sm:items-start">
            <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
              <AlertTriangle class="w-6 h-6 text-red-600" />
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
              <h3 class="text-lg font-medium leading-6 text-gray-900">Supprimer la région</h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
                  Êtes-vous sûr de vouloir supprimer la région
                  <span class="font-semibold text-gray-900">{{ regionToDelete?.nom_region }}</span> ?
                  Cette action est irréversible.
                </p>
              </div>
            </div>
          </div>
          <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            <button type="button" @click="confirmDelete" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
              <Trash2 class="w-4 h-4 mr-2" />
              Supprimer
            </button>
            <button type="button" @click="closeDeleteModal" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:mt-0 sm:w-auto sm:text-sm">
              Annuler
            </button>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { route } from 'ziggy-js';
import {
  Search,
  Plus,
  Eye,
  Edit,
  Trash2,
  MapPin,
  AlertTriangle
} from 'lucide-vue-next';

const props = defineProps<{
  regions: {
    data: any[];
    current_page: number;
    last_page: number;
    from: number;
    to: number;
    total: number;
  };
  search?: string | null;
}>();

const search = ref(props.search ?? '');
const showDeleteModal = ref(false);
const regionToDelete = ref<any>(null);

let searchTimeout: NodeJS.Timeout;
function onSearch() {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    router.get(route('regions.index'), { search: search.value }, {
      preserveState: true,
      replace: true
    });
  }, 300);
}

function goToPage(page: number) {
  if (page < 1 || page > props.regions.last_page || page === '...') return;
  router.get(route('regions.index'), {
    page,
    search: search.value
  }, {
    preserveState: true
  });
}

const visiblePages = computed(() => {
  const current = props.regions.current_page;
  const last = props.regions.last_page;
  const delta = 2;
  const range = [];
  const rangeWithDots = [];
  for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
    range.push(i);
  }
  if (current - delta > 2) {
    rangeWithDots.push(1, '...');
  } else {
    rangeWithDots.push(1);
  }
  rangeWithDots.push(...range);
  if (current + delta < last - 1) {
    rangeWithDots.push('...', last);
  } else if (last > 1) {
    rangeWithDots.push(last);
  }
  return rangeWithDots;
});

function openDeleteModal(region: any) {
  regionToDelete.value = region;
  showDeleteModal.value = true;
}

function closeDeleteModal() {
  showDeleteModal.value = false;
  regionToDelete.value = null;
}

function confirmDelete() {
  if (regionToDelete.value) {
    router.delete(route('regions.destroy', regionToDelete.value.id), {
      onSuccess: () => {
        closeDeleteModal();
      }
    });
  }
}

function formatNumber(value: number) {
  if (!value) return '0';
  return new Intl.NumberFormat('fr-FR').format(value);
}

function formatSuperficie(value: number) {
  if (!value) return '0 km²';
  return `${new Intl.NumberFormat('fr-FR').format(value)} km²`;
}
</script>
