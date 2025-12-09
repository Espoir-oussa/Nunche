<template>
  <DashboardLayout>
    <template #header>
      Créer un nouvel utilisateur
    </template>

    <template #description>
      Ajoutez un nouvel utilisateur à la plateforme
    </template>

    <div class="p-6 max-w-4xl mx-auto">
      <!-- Carte du formulaire -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- En-tête -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-amber-50">
          <div class="flex items-center">
            <UserPlusIcon class="w-6 h-6 text-orange-600 mr-3" />
            <h1 class="text-xl font-semibold text-gray-900">Nouvel utilisateur</h1>
          </div>
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="submit" class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Colonne 1 -->
            <div class="space-y-6">
              <!-- Informations personnelles -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <UserIcon class="w-5 h-5 mr-2 text-orange-500" />
                  Informations personnelles
                </h3>

                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                    <input
                      v-model="form.prenom"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                      placeholder="Entrez le prénom"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                    <input
                      v-model="form.nom"
                      type="text"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                      placeholder="Entrez le nom"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input
                      v-model="form.email"
                      type="email"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                      placeholder="email@exemple.com"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sexe</label>
                    <select
                      v-model="form.sexe"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                    >
                      <option value="">Sélectionnez le sexe</option>
                      <option value="M">Masculin</option>
                      <option value="F">Féminin</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <!-- Colonne 2 -->
            <div class="space-y-6">
              <!-- Informations du compte -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                  <SettingsIcon class="w-5 h-5 mr-2 text-orange-500" />
                  Informations du compte
                </h3>

                <div class="space-y-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe *</label>
                    <input
                      v-model="form.password"
                      type="password"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                      placeholder="Entrez le mot de passe"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select
                      v-model="form.statut"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                    >
                      <option value="actif">Actif</option>
                      <option value="inactif">Inactif</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                    <input
                      v-model="form.date_naissance"
                      type="date"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rôle *</label>
                    <select
                      v-model="form.role_id"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                    >
                      <option v-for="role in roles" :key="role" :value="role">
                        {{ role.charAt(0).toUpperCase() + role.slice(1) }}
                      </option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Langue *</label>
                    <select
                      v-model="form.langue_id"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200"
                      required
                    >
                      <option v-for="langue in langues" :key="langue.id" :value="langue.id">
                        {{ langue.nom_langue }}
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
              :href="route('users.index')"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200"
            >
              <ArrowLeftIcon class="w-4 h-4 mr-2" />
              Annuler
            </Link>

            <button
              type="submit"
              class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200"
            >
              <UserPlusIcon class="w-4 h-4 mr-2" />
              Créer l'utilisateur
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import {
  UserIcon,
  SettingsIcon,
  UserPlusIcon,
  ArrowLeftIcon
} from 'lucide-vue-next';

const props = defineProps<{
  langues: any[];
  roles: any[];
}>();

const form = reactive({
  nom: '',
  prenom: '',
  email: '',
  password: '',
  statut: 'actif',
  sexe: '',
  date_naissance: '',
  role_id: props.roles[0]?.id || 1,
  langue_id: props.langues[0]?.id || 1
});

function submit() {
  router.post(route('users.store'), form);
}
</script>
