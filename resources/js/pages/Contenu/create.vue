<template>
  <DashboardLayout>
    <template #header>
      Nouveau contenu
    </template>

    <template #description>
      Créez un nouveau contenu pour la plateforme
    </template>

    <div class="p-6 max-w-6xl mx-auto">
      <!-- Carte du formulaire -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- En-tête -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-violet-50">
          <div class="flex items-center">
            <FileText class="w-6 h-6 text-purple-600 mr-3" />
            <h1 class="text-xl font-semibold text-gray-900">Nouveau contenu</h1>
          </div>
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="submit" class="p-6" enctype="multipart/form-data">
          <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            <!-- Colonne 1 -->
            <div class="space-y-6">
              <!-- Upload média principal -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <FileText class="w-5 h-5 mr-2 text-purple-500" />
                  Image principale (obligatoire)
                </h3>
                <input
                  type="file"
                  accept="image/*"
                  required
                  @change="onFileChange"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                />
                <p class="mt-1 text-xs text-gray-500">Ajoutez une image principale pour ce contenu</p>
              </div>
              <!-- Contenu principal -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <FileText class="w-5 h-5 mr-2 text-purple-500" />
                  Contenu principal
                </h3>

                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Titre *
                    </label>
                    <input
                      v-model="form.titre"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                      placeholder="Titre du contenu..."
                    />
                    <p class="mt-1 text-xs text-gray-500">
                      Titre principal du contenu
                    </p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Contenu *
                    </label>
                    <textarea
                      v-model="form.texte"
                      rows="8"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 resize-none"
                      required
                      placeholder="Contenu détaillé..."
                    ></textarea>
                    <p class="mt-1 text-xs text-gray-500">
                      Texte principal du contenu
                    </p>
                  </div>
                </div>
              </div>

              <!-- Métadonnées -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <Calendar class="w-5 h-5 mr-2 text-blue-500" />
                  Métadonnées
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Date de création *
                    </label>
                    <input
                      v-model="form.date_creation"
                      type="date"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Date de validation
                    </label>
                    <input
                      v-model="form.date_validation"
                      type="date"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Statut *
                    </label>
                    <select
                      v-model="form.statut"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                    >
                      <option value="" disabled>Sélectionnez un statut</option>
                      <option value="brouillon">Brouillon</option>
                      <option value="en_attente">En attente</option>
                      <option value="approuve">Approuvé</option>
                      <option value="rejete">Rejeté</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Contenu parent
                    </label>
                    <select
                      v-model="form.parent_id"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                    >
                      <option value="">Aucun parent</option>
                      <option v-for="parent in parents" :key="parent.id" :value="parent.id">
                        {{ parent.titre }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- Colonne 2 -->
            <div class="space-y-6">
              <!-- Classification -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <Tag class="w-5 h-5 mr-2 text-green-500" />
                  Classification
                </h3>

                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Région *
                    </label>
                    <select
                      v-model="form.region_id"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                    >
                      <option value="" disabled>Sélectionnez une région</option>
                      <option v-for="region in regions" :key="region.id" :value="region.id">
                        {{ region.nom_region }}
                      </option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Langue *
                    </label>
                    <select
                      v-model="form.langue_id"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                    >
                      <option value="" disabled>Sélectionnez une langue</option>
                      <option v-for="langue in langues" :key="langue.id" :value="langue.id">
                        {{ langue.nom_langue }}
                      </option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Type de contenu *
                    </label>
                    <select
                      v-model="form.type_contenu_id"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 text-black"
                      required
                    >
                      <option value="" disabled>Sélectionnez un type</option>
                      <option v-for="type in types" :key="type.id" :value="type.id" class="text-black" >
                        {{ type.nom_type }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Responsables -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <Users class="w-5 h-5 mr-2 text-orange-500" />
                  Responsables
                </h3>

                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Auteur *
                    </label>
                    <select
                      v-model="form.auteur_id"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                    >
                      <option value="" disabled>Sélectionnez un auteur</option>
                      <option v-for="auteur in auteurs" :key="auteur.id" :value="auteur.id">
                        {{ auteur.nom }}
                      </option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Modérateur
                    </label>
                    <select
                      v-model="form.moderateur_id"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                    >
                      <option value="">Aucun modérateur</option>
                      <option v-for="moderateur in moderateurs" :key="moderateur.id" :value="moderateur.id">
                        {{ moderateur.nom }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex flex-wrap gap-3 justify-end pt-6 mt-6 border-t border-gray-200">
            <Link
              :href="route('contenus.index')"
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
              <span v-else>Créer le contenu</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import {
  FileText,
  Calendar,
  Tag,
  Users,
  Plus,
  ArrowLeft
} from 'lucide-vue-next';

const props = defineProps<{
  parents: any[];
  regions: any[];
  langues: any[];
  types: any[];
  auteurs: any[];
  moderateurs: any[];
}>();

const form = useForm({
  titre: '',
  texte: '',
  date_creation: '',
  statut: '',
  date_validation: '',
  parent_id: '',
  region_id: '',
  langue_id: '',
  moderateur_id: '',
  type_contenu_id: '',
  auteur_id: '',
  media: null as File | null,
});

function onFileChange(e: Event) {
  const target = e.target as HTMLInputElement;
  form.media = target.files && target.files.length > 0 ? target.files[0] : null;
}

function submit() {
  form.post(route('contenus.store'), {
    forceFormData: true
  });
}
</script>
