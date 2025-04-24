<template>
  <AppLayout title="SEO Settings">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        SEO Settings
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-medium text-gray-900">Manage SEO Settings</h3>
            <Link
              :href="route('seo-settings.create')"
              class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
            >
              Add New SEO Settings
            </Link>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Vitrin
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Meta Title
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Meta Description
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="setting in seoSettings" :key="setting.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    {{ setting.vitrin.name }}
                  </td>
                  <td class="px-6 py-4">
                    {{ setting.meta_title }}
                  </td>
                  <td class="px-6 py-4">
                    {{ setting.meta_description }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <Link
                      :href="route('seo-settings.edit', setting.id)"
                      class="text-indigo-600 hover:text-indigo-900 mr-4"
                    >
                      Edit
                    </Link>
                    <button
                      @click="destroy(setting.id)"
                      class="text-red-600 hover:text-red-900"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  seoSettings: Array,
});

const destroy = (id) => {
  if (confirm('Are you sure you want to delete these SEO settings?')) {
    router.delete(route('seo-settings.destroy', id));
  }
};
</script> 