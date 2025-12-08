<template>
  <nav v-if="links && links.length" class="inline-flex -space-x-px">
    <button
      v-for="(link, i) in links"
      :key="i"
      :disabled="!link.url"
      @click="$emit('navigate', link.url)"
      v-html="link.label"
      :class="[
        'px-3 py-2 border text-sm font-medium',
        link.active ? 'bg-orange-500 text-white' : 'bg-white text-gray-700',
        !link.url ? 'opacity-50 cursor-not-allowed' : 'hover:bg-orange-100'
      ]"
    />
  </nav>
</template>

<script setup lang="ts">
defineProps<{ links: any[] }>()
</script>
