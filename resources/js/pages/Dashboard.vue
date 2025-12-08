<template>
  <DashboardLayout>
    <template #header>
      Tableau de bord
    </template>
    <template #description>
      Vue d'ensemble de la plateforme
    </template>
    <div class="p-6">
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex items-center">
          <Users class="w-8 h-8 text-orange-600 mr-4" />
          <div>
            <div class="text-2xl font-bold text-gray-900">{{ stats.users }}</div>
            <div class="text-gray-600">Utilisateurs</div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex items-center">
          <FileText class="w-8 h-8 text-purple-600 mr-4" />
          <div>
            <div class="text-2xl font-bold text-gray-900">{{ stats.contenus }}</div>
            <div class="text-gray-600">Contenus</div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex items-center">
          <MessageSquare class="w-8 h-8 text-blue-600 mr-4" />
          <div>
            <div class="text-2xl font-bold text-gray-900">{{ stats.commentaires }}</div>
            <div class="text-gray-600">Commentaires</div>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex items-center">
          <Globe class="w-8 h-8 text-green-600 mr-4" />
          <div>
            <div class="text-2xl font-bold text-gray-900">{{ stats.regions }}</div>
            <div class="text-gray-600">Régions</div>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Accès rapide</h2>
        <div class="flex flex-wrap gap-4">
          <Link :href="route('users.index')" class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-200 font-medium shadow">Gérer les utilisateurs</Link>
          <Link :href="route('contenus.index')" class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-200 font-medium shadow">Gérer les contenus</Link>
          <Link :href="route('commentaires.index')" class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-200 font-medium shadow">Gérer les commentaires</Link>
          <Link :href="route('regions.index')" class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-200 font-medium shadow">Gérer les régions</Link>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { Users, FileText, MessageSquare, Globe } from 'lucide-vue-next';

const props = defineProps<{ stats: { users: number; contenus: number; commentaires: number; regions: number; } }>();
</script>
