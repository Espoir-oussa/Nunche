<template>
    <Head title="Profil" />

    <DashboardLayout>
        <template #header>
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <img
                        :src="$page.props.auth.user.profile_photo_url || '/images/default-avatar.png'"
                        :alt="$page.props.auth.user.name"
                        class="w-12 h-12 rounded-full object-cover border-2 border-orange-200"
                    />
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        Mon Profil
                    </h2>
                    <p class="text-gray-600 mt-1">
                        Gérez vos informations personnelles et la sécurité de votre compte
                    </p>
                </div>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-4xl mx-auto space-y-8">
                <!-- En-tête du profil -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-6 py-6">
                        <div class="flex items-center space-x-6">
                            <div class="flex-shrink-0">
                                <img
                                    :src="$page.props.auth.user.profile_photo_url || '/images/default-avatar.png'"
                                    :alt="$page.props.auth.user.name"
                                    class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-lg"
                                />
                            </div>
                            <div class="flex-1">
                                <h1 class="text-2xl font-bold text-gray-900">
                                    {{ $page.props.auth.user.name }}
                                </h1>
                                <p class="text-gray-600 mt-1">
                                    {{ $page.props.auth.user.email }}
                                </p>
                                <div class="flex items-center mt-2 space-x-4">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <User class="w-4 h-4 mr-1" />
                                        Membre depuis {{ formatJoinDate($page.props.auth.user.created_at) }}
                                    </div>
                                    <div
                                        v-if="$page.props.auth.user.email_verified_at"
                                        class="flex items-center text-sm text-green-600"
                                    >
                                        <CheckCircle class="w-4 h-4 mr-1" />
                                        Email vérifié
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grille des sections -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Colonne de gauche -->
                    <div class="space-y-8">
                        <!-- Informations du profil -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <User class="w-5 h-5 mr-2 text-blue-600" />
                                    Informations personnelles
                                </h3>
                            </div>
                            <div class="p-6">
                                <UpdateProfileInformationForm
                                    :must-verify-email="mustVerifyEmail"
                                    :status="status"
                                />
                            </div>
                        </div>

                        <!-- Photo de profil -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-violet-50">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <Camera class="w-5 h-5 mr-2 text-purple-600" />
                                    Photo de profil
                                </h3>
                            </div>
                            <div class="p-6">
                                <UpdateProfilePhotoForm />
                            </div>
                        </div>
                    </div>

                    <!-- Colonne de droite -->
                    <div class="space-y-8">
                        <!-- Mot de passe -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <Lock class="w-5 h-5 mr-2 text-green-600" />
                                    Sécurité du compte
                                </h3>
                            </div>
                            <div class="p-6">
                                <UpdatePasswordForm />
                            </div>
                        </div>

                        <!-- Suppression du compte -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-pink-50">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <Trash2 class="w-5 h-5 mr-2 text-red-600" />
                                    Zone dangereuse
                                </h3>
                            </div>
                            <div class="p-6">
                                <DeleteUserForm />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UpdateProfilePhotoForm from './Partials/UpdateProfilePhotoForm.vue';
import { Head } from '@inertiajs/vue3';
import { User, CheckCircle, Camera, Lock, Trash2 } from 'lucide-vue-next';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const formatJoinDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long'
    });
};
</script>
