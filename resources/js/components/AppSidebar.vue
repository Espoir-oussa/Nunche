<script setup lang="ts">
// import cassé supprimé
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '#', // lien désactivé, route cassée
        icon: LayoutGrid,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <aside class="w-64 bg-gray-50 border-r flex flex-col h-full">
        <div class="p-4 border-b">
            <Link href="#" style="pointer-events: none; opacity: 0.5;">
                <AppLogo />
            </Link>
        </div>
        <div class="flex-1 overflow-y-auto">
            <NavMain :items="mainNavItems" />
        </div>
        <div class="border-t p-4">
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </div>
    </aside>
    <slot />
</template>
