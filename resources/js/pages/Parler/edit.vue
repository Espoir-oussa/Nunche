<template>
  <DashboardLayout>
    <h1 class="text-2xl font-bold mb-4">Modifier l'association Région-Langue</h1>
    <form @submit.prevent="submit" class="space-y-4 max-w-lg mx-auto">
      <div>
        <label class="block mb-1">Région</label>
        <select v-model="form.region_id" class="input input-bordered w-full" required>
          <option value="" disabled>Sélectionner...</option>
          <option v-for="region in regions" :key="region.id" :value="region.id">{{ region.nom_region }}</option>
        </select>
      </div>
      <div>
        <label class="block mb-1">Langue</label>
        <select v-model="form.langue_id" class="input input-bordered w-full" required>
          <option value="" disabled>Sélectionner...</option>
          <option v-for="langue in langues" :key="langue.id" :value="langue.id">{{ langue.nom_langue }}</option>
        </select>
      </div>
      <div class="flex gap-2 mt-6">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <Link :href="route('parlers.index')" class="btn btn-secondary">Annuler</Link>
      </div>
    </form>
  </DashboardLayout>
</template>

<script setup lang ="ts" lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { route } from 'ziggy-js';

const props = defineProps<{ parler: any; regions: any[]; langues: any[] }>();

const form = useForm({
  region_id: props.parler.region_id,
  langue_id: props.parler.langue_id,
});

function submit() {
  form.put(route('parlers.update', props.parler.id));
}
</script>
