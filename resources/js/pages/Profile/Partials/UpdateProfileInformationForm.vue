<template>
    <section class="space-y-6">
        <form @submit.prevent="form.patch(route('profile.update'))" class="space-y-6">
            <!-- Nom -->
            <div>
                <InputLabel for="name" value="Nom complet" class="text-sm font-medium text-gray-700" />
                <div class="mt-2 relative">
                    <User class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full pl-10"
                        :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors.name }"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Votre nom complet"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- Email -->
            <div>
                <InputLabel for="email" value="Adresse email" class="text-sm font-medium text-gray-700" />
                <div class="mt-2 relative">
                    <Mail class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full pl-10"
                        :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors.email }"
                        v-model="form.email"
                        required
                        autocomplete="username"
                        placeholder="votre@email.com"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- Vérification email -->
            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center">
                        <AlertCircle class="w-5 h-5 text-yellow-600 mr-2" />
                        <p class="text-sm text-yellow-800">
                            Votre adresse email n'est pas vérifiée.
                        </p>
                    </div>
                    <div class="mt-2">
                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="text-sm text-yellow-700 underline hover:text-yellow-800"
                        >
                            Cliquez ici pour renvoyer l'email de vérification.
                        </Link>
                    </div>
                    <div
                        v-show="status === 'verification-link-sent'"
                        class="mt-2 text-sm font-medium text-green-600 flex items-center"
                    >
                        <CheckCircle class="w-4 h-4 mr-1" />
                        Un nouveau lien de vérification a été envoyé à votre adresse email.
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4 pt-4">
                <PrimaryButton
                    :disabled="form.processing"
                    class="!px-6 !py-2"
                >
                    <Save class="w-4 h-4 mr-2" />
                    <span v-if="form.processing">Enregistrement...</span>
                    <span v-else>Enregistrer les modifications</span>
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-green-600 flex items-center"
                    >
                        <CheckCircle class="w-4 h-4 mr-1" />
                        Modifications enregistrées.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>

<script setup lang ="ts">
import InputError from '@/components/InputError.vue';
import InputLabel from '@/components/InputLabel.vue';
import PrimaryButton from '@/components/PrimaryButton.vue';
import TextInput from '@/components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { User, Mail, Save, CheckCircle, AlertCircle } from 'lucide-vue-next';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>
