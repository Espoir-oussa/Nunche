<script setup lang="ts">
import AlertError from '@/components/AlertError.vue';
import InputError from '@/components/InputError.vue';
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth';
// import supprimé : module inexistant
// Remplacer confirm par une fonction factice
function confirm() {
    // À adapter selon votre backend
    return '/api/two-factor/confirm';
}
import { Form } from '@inertiajs/vue3';
import { useClipboard } from '@vueuse/core';
import { Check, Copy, ScanLine } from 'lucide-vue-next';
import { computed, nextTick, ref, useTemplateRef, watch } from 'vue';

interface Props {
    requiresConfirmation: boolean;
    twoFactorEnabled: boolean;
}

const props = defineProps<Props>();
const isOpen = defineModel<boolean>('isOpen');

const { copy, copied } = useClipboard();
const { qrCodeSvg, manualSetupKey, clearSetupData, fetchSetupData, errors } =
    useTwoFactorAuth();

const showVerificationStep = ref(false);
const code = ref<number[]>([]);
const codeValue = computed<string>(() => code.value.join(''));

const pinInputContainerRef = useTemplateRef('pinInputContainerRef');

const modalConfig = computed<{
    title: string;
    description: string;
    buttonText: string;
}>(() => {
    if (props.twoFactorEnabled) {
        return {
            title: 'Two-Factor Authentication Enabled',
            description:
                'Two-factor authentication is now enabled. Scan the QR code or enter the setup key in your authenticator app.',
            buttonText: 'Close',
        };
    }

    if (showVerificationStep.value) {
        return {
            title: 'Verify Authentication Code',
            description: 'Enter the 6-digit code from your authenticator app',
            buttonText: 'Continue',
        };
    }

    return {
        title: 'Enable Two-Factor Authentication',
        description:
            'To finish enabling two-factor authentication, scan the QR code or enter the setup key in your authenticator app',
        buttonText: 'Continue',
    };
});

const handleModalNextStep = () => {
    if (props.requiresConfirmation) {
        showVerificationStep.value = true;

        nextTick(() => {
            pinInputContainerRef.value?.querySelector('input')?.focus();
        });

        return;
    }

    clearSetupData();
    isOpen.value = false;
};

const resetModalState = () => {
    if (props.twoFactorEnabled) {
        clearSetupData();
    }

    showVerificationStep.value = false;
    code.value = [];
};

watch(
    () => isOpen.value,
    async (isOpen) => {
        if (!isOpen) {
            resetModalState();
            return;
        }

        if (!qrCodeSvg.value) {
            await fetchSetupData();
        }
    },
);
</script>

<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <div class="flex items-center justify-center mb-3">
                <ScanLine class="size-6 text-foreground" />
            </div>
            <h2 class="text-lg font-bold text-center mb-2">{{ modalConfig.title }}</h2>
            <p class="text-center mb-4">{{ modalConfig.description }}</p>
            <div class="relative flex w-auto flex-col items-center justify-center space-y-5">
                <template v-if="!showVerificationStep">
                    <AlertError v-if="errors?.length" :errors="errors" />
                    <template v-else>
                        <div class="relative mx-auto flex max-w-md items-center overflow-hidden">
                            <div class="relative mx-auto aspect-square w-64 overflow-hidden rounded-lg border border-border">
                                <div v-if="!qrCodeSvg" class="absolute inset-0 z-10 flex aspect-square h-auto w-full animate-pulse items-center justify-center bg-background">
                                    <!-- Spinner remplacé par texte -->
                                    <span>Chargement...</span>
                                </div>
                                <div v-else class="relative z-10 overflow-hidden border p-5">
                                    <div v-html="qrCodeSvg" class="flex aspect-square size-full items-center justify-center" />
                                </div>
                            </div>
                        </div>
                        <div class="flex w-full items-center space-x-5">
                            <button class="w-full px-4 py-2 rounded bg-gray-200" @click="handleModalNextStep">
                                {{ modalConfig.buttonText }}
                            </button>
                        </div>
                        <div class="relative flex w-full items-center justify-center">
                            <div class="absolute inset-0 top-1/2 h-px w-full bg-border" />
                            <span class="relative bg-card px-2 py-1">or, enter the code manually</span>
                        </div>
                        <div class="flex w-full items-center justify-center space-x-2">
                            <div class="flex w-full items-stretch overflow-hidden rounded-xl border border-border">
                                <div v-if="!manualSetupKey" class="flex h-full w-full items-center justify-center bg-muted p-3">
                                    <span>Chargement...</span>
                                </div>
                                <template v-else>
                                    <input type="text" readonly :value="manualSetupKey" class="h-full w-full bg-background p-3 text-foreground" />
                                    <button @click="copy(manualSetupKey || '')" class="relative block h-auto border-l border-border px-3 hover:bg-muted">
                                        <Check v-if="copied" class="w-4 text-green-500" />
                                        <Copy v-else class="w-4" />
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </template>
                <template v-else>
                    <form @submit.prevent="confirm.form().submit()" class="space-y-6">
                        <input type="hidden" name="code" :value="codeValue" />
                        <div ref="pinInputContainerRef" class="relative w-full space-y-3">
                            <div class="flex w-full flex-col items-center justify-center space-y-3 py-2">
                                <!-- Remplacement du PinInput par 6 inputs natifs -->
                                <div class="flex gap-2">
                                    <input v-for="(id, index) in 6" :key="id" type="text" maxlength="1" v-model="code[index]" class="w-10 h-10 text-center border rounded" :disabled="processing" />
                                </div>
                                <InputError :message="errors?.confirmTwoFactorAuthentication?.code" />
                            </div>
                            <div class="flex w-full items-center space-x-5">
                                <button type="button" class="w-auto flex-1 px-4 py-2 rounded bg-gray-200" @click="showVerificationStep = false" :disabled="processing">Back</button>
                                <button type="submit" class="w-auto flex-1 px-4 py-2 rounded bg-blue-600 text-white" :disabled="processing || codeValue.length < 6">Confirm</button>
                            </div>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
</template>
