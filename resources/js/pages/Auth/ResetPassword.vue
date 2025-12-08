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

    <Head title="Réinitialisation du mot de passe" />

    <div class="max-w-md w-full space-y-6 bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20 relative z-10">
      <!-- En-tête -->
      <div class="text-center">
        <h2 class="text-2xl font-bold text-white">
          Nouveau mot de passe
        </h2>
        <p class="mt-1 text-sm text-gray-300">
          Créez votre nouveau mot de passe
        </p>
      </div>

      <!-- Instructions -->
      <div class="text-center text-sm text-gray-300 bg-white/5 rounded-lg p-4">
        <div class="flex items-center justify-center mb-2">
          <svg class="w-5 h-5 text-orange-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
          </svg>
          <span class="font-medium">Réinitialisation</span>
        </div>
        <p>Veuillez saisir votre nouveau mot de passe ci-dessous.</p>
      </div>

      <form class="space-y-4" @submit.prevent="submit">
        <!-- Champ email (caché mais nécessaire) -->
        <input type="hidden" v-model="form.email" />

        <!-- Champ nouveau mot de passe -->
        <div>
          <InputLabel for="password" value="Nouveau mot de passe" class="text-white text-sm font-medium" />
          <TextInput
            id="password"
            type="password"
            class="mt-2 block w-full bg-white/5 border-white/20 text-white placeholder-gray-400 focus:border-orange-500 focus:ring-orange-500 text-sm py-3 px-4 rounded-lg"
            v-model="form.password"
            required
            autocomplete="new-password"
            placeholder="Votre nouveau mot de passe"
          />
          <InputError class="mt-2 text-sm" :message="form.errors.password" />
        </div>

        <!-- Confirmation du mot de passe -->
        <div>
          <InputLabel for="password_confirmation" value="Confirmer le mot de passe" class="text-white text-sm font-medium" />
          <TextInput
            id="password_confirmation"
            type="password"
            class="mt-2 block w-full bg-white/5 border-white/20 text-white placeholder-gray-400 focus:border-orange-500 focus:ring-orange-500 text-sm py-3 px-4 rounded-lg"
            v-model="form.password_confirmation"
            required
            autocomplete="new-password"
            placeholder="Confirmez votre nouveau mot de passe"
          />
          <InputError class="mt-2 text-sm" :message="form.errors.password_confirmation" />
        </div>

        <!-- Bouton de réinitialisation -->
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
              Réinitialisation...
            </span>
            <span v-else class="flex items-center">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
              Réinitialiser le mot de passe
            </span>
          </button>
        </div>

        <!-- Liens de navigation -->
        <div class="text-center pt-4 border-t border-white/20">
          <div class="space-y-2">
            <p class="text-xs text-gray-300">
              <Link :href="route('login')" class="font-medium text-orange-400 hover:text-orange-300 underline">
                Retour à la connexion
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
  email: string;
  token: string;
}

const props = defineProps<Props>();

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('password.store'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>
