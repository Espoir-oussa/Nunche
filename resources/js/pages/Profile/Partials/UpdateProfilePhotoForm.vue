<template>
    <section class="space-y-6">
        <div class="flex items-center space-x-6">
            <!-- Photo actuelle -->
            <div class="flex-shrink-0">
                <img
                    :src="$page.props.auth.user.profile_photo_url || '/images/default-avatar.png'"
                    :alt="$page.props.auth.user.name"
                    class="w-20 h-20 rounded-full object-cover border-2 border-gray-300"
                />
            </div>

            <!-- Actions photo -->
            <div class="flex-1">
                <div class="space-y-4">
                    <!-- Téléchargement -->
                    <div>
                        <InputLabel for="photo" value="Nouvelle photo" class="text-sm font-medium text-gray-700" />
                        <div class="mt-2 flex items-center space-x-4">
                            <input
                                id="photo"
                                ref="photoInput"
                                type="file"
                                class="hidden"
                                @change="updatePhotoPreview"
                            >
                            <PrimaryButton
                                type="button"
                                class="!px-4 !py-2 text-sm"
                                @click="selectNewPhoto"
                            >
                                <Upload class="w-4 h-4 mr-2" />
                                Choisir une photo
                            </PrimaryButton>

                            <SecondaryButton
                                v-if="$page.props.auth.user.profile_photo_path"
                                type="button"
                                class="!px-4 !py-2 text-sm"
                                @click="deletePhoto"
                            >
                                <Trash2 class="w-4 h-4 mr-2" />
                                Supprimer
                            </SecondaryButton>
                        </div>

                        <!-- Aperçu de la nouvelle photo -->
                        <div v-if="photoPreview" class="mt-4">
                            <span class="block text-sm font-medium text-gray-700 mb-2">
                                Aperçu de la nouvelle photo :
                            </span>
                            <div class="flex items-center space-x-4">
                                <img :src="photoPreview" class="w-16 h-16 rounded-full object-cover border-2 border-orange-300">
                                <SecondaryButton
                                    type="button"
                                    class="!px-3 !py-1 text-xs"
                                    @click="clearPhotoFile"
                                >
                                    Annuler
                                </SecondaryButton>
                            </div>
                        </div>

                        <InputError :message="form.errors.photo" class="mt-2" />
                    </div>

                    <!-- Bouton de sauvegarde -->
                    <div v-if="form.photo" class="flex items-center space-x-4">
                        <PrimaryButton
                            :disabled="form.processing"
                            @click="updateProfilePhoto"
                        >
                            <Save class="w-4 h-4 mr-2" />
                            <span v-if="form.processing">Enregistrement...</span>
                            <span v-else>Enregistrer la photo</span>
                        </PrimaryButton>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p
                                v-if="form.recentlySuccessful"
                                class="text-sm text-green-600"
                            >
                                Photo enregistrée.
                            </p>
                        </Transition>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-xs text-gray-500 mt-4">
            Formats acceptés : JPG, PNG, GIF. Taille maximale : 2MB.
            Recommandé : photo carrée de 256x256 pixels minimum.
        </p>
    </section>
</template>

<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Upload, Trash2, Save } from 'lucide-vue-next';

const form = useForm({
    photo: null,
});

const photoInput = ref(null);
const photoPreview = ref(null);

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    form.photo = photo;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const clearPhotoFile = () => {
    photoInput.value.value = null;
    photoPreview.value = null;
    form.photo = null;
};

import { router } from '@inertiajs/vue3';

const updateProfilePhoto = () => {
    if (!form.photo) {
        return;
    }

    form.post(route('profile-photo.update'), {
        preserveScroll: true,
        onSuccess: () => {
            clearPhotoFile();
            router.reload({ only: ['auth'] });
        },
    });
};

const deletePhoto = () => {
    form.delete(route('profile-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            clearPhotoFile();
            router.reload({ only: ['auth'] });
        },
    });
};
</script>
