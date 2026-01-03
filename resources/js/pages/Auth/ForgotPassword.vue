<template>
  <div class="min-h-screen relative flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <!-- Barre de navigation fixe en haut -->
    <nav class="fixed top-0 left-0 w-full z-50 bg-transparent py-6">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 flex justify-between items-center">
        <!-- Logo à gauche -->
        <Link href="/" class="flex items-center space-x-2">
          <div
            class="w-10 h-10 rounded-xl flex items-center justify-center bg-gradient-to-r from-orange-500 to-amber-500 shadow-md"
          >
            <svg
              class="w-6 h-6 text-white"
              fill="currentColor"
              viewBox="0 0 24 24"
            >
              <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
            </svg>
          </div>
          <span class="text-2xl font-bold text-white tracking-tight hidden sm:inline">
            Nunche
          </span>
        </Link>

        <!-- Lien "Se connecter" à droite -->
        <div class="flex items-center space-x-4">
          <Link
            :href="route('login')"
            class="px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 text-white rounded-lg hover:bg-white/20 transition-all duration-300 text-sm font-medium"
          >
            Se connecter
          </Link>
        </div>
      </div>
    </nav>

    <!-- Vidéo de fond -->
    <img
      src="/videos/nunche.png"
      alt="Animation d'ambiance Nunche"
      class="absolute top-0 left-0 w-full h-full object-cover"
    />

    <!-- Overlay noir -->
    <div class="absolute inset-0 bg-black/70 z-0"></div>

    <Head title="Mot de passe oublié" />

    <div class="max-w-md w-full space-y-6 bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20 relative z-10">
      <!-- En-tête -->
      <div class="text-center">
        <h2 class="text-2xl font-bold text-white">
          Mot de passe oublié
        </h2>
        <p class="mt-1 text-sm text-gray-300">
          Réinitialisez votre mot de passe en quelques clics
        </p>
      </div>

      <!-- Message de statut -->
      <div
        v-if="status"
        class="p-3 rounded-lg bg-green-500/20 border border-green-400/30 text-green-100 text-sm text-center"
      >
        {{ status }}
      </div>

      <!-- Instructions -->
      <div class="text-center text-sm text-gray-300 bg-white/5 rounded-lg p-4">
        <p>Vous avez oublié votre mot de passe ? Aucun problème.</p>
        <p class="mt-1">Indiquez-nous votre adresse email et nous vous enverrons un lien de réinitialisation.</p>
      </div>

      <form class="space-y-5" @submit.prevent="submit">
        <!-- Champ email -->
        <div>
          <InputLabel for="email" value="Adresse email" class="text-white text-sm font-medium" />
          <TextInput
            id="email"
            type="email"
            class="mt-2 block w-full bg-white/5 border-white/20 text-white placeholder-gray-400 focus:border-orange-500 focus:ring-orange-500 text-sm py-3 px-4 rounded-lg"
            v-model="form.email"
            required
            autofocus
            autocomplete="email"
            placeholder="votre@email.com"
          />
          <InputError class="mt-2 text-sm" :message="form.errors.email" />
        </div>

        <!-- Bouton d'envoi -->
        <div class="pt-2">
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-full text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-orange-500/25"
          >
            <span v-if="form.processing" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Envoi en cours...
            </span>
            <span v-else class="flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              Envoyer le lien de réinitialisation
            </span>
          </button>
        </div>

        <!-- Liens de navigation (version mobile) -->
        <div class="text-center pt-4 border-t border-white/20 sm:hidden">
          <div class="space-y-2">
            <p class="text-xs text-gray-300">
              Vous vous souvenez de votre mot de passe ?
              <Link :href="route('login')" class="font-medium text-orange-400 hover:text-orange-300 underline ml-1">
                Se connecter
              </Link>
            </p>
            <p class="text-xs text-gray-300">
              Pas encore de compte ?
              <Link :href="route('register')" class="font-medium text-orange-400 hover:text-orange-300 underline ml-1">
                S'inscrire
              </Link>
            </p>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import TextInput from '@/components/TextInput.vue';

interface Props {
  status?: string;
}

defineProps<Props>();

const form = useForm({
  email: '',
});

const submit = () => {
  form.post(route('password.email'));
};
</script>
