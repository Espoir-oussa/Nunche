<template>
    <section class="space-y-6">
        <form @submit.prevent="updatePassword" class="space-y-6">
            <!-- Mot de passe actuel -->
            <div>
                <InputLabel for="current_password" value="Mot de passe actuel" class="text-sm font-medium text-gray-700" />
                <div class="mt-2 relative">
                    <Lock class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                    <TextInput
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        type="password"
                        class="mt-1 block w-full pl-10"
                        :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors.current_password }"
                        autocomplete="current-password"
                        placeholder="Votre mot de passe actuel"
                    />
                </div>
                <InputError :message="form.errors.current_password" class="mt-2" />
            </div>

            <!-- Nouveau mot de passe -->
            <div>
                <InputLabel for="password" value="Nouveau mot de passe" class="text-sm font-medium text-gray-700" />
                <div class="mt-2 relative">
                    <Lock class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full pl-10"
                        :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors.password }"
                        autocomplete="new-password"
                        placeholder="Votre nouveau mot de passe"
                    />
                </div>
                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <!-- Confirmation -->
            <div>
                <InputLabel for="password_confirmation" value="Confirmer le mot de passe" class="text-sm font-medium text-gray-700" />
                <div class="mt-2 relative">
                    <Lock class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="mt-1 block w-full pl-10"
                        :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': form.errors.password_confirmation }"
                        autocomplete="new-password"
                        placeholder="Confirmez votre nouveau mot de passe"
                    />
                </div>
                <InputError :message="form.errors.password_confirmation" class="mt-2" />
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4 pt-4">
                <PrimaryButton
                    :disabled="form.processing"
                    class="!px-6 !py-2"
                >
                    <Save class="w-4 h-4 mr-2" />
                    <span v-if="form.processing">Mise à jour...</span>
                    <span v-else>Mettre à jour le mot de passe</span>
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
                        Mot de passe mis à jour.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>

<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Lock, Save, CheckCircle } from 'lucide-vue-next';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>
