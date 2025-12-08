<template>
  <DashboardLayout>
    <template #header>
      Gestion des contenus
    </template>
    <template #description>
      Gérez les contenus de votre plateforme
    </template>
    <div class="p-6">
      <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="w-full sm:w-auto">
          <h1 class="text-2xl font-bold text-gray-900">Contenus</h1>
          <p class="text-gray-600 mt-1">{{ contenus.total }} contenu(s) au total</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <SearchIcon class="h-5 w-5 text-gray-400" />
            </div>
            <input v-model="search" @input="onSearch" type="text" placeholder="Rechercher un contenu..."
              class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 w-full sm:w-64" />
          </div>
          <Link :href="route('contenus.create')"
            class="inline-flex items-center justify-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-200 font-medium">
            <PlusIcon class="w-4 h-4 mr-2" />
            Nouveau contenu
          </Link>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
                <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th> -->
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Région</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Langue</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="contenu in contenus.data" :key="contenu.id" class="hover:bg-gray-50 transition-colors duration-150">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ contenu.titre }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-500">{{ contenu.auteur?.nom }}</div>
                </td>
                <!-- <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ contenu.type_contenu?.nom_type }}</div>
                </td> -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ contenu.region?.nom_region }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ contenu.langue?.nom_langue }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="[
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    contenu.statut === 'valide'
                      ? 'bg-green-100 text-green-800'
                      : 'bg-red-100 text-red-800'
                  ]">
                    {{ contenu.statut === 'valide' ? 'Validé' : 'Brouillon' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end items-center space-x-2">
                    <Link :href="route('contenus.show', contenu.id)"
                      class="text-blue-600 hover:text-blue-900 transition-colors duration-200 p-1 rounded"
                      title="Voir">
                      <EyeIcon class="w-4 h-4" />
                    </Link>
                    <Link :href="route('contenus.edit', contenu.id)"
                      class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200 p-1 rounded"
                      title="Modifier">
                      <EditIcon class="w-4 h-4" />
                    </Link>
                    <button @click="openDeleteModal(contenu)"
                      class="text-red-600 hover:text-red-900 transition-colors duration-200 p-1 rounded"
                      title="Supprimer">
                      <Trash2Icon class="w-4 h-4" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-if="contenus.data.length === 0" class="text-center py-12">
          <FileTextIcon class="mx-auto h-12 w-12 text-gray-400" />
          <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun contenu</h3>
          <p class="mt-1 text-sm text-gray-500">Commencez par créer un nouveau contenu.</p>
        </div>
        <div v-if="contenus.data.length > 0" class="bg-white px-6 py-3 border-t border-gray-200">
          <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-700">
              Affichage de
              <span class="font-medium">{{ contenus.from }}</span>
              à
              <span class="font-medium">{{ contenus.to }}</span>
              sur
              <span class="font-medium">{{ contenus.total }}</span>
              résultats
            </div>
            <div class="flex items-center space-x-1">
              <button @click="goToPage(1)" :disabled="contenus.current_page === 1" :class="[
                'px-3 py-1 rounded border text-sm font-medium',
                contenus.current_page === 1
                  ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                  : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300'
              ]">
                «
              </button>
              <button @click="goToPage(contenus.current_page - 1)" :disabled="contenus.current_page === 1"
                :class="[
                  'px-3 py-1 rounded border text-sm font-medium',
                  contenus.current_page === 1
                    ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                    : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300'
                ]">
                Précédent
              </button>
              <button v-for="page in visiblePages" :key="page" @click="goToPage(page)" :class="[
                'px-3 py-1 rounded border text-sm font-medium min-w-10',
                contenus.current_page === page
                  ? 'bg-orange-600 text-white border-orange-600'
                  : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300'
              ]">
                {{ page }}
              </button>
              <button @click="goToPage(contenus.current_page + 1)"
                :disabled="contenus.current_page === contenus.last_page" :class="[
                  'px-3 py-1 rounded border text-sm font-medium',
                  contenus.current_page === contenus.last_page
                    ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                    : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300'
                ]">
                Suivant
              </button>
              <button @click="goToPage(contenus.last_page)"
                :disabled="contenus.current_page === contenus.last_page" :class="[
                  'px-3 py-1 rounded border text-sm font-medium',
                  contenus.current_page === contenus.last_page
                    ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                    : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300'
                ]">
                »
              </button>
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
              <AlertTriangleIcon class="w-6 h-6 text-red-600" />
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
              <h3 class="text-lg font-medium leading-6 text-gray-900">
                Supprimer le contenu
              </h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
                  Êtes-vous sûr de vouloir supprimer le contenu
                  <span class="font-semibold text-gray-900">{{ contenuToDelete?.titre }}</span> ?
                  Cette action est irréversible.
                </p>
              </div>
            </div>
          </div>
          <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
            <button type="button" @click="confirmDelete"
              class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
              <Trash2Icon class="w-4 h-4 mr-2" />
              Supprimer
            </button>
            <button type="button" @click="closeDeleteModal"
              class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:mt-0 sm:w-auto sm:text-sm">
              Annuler
            </button>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup lang ="ts" lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { route } from 'ziggy-js';
import {
  SearchIcon,
  PlusIcon,
  EyeIcon,
  EditIcon,
  Trash2Icon,
  FileTextIcon,
  AlertTriangleIcon
} from 'lucide-vue-next';

const props = defineProps<{
  contenus: {
    data: any[];
    current_page: number;
    last_page: number;
    from: number;
    to: number;
    total: number;
  };
  search?: string | null;
  filters: any;
}>();

const search = ref(props.search ?? '');
const showDeleteModal = ref(false);
const contenuToDelete = ref<any>(null);

function onSearch() {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    router.get(route('contenus.index'), { search: search.value }, {
      preserveState: true,
      replace: true
    });
  }, 300);
}

let searchTimeout: NodeJS.Timeout;

function goToPage(page: number) {
  if (page < 1 || page > props.contenus.last_page) return;
  router.get(route('contenus.index'), {
    page,
    search: search.value
  }, {
    preserveState: true
  });
}

const visiblePages = computed(() => {
  const current = props.contenus.current_page;
  const last = props.contenus.last_page;
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

function openDeleteModal(contenu: any) {
  contenuToDelete.value = contenu;
  showDeleteModal.value = true;
}

function closeDeleteModal() {
  showDeleteModal.value = false;
  contenuToDelete.value = null;
}

function confirmDelete() {
  if (contenuToDelete.value) {
    router.delete(route('contenus.destroy', contenuToDelete.value.id), {
      onSuccess: () => {
        closeDeleteModal();
      }
    });
  }
}
</script>
