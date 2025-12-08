<script setup lang ="ts" lang="ts">
import { computed } from 'vue';

interface Props {
    errors: string[];
    title?: string;
}

const props = withDefaults(defineProps<Props>(), {
    title: 'Something went wrong.',
});

const uniqueErrors = computed(() => Array.from(new Set(props.errors)));
</script>

<template>
    <div class="border border-red-300 bg-red-50 rounded p-3 flex flex-col gap-2">
        <div class="flex items-center gap-2">
            <AlertCircle class="size-4" />
            <span class="font-bold text-red-700">{{ title }}</span>
        </div>
        <ul class="list-inside list-disc text-sm text-red-700">
            <li v-for="(error, index) in uniqueErrors" :key="index">
                {{ error }}
            </li>
        </ul>
    </div>
</template>
