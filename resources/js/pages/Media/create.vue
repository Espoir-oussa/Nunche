<template>
  <!-- ...existing code... -->
  <DashboardLayout>
    <template #header>
      Nouveau média
    </template>

    <template #description>
      Ajoutez un nouveau média à la plateforme
    </template>

    <div class="p-6 max-w-2xl mx-auto">
      <!-- Carte du formulaire -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- En-tête -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-amber-50">
          <div class="flex items-center">
            <ImageIcon class="w-6 h-6 text-orange-600 mr-3" />
            <h1 class="text-xl font-semibold text-gray-900">Nouveau média</h1>
          </div>
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="submit" class="p-6" enctype="multipart/form-data">
                <div v-if="form.hasErrors" class="mb-6">
                  <div v-for="(errors, key) in form.errors" :key="key" class="text-red-600 text-sm mb-1">
                    <span v-for="err in errors" :key="err">{{ err }}</span>
                  </div>
                </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Colonne 1 -->
            <div class="space-y-6">
              <!-- Upload du fichier -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <UploadIcon class="w-5 h-5 mr-2 text-orange-500" />
                  Fichier média
                </h3>

                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Fichier média *
                    </label>
                    <div
                      @drop.prevent="handleDrop"
                      @dragover.prevent
                      @dragenter.prevent
                      class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-orange-400 transition-colors duration-200"
                      :class="form.chemin ? 'border-orange-400 bg-orange-50' : ''"
                    >
                      <input
                        type="file"
                        @change="onFileChange"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                        accept="image/*,video/*,audio/*,.pdf,.doc,.docx"
                        required
                      />
                      <div v-if="!form.chemin">
                        <UploadIcon class="mx-auto h-12 w-12 text-gray-400" />
                        <div class="mt-4">
                          <p class="text-sm font-medium text-gray-900">
                            Glissez-déposez votre fichier
                          </p>
                          <p class="text-xs text-gray-500 mt-1">
                            ou cliquez pour parcourir
                          </p>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">
                          Formats supportés: images, vidéos, audio, PDF, documents
                        </p>
                      </div>
                      <div v-else class="text-center">
                        <CheckCircleIcon class="mx-auto h-12 w-12 text-green-500" />
                        <p class="text-sm font-medium text-gray-900 mt-2">
                          Fichier sélectionné
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                          {{ getFileName(form.chemin) }}
                        </p>
                        <button
                          type="button"
                          @click.stop="removeFile"
                          class="mt-2 text-xs text-red-600 hover:text-red-800"
                        >
                          Supprimer
                        </button>
                      </div>
                    </div>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Type de média *
                    </label>
                    <select
                      v-model="form.type_media_id"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                    >
                      <option value="">Sélectionnez un type</option>
                      <option v-for="type in types" :key="type.id" :value="type.id">
                        {{ type.nom_media }}
                      </option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">
                      Type de média du fichier
                    </p>
                  </div>

                  <div>
                    <label for="contenu_id" class="block text-sm font-medium text-gray-700 mb-2">
                      Contenu associé *
                    </label>
                    <select
                      id="contenu_id"
                      name="contenu_id"
                      v-model="form.contenu_id"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                    >
                      <option value="" disabled>Choisir un contenu...</option>
                      <option v-for="contenu in contenus" :key="contenu.id" :value="contenu.id">
                        {{ contenu.titre }}
                      </option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">
                      Sélectionnez le contenu auquel ce média est rattaché
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Colonne 2 -->
            <div class="space-y-6">
              <!-- Métadonnées -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <FileTextIcon class="w-5 h-5 mr-2 text-orange-500" />
                  Métadonnées
                </h3>

                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Type de contenu *
                    </label>
                      <!-- Type de contenu synchronisé automatiquement avec le contenu sélectionné -->
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Description
                    </label>
                    <textarea
                      v-model="form.description"
                      rows="6"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 resize-none"
                      placeholder="Description du média (optionnel)..."
                    ></textarea>
                    <p class="mt-1 text-xs text-gray-500">
                      Description optionnelle du média
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Aperçu du fichier (optionnel) -->
          <div v-if="form.chemin && isImage(form.chemin)" class="mt-6 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
              <EyeIcon class="w-5 h-5 mr-2 text-orange-500" />
              Aperçu
            </h3>
            <div class="bg-gray-100 rounded-lg p-4 border border-gray-300">
              <img
                :src="getFilePreview(form.chemin)"
                alt="Aperçu"
                class="max-w-full max-h-64 mx-auto rounded"
              />
            </div>
          </div>

          <!-- Actions -->
          <div class="flex flex-wrap gap-3 justify-end pt-6 mt-6 border-t border-gray-200">
            <Link
              :href="route('media.index')"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200"
            >
              <ArrowLeftIcon class="w-4 h-4 mr-2" />
              Annuler
            </Link>

            <button
              type="submit"
              :disabled="form.processing || !form.chemin"
              :class="[
                'inline-flex items-center px-6 py-2 border border-transparent rounded-lg text-sm font-medium text-white transition-colors duration-200',
                form.processing || !form.chemin
                  ? 'bg-orange-400 cursor-not-allowed'
                  : 'bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500'
              ]"
            >
              <PlusIcon class="w-4 h-4 mr-2" />
              <span v-if="form.processing">Création en cours...</span>
              <span v-else>Créer le média</span>
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
  ImageIcon,
  UploadIcon,
  FileTextIcon,
  PlusIcon,
  ArrowLeftIcon,
  CheckCircleIcon,
  EyeIcon
} from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
  contenus: any[];
  types: any[];
  typesContenu: any[];
}>();

const form = useForm({
  chemin: undefined as File | undefined,
  description: '',
  contenu_id: '',
  type_media_id: '',
  type_contenu_id: '' // sera synchronisé automatiquement
});

// Synchroniser automatiquement le type_contenu_id avec le contenu sélectionné
import { watch } from 'vue';
watch(() => form.contenu_id, (newContenuId) => {
  if (newContenuId) {
    const contenu = props.contenus.find((c: any) => c.id == newContenuId);
    form.type_contenu_id = contenu ? contenu.type_contenu_id : '';
  } else {
    form.type_contenu_id = '';
  }
});

function onFileChange(e: Event) {
  const file = (e.target as HTMLInputElement).files?.[0];
  if (file) {
    form.chemin = file;
  }
}

function handleDrop(e: DragEvent) {
  const files = e.dataTransfer?.files;
  if (files && files.length > 0) {
    form.chemin = files[0];
  }
}

function removeFile() {
  form.chemin = undefined;
  // Réinitialiser l'input file
  const fileInput = document.querySelector('input[type="file"]') as HTMLInputElement;
  if (fileInput) {
    fileInput.value = '';
  }
}

function getFileName(file: File | string | undefined): string {
  if (file instanceof File) {
    return file.name;
  }
  return 'Fichier sélectionné';
}

function getFilePreview(file: File | string | undefined): string {
  if (file instanceof File) {
    return URL.createObjectURL(file);
  }
  return '';
}

function isImage(file: File | string | undefined): boolean {
  if (file instanceof File) {
    return file.type.startsWith('image/');
  }
  return false;
}

function submit() {
  form.post(route('media.store'), {
    forceFormData: true,
    onSuccess: () => {
      // Nettoyer les URLs d'aperçu
      if (form.chemin instanceof File) {
        URL.revokeObjectURL(getFilePreview(form.chemin));
      }
    }
  });
}
</script>
