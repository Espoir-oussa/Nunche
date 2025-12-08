<template>
  <DashboardLayout>
    <h1 class="text-2xl font-bold mb-4">Détail de l'association Région-Langue</h1>
    <div class="max-w-lg mx-auto bg-white rounded shadow p-6">
      <div class="mb-2"><strong>ID :</strong> {{ parler.id }}</div>
      <div class="mb-2"><strong>Région :</strong> {{ parler.region?.nom_region }}</div>
      <div class="mb-2"><strong>Langue :</strong> {{ parler.langue?.nom_langue }}</div>
      <div class="flex gap-2 mt-6">
        <Link :href="route('parlers.edit', parler.id)" class="btn btn-primary">Modifier</Link>
        <Link :href="route('parlers.index')" class="btn btn-secondary">Retour</Link>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup lang ="ts" lang="ts">
import { Link } from '@inertiajs/vue3';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { route } from 'ziggy-js';

defineProps<{ parler: any }>();
</script>
