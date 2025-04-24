<template>
  <AppLayout title="Edit SEO Settings">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit SEO Settings
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <form @submit.prevent="submit">
            <div class="grid grid-cols-1 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700">Vitrin</label>
                <select
                  v-model="form.vitrin_id"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                  <option v-for="vitrin in vitrins" :key="vitrin.id" :value="vitrin.id">
                    {{ vitrin.name }}
                  </option>
                </select>
                <div v-if="form.errors.vitrin_id" class="text-red-500 text-sm mt-1">
                  {{ form.errors.vitrin_id }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">Meta Title</label>
                <input
                  type="text"
                  v-model="form.meta_title"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
                <div v-if="form.errors.meta_title" class="text-red-500 text-sm mt-1">
                  {{ form.errors.meta_title }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">Meta Description</label>
                <textarea
                  v-model="form.meta_description"
                  rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                ></textarea>
                <div v-if="form.errors.meta_description" class="text-red-500 text-sm mt-1">
                  {{ form.errors.meta_description }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">Meta Keywords</label>
                <input
                  type="text"
                  v-model="form.meta_keywords"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
                <div v-if="form.errors.meta_keywords" class="text-red-500 text-sm mt-1">
                  {{ form.errors.meta_keywords }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">Open Graph Title</label>
                <input
                  type="text"
                  v-model="form.og_title"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
                <div v-if="form.errors.og_title" class="text-red-500 text-sm mt-1">
                  {{ form.errors.og_title }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">Open Graph Description</label>
                <textarea
                  v-model="form.og_description"
                  rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                ></textarea>
                <div v-if="form.errors.og_description" class="text-red-500 text-sm mt-1">
                  {{ form.errors.og_description }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">Open Graph Image</label>
                <input
                  type="text"
                  v-model="form.og_image"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
                <div v-if="form.errors.og_image" class="text-red-500 text-sm mt-1">
                  {{ form.errors.og_image }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">Twitter Title</label>
                <input
                  type="text"
                  v-model="form.twitter_title"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
                <div v-if="form.errors.twitter_title" class="text-red-500 text-sm mt-1">
                  {{ form.errors.twitter_title }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">Twitter Description</label>
                <textarea
                  v-model="form.twitter_description"
                  rows="3"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                ></textarea>
                <div v-if="form.errors.twitter_description" class="text-red-500 text-sm mt-1">
                  {{ form.errors.twitter_description }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">Twitter Image</label>
                <input
                  type="text"
                  v-model="form.twitter_image"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
                <div v-if="form.errors.twitter_image" class="text-red-500 text-sm mt-1">
                  {{ form.errors.twitter_image }}
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">Index/Follow</label>
                <div class="mt-1">
                  <label class="inline-flex items-center">
                    <input
                      type="checkbox"
                      v-model="form.index_follow"
                      class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    <span class="ml-2">Allow search engines to index this page</span>
                  </label>
                </div>
                <div v-if="form.errors.index_follow" class="text-red-500 text-sm mt-1">
                  {{ form.errors.index_follow }}
                </div>
              </div>
            </div>

            <div class="mt-6 flex items-center justify-end">
              <Link
                :href="route('seo-settings.index')"
                class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-4"
              >
                Cancel
              </Link>
              <button
                type="submit"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150"
                :disabled="form.processing"
              >
                Update SEO Settings
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  seoSettings: Object,
  vitrins: Array,
});

const form = useForm({
  vitrin_id: props.seoSettings.vitrin_id,
  meta_title: props.seoSettings.meta_title,
  meta_description: props.seoSettings.meta_description,
  meta_keywords: props.seoSettings.meta_keywords,
  og_title: props.seoSettings.og_title,
  og_description: props.seoSettings.og_description,
  og_image: props.seoSettings.og_image,
  twitter_title: props.seoSettings.twitter_title,
  twitter_description: props.seoSettings.twitter_description,
  twitter_image: props.seoSettings.twitter_image,
  index_follow: props.seoSettings.index_follow,
});

const submit = () => {
  form.put(route('seo-settings.update', props.seoSettings.id));
};
</script> 