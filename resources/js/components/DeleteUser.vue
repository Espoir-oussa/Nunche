<script setup lang="ts">
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import { Form } from '@inertiajs/vue3';
import { useTemplateRef } from 'vue';

// Components
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';

const passwordInput = useTemplateRef('passwordInput');
</script>

<template>
    <div class="space-y-6">
        <HeadingSmall
            title="Delete account"
            description="Delete your account and all of its resources"
        />
        <div class="space-y-4 rounded-lg border border-red-100 bg-red-50 p-4">
            <div class="relative space-y-0.5 text-red-600">
                <p class="font-medium">Warning</p>
                <p class="text-sm">
                    Please proceed with caution, this cannot be undone.
                </p>
            </div>
            <!-- Remplacement du Dialog par une modale simple -->
            <div>
                <form @submit.prevent="ProfileController.destroy.form().submit()" class="space-y-6">
                    <div class="grid gap-2">
                        <label for="password" class="sr-only">Password</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            ref="passwordInput"
                            placeholder="Password"
                            class="border rounded px-3 py-2 w-full"
                        />
                        <InputError :message="ProfileController.destroy.form().errors.password" />
                    </div>
                    <div class="flex gap-2">
                        <button type="button" @click="ProfileController.destroy.form().reset()" class="px-4 py-2 rounded bg-gray-200">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white">Delete account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
