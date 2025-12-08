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

    <Head title="Inscription" />

    <div class="max-w-2xl w-full space-y-6 bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20 relative z-10">
      <!-- En-tête plus compact -->
      <div class="text-center">
        <h2 class="text-2xl font-bold text-white">
          Créer un compte
        </h2>
        <p class="mt-1 text-sm text-gray-300">
          Rejoignez la communauté ContesDuBénin
        </p>
      </div>

      <form class="space-y-4" @submit.prevent="submit">
        <!-- Première ligne : Nom, Prénom -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <div>
            <InputLabel for="nom" value="Nom" class="text-white text-sm" />
            <TextInput
              id="nom"
              type="text"
              class="mt-1 block w-full bg-white/5 border-white/20 text-white placeholder-gray-400 focus:border-orange-500 focus:ring-orange-500 text-sm py-2"
              v-model="form.nom"
              required
              autofocus
              autocomplete="family-name"
              placeholder="Votre nom"
            />
            <InputError class="mt-1" :message="form.errors.nom" />
          </div>

          <div>
            <InputLabel for="prenom" value="Prénom" class="text-white text-sm" />
            <TextInput
              id="prenom"
              type="text"
              class="mt-1 block w-full bg-white/5 border-white/20 text-white placeholder-gray-400 focus:border-orange-500 focus:ring-orange-500 text-sm py-2"
              v-model="form.prenom"
              required
              autocomplete="given-name"
              placeholder="Votre prénom"
            />
            <InputError class="mt-1" :message="form.errors.prenom" />
          </div>
        </div>

        <!-- Email seul -->
        <div>
          <InputLabel for="email" value="Email" class="text-white text-sm" />
          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full bg-white/5 border-white/20 text-white placeholder-gray-400 focus:border-orange-500 focus:ring-orange-500 text-sm py-2"
            v-model="form.email"
            required
            autocomplete="email"
            placeholder="votre@email.com"
          />
          <InputError class="mt-1" :message="form.errors.email" />
        </div>

        <!-- Deuxième ligne : Sexe, Date de naissance -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <div>
            <InputLabel for="sexe" value="Sexe" class="text-white text-sm" />
            <select
  id="sexe"
  v-model="form.sexe"
  class="mt-1 block w-full bg-white/5 border border-white/20 rounded-lg text-white placeholder-gray-400 focus:border-orange-500 focus:ring-orange-500 text-sm py-2 px-3"
>
  <option value="" disabled style="background-color: white; color: black;">Sélectionnez votre sexe</option>
  <option value="M" style="background-color: white; color: black;">Masculin</option>
  <option value="F" style="background-color: white; color: black;">Féminin</option>
</select>
            <InputError class="mt-1" :message="form.errors.sexe" />
          </div>

          <div>
            <InputLabel for="date_naissance" value="Date de naissance" class="text-white text-sm" />
            <TextInput
              id="date_naissance"
              type="date"
              class="mt-1 block w-full bg-white/5 border-white/20 text-white placeholder-gray-400 focus:border-orange-500 focus:ring-orange-500 text-sm py-2"
              v-model="form.date_naissance"
            />
            <InputError class="mt-1" :message="form.errors.date_naissance" />
          </div>
        </div>

        <!-- Mot de passe et confirmation -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <div>
            <InputLabel for="password" value="Mot de passe" class="text-white text-sm" />
            <TextInput
              id="password"
              type="password"
              class="mt-1 block w-full bg-white/5 border-white/20 text-white placeholder-gray-400 focus:border-orange-500 focus:ring-orange-500 text-sm py-2"
              v-model="form.password"
              required
              autocomplete="new-password"
              placeholder="Créez un mot de passe"
            />
            <InputError class="mt-1" :message="form.errors.password" />
          </div>

          <div>
            <InputLabel for="password_confirmation" value="Confirmation" class="text-white text-sm" />
            <TextInput
              id="password_confirmation"
              type="password"
              class="mt-1 block w-full bg-white/5 border-white/20 text-white placeholder-gray-400 focus:border-orange-500 focus:ring-orange-500 text-sm py-2"
              v-model="form.password_confirmation"
              required
              autocomplete="new-password"
              placeholder="Confirmez le mot de passe"
            />
            <InputError class="mt-1" :message="form.errors.password_confirmation" />
          </div>
        </div>

        <!-- Bouton d'inscription -->
        <div class="pt-2">
          <button
            type="submit"
            :disabled="form.processing"
            class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-full text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="form.processing" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Inscription...
            </span>
            <span v-else>
              S'inscrire
            </span>
          </button>
        </div>

        <!-- Lien vers la connexion -->
        <div class="text-center pt-3 border-t border-white/20">
          <p class="text-xs text-gray-300">
            Déjà un compte ?
            <Link :href="route('login')" class="font-medium text-orange-400 hover:text-orange-300 underline">
              Se connecter
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

const form = useForm({
  nom: '',
  prenom: '',
  email: '',
  sexe: '',
  date_naissance: '',
  password: '',
  password_confirmation: '',
  role_id: 1,
  langue_id: 1,
  statut: 'actif',
});

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>
