<template>
  <DashboardLayout>
    <div class="flex items-center justify-between mb-4">
      <h1 class="text-2xl font-bold">Associations Région-Langue</h1>
      <Link :href="route('parlers.create')" class="btn btn-primary">Ajouter</Link>
    </div>
    <div class="mb-4">
      <input v-model="search" @input="onSearch" type="text" placeholder="Recherche..." class="input input-bordered w-full max-w-xs" />
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Région</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Langue</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="parler in parlers.data" :key="parler.id" class="hover:bg-gray-50 transition-colors duration-150">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ parler.region?.nom_region }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ parler.langue?.nom_langue }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex justify-end items-center space-x-2">
                  <Link :href="route('parlers.show', parler.id)" class="text-blue-600 hover:text-blue-900 transition-colors duration-200 p-1 rounded" title="Voir">
                    <!-- Icône voir -->
                  </Link>
                  <Link :href="route('parlers.edit', parler.id)" class="text-yellow-600 hover:text-yellow-900 transition-colors duration-200 p-1 rounded" title="Modifier">
                    <!-- Icône modifier -->
                  </Link>
                  <button @click="onDelete(parler.id)" class="text-red-600 hover:text-red-900 transition-colors duration-200 p-1 rounded" title="Supprimer">
                    <!-- Icône supprimer -->
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="parlers.data.length === 0" class="text-center py-12">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M3 20h5v-2a4 4 0 013-3.87M16 3.13a4 4 0 01.88 7.94M8 3.13a4 4 0 00-.88 7.94" /></svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune association</h3>
        <p class="mt-1 text-sm text-gray-500">Commencez par créer une nouvelle association.</p>
      </div>
      <div v-if="parlers.data.length > 0" class="bg-white px-6 py-3 border-t border-gray-200">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="text-sm text-gray-700">
            Affichage de
            <span class="font-medium">{{ parlers.from }}</span>
            à
            <span class="font-medium">{{ parlers.to }}</span>
            sur
            <span class="font-medium">{{ parlers.total }}</span>
            résultats
          </div>
          <div class="flex items-center space-x-1">
            <button @click="goToPage(1)" :disabled="parlers.current_page === 1" :class="['px-3 py-1 rounded border text-sm font-medium', parlers.current_page === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300']">«</button>
            <button @click="goToPage(parlers.current_page - 1)" :disabled="parlers.current_page === 1" :class="['px-3 py-1 rounded border text-sm font-medium', parlers.current_page === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300']">Précédent</button>
            <button v-for="page in visiblePages" :key="page" @click="goToPage(page)" :class="['px-3 py-1 rounded border text-sm font-medium min-w-10', parlers.current_page === page ? 'bg-orange-600 text-white border-orange-600' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300']">{{ page }}</button>
            <button @click="goToPage(parlers.current_page + 1)" :disabled="parlers.current_page === parlers.last_page" :class="['px-3 py-1 rounded border text-sm font-medium', parlers.current_page === parlers.last_page ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300']">Suivant</button>
            <button @click="goToPage(parlers.last_page)" :disabled="parlers.current_page === parlers.last_page" :class="['px-3 py-1 rounded border text-sm font-medium', parlers.current_page === parlers.last_page ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300']">»</button>
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

const props = defineProps<{ parlers: any; search?: string }>();
const search = ref(props.search ?? '');

function onSearch() {
  router.get(route('parlers.index'), { search: search.value }, { preserveState: true });
}

function onDelete(id: number) {
  if (confirm('Supprimer cette association ?')) {
    router.delete(route('parlers.destroy', id));
  }
}

function goToPage(page: number) {
  if (page < 1 || page > props.parlers.last_page) return;
  router.get(route('parlers.index'), {
    page,
    search: search.value
  }, {
    preserveState: true
  });
}

const visiblePages = computed(() => {
  const current = props.parlers.current_page;
  const last = props.parlers.last_page;
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
</script>
