<template>
  <div class="min-h-screen relative flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <Link href="/" class="absolute top-6 left-6 z-20">
      <div class="font-bold text-2xl text-white tracking-tight">
        ContesDuBénin
      </div>
    </Link>

    <!-- Vidéo de fond -->
    <img
      src="/videos/nunche.png"
      alt="Animation d'ambiance Nunche"
      class="absolute top-0 left-0 w-full h-full object-cover"
    />

    <!-- Overlay noir -->
    <div class="absolute inset-0 bg-black/70 z-0"></div>

    <Head title="Confirmation du mot de passe" />

    <div class="max-w-md w-full space-y-6 bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20 relative z-10">
      <!-- En-tête -->
      <div class="text-center">
        <h2 class="text-2xl font-bold text-white">
          Confirmation requise
        </h2>
        <p class="mt-1 text-sm text-gray-300">
          Zone sécurisée de l'application
        </p>
      </div>

      <!-- Instructions -->
      <div class="text-center text-sm text-gray-300 bg-white/5 rounded-lg p-4">
        <div class="flex items-center justify-center mb-2">
          <svg class="w-5 h-5 text-orange-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
          <span class="font-medium">Zone sécurisée</span>
        </div>
        <p>Veuillez confirmer votre mot de passe avant de continuer.</p>
      </div>

      <form class="space-y-5" @submit.prevent="submit">
        <!-- Champ mot de passe -->
        <div>
          <InputLabel for="password" value="Mot de passe" class="text-white text-sm font-medium" />
          <TextInput
            id="password"
            type="password"
            class="mt-2 block w-full bg-white/5 border-white/20 text-white placeholder-gray-400 focus:border-orange-500 focus:ring-orange-500 text-sm py-3 px-4 rounded-lg"
            v-model="form.password"
            required
            autofocus
            autocomplete="current-password"
            placeholder="Votre mot de passe actuel"
          />
          <InputError class="mt-2 text-sm" :message="form.errors.password" />
        </div>

        <!-- Bouton de confirmation -->
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
              Vérification...
            </span>
            <span v-else class="flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Confirmer
            </span>
          </button>
        </div>

        <!-- Lien de retour -->
        <div class="text-center pt-4 border-t border-white/20">
          <p class="text-xs text-gray-300">
            <Link :href="route('dashboard')" class="font-medium text-orange-400 hover:text-orange-300 underline">
              Retour au tableau de bord
            </Link>
          </p>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import TextInput from '@/components/TextInput.vue';
import { route } from 'ziggy-js';

const form = useForm({
  password: '',
});

const submit = () => {
  form.post(route('password.confirm'), {
    onFinish: () => form.reset(),
  });
};
</script>
