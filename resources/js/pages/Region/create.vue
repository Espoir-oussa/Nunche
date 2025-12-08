<template>
  <DashboardLayout>
    <template #header>
      Nouvelle région
    </template>

    <template #description>
      Ajoutez une nouvelle région à la plateforme
    </template>

    <div class="p-6 max-w-4xl mx-auto">
      <!-- Carte du formulaire -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- En-tête -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
          <div class="flex items-center">
            <MapPin class="w-6 h-6 text-green-600 mr-3" />
            <h1 class="text-xl font-semibold text-gray-900">Nouvelle région</h1>
          </div>
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="submit" class="p-6">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Colonne 1 -->
            <div class="space-y-6">
              <!-- Informations principales -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <Info class="w-5 h-5 mr-2 text-green-500" />
                  Informations principales
                </h3>

                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Nom de la région *
                    </label>
                    <input
                      v-model="form.nom_region"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                      placeholder="Ex: Atlantique, Borgou, Zou..."
                    />
                    <p class="mt-1 text-xs text-gray-500">
                      Nom officiel de la région
                    </p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Localisation
                    </label>
                    <input
                      v-model="form.localisation"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      placeholder="Ex: Sud du Bénin, Nord-Est..."
                    />
                    <p class="mt-1 text-xs text-gray-500">
                      Position géographique générale
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Colonne 2 -->
            <div class="space-y-6">
              <!-- Démographie -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <Users class="w-5 h-5 mr-2 text-blue-500" />
                  Démographie
                </h3>

                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Population
                    </label>
                    <input
                      v-model="form.population"
                      type="number"
                      min="0"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      placeholder="0"
                    />
                    <p class="mt-1 text-xs text-gray-500">
                      Nombre d'habitants (optionnel)
                    </p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Superficie (km²)
                    </label>
                    <input
                      v-model="form.superficie"
                      type="number"
                      min="0"
                      step="0.01"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      placeholder="0.00"
                    />
                    <p class="mt-1 text-xs text-gray-500">
                      Superficie en kilomètres carrés (optionnel)
                    </p>
                  </div>
                </div>
              </div>

              <!-- Description -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <FileText class="w-5 h-5 mr-2 text-orange-500" />
                  Description
                </h3>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                  </label>
                  <textarea
                    v-model="form.description"
                    rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 resize-none"
                    placeholder="Description de la région, caractéristiques, culture..."
                  ></textarea>
                  <p class="mt-1 text-xs text-gray-500">
                    Informations supplémentaires sur la région (optionnel)
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex flex-wrap gap-3 justify-end pt-6 mt-6 border-t border-gray-200">
            <Link
              :href="route('regions.index')"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200"
            >
              <ArrowLeft class="w-4 h-4 mr-2" />
              Annuler
            </Link>

            <button
              type="submit"
              :disabled="form.processing"
              :class="[
                'inline-flex items-center px-6 py-2 border border-transparent rounded-lg text-sm font-medium text-white transition-colors duration-200',
                form.processing
                  ? 'bg-orange-400 cursor-not-allowed'
                  : 'bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500'
              ]"
            >
              <Plus class="w-4 h-4 mr-2" />
              <span v-if="form.processing">Création...</span>
              <span v-else>Créer la région</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup lang ="ts" lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import {
  MapPin,
  Info,
  Users,
  FileText,
  Plus,
  ArrowLeft
} from 'lucide-vue-next';

const form = useForm({
  nom_region: '',
  description: '',
  population: '',
  superficie: '',
  localisation: '',
  image: null,
});

function onFileChange(e: Event) {
  const target = e.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    form.image = target.files[0];
  } else {
    form.image = null;
  }
}

function submit() {
  form.post(route('regions.store'), {
    forceFormData: true
  });
}
</script>
