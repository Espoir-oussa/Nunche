<script setup lang ="ts" lang="ts">
import UserInfo from '@/components/UserInfo.vue';
import { usePage } from '@inertiajs/vue3';
import { ChevronsUpDown } from 'lucide-vue-next';
import UserMenuContent from './UserMenuContent.vue';

const page = usePage();
const user = page.props.auth.user;
</script>

<template>
    <div>
        <div>
            <button class="w-full flex items-center gap-2 px-4 py-2 rounded bg-gray-100">
                <UserInfo :user="user" />
                <ChevronsUpDown class="ml-auto size-4" />
            </button>
            <div class="min-w-56 rounded-lg mt-2">
                <UserMenuContent :user="user" />
            </div>
        </div>
    </div>
</template>
