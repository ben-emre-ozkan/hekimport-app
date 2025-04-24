<template>
  <div>
    <div class="min-h-screen bg-gray-100">
      <nav class="bg-white border-b border-gray-100">
        <!-- ... existing navigation code ... -->
      </nav>

      <!-- Page Heading -->
      <header class="bg-white shadow" v-if="$slots.header">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <!-- Page Content -->
      <main>
        <slot />
      </main>
    </div>

    <Analytics :analytics-id="analyticsId" />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Analytics from '@/Components/Analytics.vue';

const analyticsId = ref(import.meta.env.VITE_GOOGLE_ANALYTICS_ID);
</script> 