<template>
  <DashboardLayout>
    <template #header>
      Détails de l'utilisateur
    </template>

    <template #description>
      Informations complètes sur l'utilisateur
    </template>

    <div class="p-6 max-w-4xl mx-auto">
      <!-- Carte principale -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- En-tête avec avatar -->
        <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-6 py-8 border-b border-gray-200">
          <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6">
            <!-- Avatar -->
            <div class="flex-shrink-0">
              <div class="w-20 h-20 bg-orange-500 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                {{ user?.prenom?.charAt(0) || '' }}{{ user?.nom?.charAt(0) || '' }}
              </div>
            </div>

            <!-- Informations principales -->
            <div class="text-center sm:text-left flex-1">
              <h1 class="text-2xl font-bold text-gray-900">
                {{ user?.prenom ?? '' }} {{ user?.nom ?? '' }}
              </h1>
              <p class="text-gray-600 mt-1">{{ user?.email ?? '' }}</p>

              <!-- Badges -->
              <div class="flex flex-wrap gap-2 mt-3 justify-center sm:justify-start">
                <span
                  :class="[
                    'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
                    user?.statut === 'actif'
                      ? 'bg-green-100 text-green-800'
                      : 'bg-red-100 text-red-800'
                  ]"
                >
                  <span class="w-2 h-2 rounded-full mr-2" :class="user?.statut === 'actif' ? 'bg-green-500' : 'bg-red-500'"></span>
                  {{ user?.statut === 'actif' ? 'Actif' : 'Inactif' }}
                </span>

                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                  <UserIcon class="w-3 h-3 mr-1" />
                  {{ user?.role_nom ? user.role_nom.charAt(0).toUpperCase() + user.role_nom.slice(1) : 'Non défini' }}
                </span>

                <span v-if="user?.sexe" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                  {{ user.sexe === 'M' ? '♂ Masculin' : '♀ Féminin' }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Corps avec les informations détaillées -->
        <div class="px-6 py-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Colonne 1 : Informations personnelles -->
            <div class="space-y-6">
              <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                  <UserIcon class="w-5 h-5 mr-2 text-orange-500" />
                  Informations personnelles
                </h3>
                <dl class="space-y-3">
                  <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Nom complet</dt>
                    <dd class="text-sm text-gray-900">{{ user?.prenom ?? '' }} {{ user?.nom ?? '' }}</dd>
                  </div>

                  <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="text-sm text-gray-900">{{ user?.email ?? '' }}</dd>
                  </div>

                  <div v-if="user?.sexe" class="flex justify-between items-center py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Sexe</dt>
                    <dd class="text-sm text-gray-900 capitalize">{{ user.sexe === 'M' ? 'Masculin' : 'Féminin' }}</dd>
                  </div>

                  <div v-if="user?.date_naissance" class="flex justify-between items-center py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Date de naissance</dt>
                    <dd class="text-sm text-gray-900">{{ formatDate(user.date_naissance) }}</dd>
                  </div>

                  <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Âge</dt>
                    <dd class="text-sm text-gray-900">{{ calculateAge(user?.date_naissance) }}</dd>
                  </div>
                </dl>
              </div>
            </div>

            <!-- Colonne 2 : Informations du compte -->
            <div class="space-y-6">
              <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                  <SettingsIcon class="w-5 h-5 mr-2 text-orange-500" />
                  Informations du compte
                </h3>
                <dl class="space-y-3">
                  <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Statut</dt>
                    <dd class="text-sm">
                      <span
                        :class="[
                          'inline-flex items-center px-2 py-1 rounded text-xs font-medium',
                          user?.statut === 'actif'
                            ? 'bg-green-100 text-green-800'
                            : 'bg-red-100 text-red-800'
                        ]"
                      >
                        {{ user?.statut === 'actif' ? 'Actif' : 'Inactif' }}
                      </span>
                    </dd>
                  </div>

                  <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Rôle</dt>
                    <dd class="text-sm text-gray-900">{{ user?.role_nom ? user.role_nom.charAt(0).toUpperCase() + user.role_nom.slice(1) : 'Non défini' }}</dd>
                  </div>

                  <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Langue préférée</dt>
                    <dd class="text-sm text-gray-900">{{ langue?.nom_langue ?? 'Non définie' }}</dd>
                  </div>

                  <div v-if="user?.date_inscription" class="flex justify-between items-center py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Date d'inscription</dt>
                    <dd class="text-sm text-gray-900">{{ formatDate(user.date_inscription) }}</dd>
                  </div>

                  <div v-if="user?.email_verified_at" class="flex justify-between items-center py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Email vérifié</dt>
                    <dd class="text-sm text-green-600 font-medium">Oui</dd>
                  </div>
                  <div v-else class="flex justify-between items-center py-2 border-b border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Email vérifié</dt>
                    <dd class="text-sm text-red-600 font-medium">Non</dd>
                  </div>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
          <div class="flex flex-wrap gap-3 justify-end">
            <Link
              :href="route('users.index')"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200"
            >
              <ArrowLeftIcon class="w-4 h-4 mr-2" />
              Retour à la liste
            </Link>

            <Link
              :href="route('users.edit', user?.id)"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 transition-colors duration-200"
            >
              <EditIcon class="w-4 h-4 mr-2" />
              Modifier l'utilisateur
            </Link>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup lang ="ts" lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Link } from '@inertiajs/vue3';
import {
  UserIcon,
  SettingsIcon,
  EditIcon,
  ArrowLeftIcon
} from 'lucide-vue-next';

const props = defineProps<{
  user?: any;
  langue?: any;
  roles?: string[];
}>();

// Formatage de date
function formatDate(dateString: string) {
  if (!dateString) return 'Non définie';
  return new Date(dateString).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
}

// Calcul de l'âge
function calculateAge(dateString: string) {
  if (!dateString) return 'Non définie';

  const birthDate = new Date(dateString);
  const today = new Date();
  let age = today.getFullYear() - birthDate.getFullYear();
  const monthDiff = today.getMonth() - birthDate.getMonth();

  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }

  return age + ' ans';
}
</script>
