<script setup lang="ts">
import { urlIsActive } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
    items: NavItem[];
}>();

const page = usePage();
</script>

<template>
    <div class="px-2 py-0">
        <span class="font-bold mb-2 block">Platform</span>
        <ul>
            <li v-for="item in items" :key="item.title" class="mb-1">
                <Link :href="item.href" :class="urlIsActive(item.href, page.url) ? 'font-bold text-blue-600' : ''">
                    <component :is="item.icon" />
                    <span>{{ item.title }}</span>
                </Link>
            </li>
        </ul>
    </div>
</template>
