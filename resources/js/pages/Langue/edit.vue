<template>
  <DashboardLayout>
    <template #header>
      Modifier la langue
    </template>

    <template #description>
      Éditez les informations de la langue
    </template>

    <div class="p-6 max-w-2xl mx-auto">
      <!-- Carte du formulaire -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- En-tête -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-amber-50">
          <div class="flex items-center">
            <LanguagesIcon class="w-6 h-6 text-orange-600 mr-3" />
            <h1 class="text-xl font-semibold text-gray-900">Modifier {{ langue.nom_langue }}</h1>
          </div>
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="submit" class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Colonne 1 -->
            <div class="space-y-6">
              <!-- Informations principales -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <InfoIcon class="w-5 h-5 mr-2 text-orange-500" />
                  Informations principales
                </h3>

                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Nom de la langue *
                    </label>
                    <input
                      v-model="form.nom_langue"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                      placeholder="Ex: Français, Fon, Yoruba..."
                    />
                    <p class="mt-1 text-xs text-gray-500">
                      Nom complet de la langue
                    </p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Code ISO *
                    </label>
                    <input
                      v-model="form.code_langue"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                      placeholder="Ex: fr, fon, yor..."
                    />
                    <p class="mt-1 text-xs text-gray-500">
                      Code court (2-3 caractères)
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Colonne 2 -->
            <div class="space-y-6">
              <!-- Description -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <FileTextIcon class="w-5 h-5 mr-2 text-orange-500" />
                  Description
                </h3>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                  </label>
                  <textarea
                    v-model="form.description"
                    rows="6"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 resize-none"
                    placeholder="Description optionnelle de la langue..."
                  ></textarea>
                  <p class="mt-1 text-xs text-gray-500">
                    Informations supplémentaires sur la langue (optionnel)
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex flex-wrap gap-3 justify-end pt-6 mt-6 border-t border-gray-200">
            <Link
              :href="route('langues.index')"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200"
            >
              <ArrowLeftIcon class="w-4 h-4 mr-2" />
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
              <SaveIcon class="w-4 h-4 mr-2" />
              <span v-if="form.processing">Enregistrement...</span>
              <span v-else>Enregistrer les modifications</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import {
  LanguagesIcon,
  InfoIcon,
  FileTextIcon,
  SaveIcon,
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

const form = useForm({
  nom_langue: props.langue.nom_langue,
  code_langue: props.langue.code_langue,
  description: props.langue.description || ''
});

function submit() {
  form.put(route('langues.update', props.langue.id));
}
</script>
