<template>
  <DashboardLayout>
    <template #header>
      Modifier le commentaire
    </template>

    <template #description>
      Éditez les informations du commentaire
    </template>

    <div class="p-6 max-w-4xl mx-auto">
      <!-- Carte du formulaire -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- En-tête -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
          <div class="flex items-center">
            <MessageSquare class="w-6 h-6 text-blue-600 mr-3" />
            <h1 class="text-xl font-semibold text-gray-900">Modifier le commentaire</h1>
          </div>
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="submit" class="p-6">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Colonne 1 -->
            <div class="space-y-6">
              <!-- Informations du commentaire -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <MessageSquare class="w-5 h-5 mr-2 text-blue-500" />
                  Contenu du commentaire
                </h3>

                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Commentaire *
                    </label>
                    <textarea
                      v-model="form.texte"
                      rows="6"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 resize-none"
                      required
                      placeholder="Saisissez votre commentaire..."
                    ></textarea>
                    <p class="mt-1 text-xs text-gray-500">
                      Le texte du commentaire (minimum 10 caractères)
                    </p>
                  </div>
                </div>
              </div>

              <!-- Évaluation -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <Star class="w-5 h-5 mr-2 text-yellow-500" />
                  Évaluation
                </h3>

                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Note *
                    </label>
                    <select
                      v-model="form.note"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                    >
                      <option value="" disabled>Sélectionnez une note</option>
                      <option value="1">⭐ 1 étoile</option>
                      <option value="2">⭐⭐ 2 étoiles</option>
                      <option value="3">⭐⭐⭐ 3 étoiles</option>
                      <option value="4">⭐⭐⭐⭐ 4 étoiles</option>
                      <option value="5">⭐⭐⭐⭐⭐ 5 étoiles</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">
                      Notez de 1 à 5 étoiles
                    </p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Date *
                    </label>
                    <input
                      v-model="form.date"
                      type="date"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                    />
                    <p class="mt-1 text-xs text-gray-500">
                      Date du commentaire
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Colonne 2 -->
            <div class="space-y-6">
              <!-- Relations -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <Users class="w-5 h-5 mr-2 text-green-500" />
                  Relations
                </h3>

                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Utilisateur *
                    </label>
                    <select
                      v-model="form.user_id"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                    >
                      <option value="" disabled>Sélectionnez un utilisateur</option>
                      <option v-for="user in users" :key="user.id" :value="user.id">
                        {{ user.nom }}
                      </option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">
                      Auteur du commentaire
                    </p>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Contenu *
                    </label>
                    <select
                      v-model="form.contenu_id"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                    >
                      <option value="" disabled>Sélectionnez un contenu</option>
                      <option v-for="contenu in contenus" :key="contenu.id" :value="contenu.id">
                        {{ contenu.titre }}
                      </option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">
                      Contenu associé au commentaire
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex flex-wrap gap-3 justify-end pt-6 mt-6 border-t border-gray-200">
            <Link
              :href="route('commentaires.index')"
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
              <Save class="w-4 h-4 mr-2" />
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
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import {
  MessageSquare,
  Star,
  Users,
  Save,
  ArrowLeft
} from 'lucide-vue-next';

interface User {
  id: number;
  nom: string;
}
interface Contenu {
  id: number;
  titre: string;
}
interface Commentaire {
  id: number;
  texte: string;
  note: number;
  date: string;
  user_id: number;
  contenu_id: number;
}

const props = defineProps<{
  commentaire: Commentaire;
  users: User[];
  contenus: Contenu[];
}>();

const form = useForm({
  texte: props.commentaire.texte,
  note: props.commentaire.note,
  date: props.commentaire.date,
  user_id: props.commentaire.user_id,
  contenu_id: props.commentaire.contenu_id,
});

function submit() {
  form.put(route('commentaires.update', props.commentaire.id));
}
</script>
