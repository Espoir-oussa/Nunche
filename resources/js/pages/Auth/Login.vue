<template>
  <div class="min-h-screen relative flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 overflow-hidden">
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

    <Head title="Connexion" />

    <div class="max-w-md w-full space-y-8 bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/20 relative z-10">
      <!-- En-tête -->
      <div class="text-center">
        <h2 class="mt-6 text-3xl font-bold text-white">
          Connectez-vous
        </h2>
        <p class="mt-2 text-sm text-gray-300">
          Accédez à votre compte ContesDuBénin
        </p>
      </div>

      <!-- Message de statut -->
      <div v-if="status" class="bg-green-500/20 border border-green-500/50 rounded-lg p-4">
        <p class="text-green-400 text-sm text-center">{{ status }}</p>
      </div>

      <form class="mt-8 space-y-6" @submit.prevent="submit">
        <!-- Email -->
        <div>
          <InputLabel for="email" value="Email" class="text-white" />
          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full bg-white/5 border-white/20 text-white placeholder-gray-400 focus:border-orange-500 focus:ring-orange-500"
            v-model="form.email"
            required
            autofocus
            autocomplete="email"
            placeholder="votre@email.com"
          />
          <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <!-- Mot de passe -->
        <div>
          <InputLabel for="password" value="Mot de passe" class="text-white" />
          <TextInput
            id="password"
            type="password"
            class="mt-1 block w-full bg-white/5 border-white/20 text-white placeholder-gray-400 focus:border-orange-500 focus:ring-orange-500"
            v-model="form.password"
            required
            autocomplete="current-password"
            placeholder="Votre mot de passe"
          />
          <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <!-- Se souvenir de moi et Mot de passe oublié sur la même ligne -->
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <Checkbox
              name="remember"
              v-model:checked="form.remember"
              class="bg-white/5 border-white/20 text-orange-500 focus:ring-orange-500"
            />
            <InputLabel for="remember" value="Se souvenir de moi" class="ms-2 text-sm text-white font-medium" />
          </div>

          <Link
            v-if="canResetPassword"
            :href="route('password.request')"
            class="text-sm text-orange-400 hover:text-orange-300 underline"
          >
            Mot de passe oublié ?
          </Link>
        </div>

        <!-- Bouton de connexion -->
        <div>
          <button
            type="submit"
            :disabled="form.processing"
            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-full text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="form.processing" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Connexion...
            </span>
            <span v-else>
              Se connecter
            </span>
          </button>
        </div>

        <!-- Lien vers l'inscription -->
        <div class="text-center">
          <p class="text-sm text-gray-300">
            Pas encore de compte ?
            <Link :href="route('register')" class="font-medium text-orange-400 hover:text-orange-300 underline">
              Créer un compte
            </Link>
          </p>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang ="ts" lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import Checkbox from '@/components/Checkbox.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import TextInput from '@/components/TextInput.vue';

defineProps({
  canResetPassword: {
    type: Boolean,
    default: false,
  },
  status: {
    type: String,
  },
});

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>
