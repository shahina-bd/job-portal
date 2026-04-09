<template>
  <div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Create or Edit Company</h1>

    <form @submit.prevent="submitForm" class="bg-white rounded-lg shadow p-8">
      <div class="space-y-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Company Name <span class="text-red-600">*</span>
          </label>
          <input
            v-model="form.name"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            required
          />
          <p v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Company Type</label>
          <input
            v-model="form.company_type"
            type="text"
            placeholder="e.g., Tech, Finance, Healthcare"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Website URL</label>
          <input
            v-model="form.website_url"
            type="url"
            placeholder="https://example.com"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Company Size</label>
          <select v-model="form.company_size" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            <option value="">Select size</option>
            <option value="1-10">1-10</option>
            <option value="11-50">11-50</option>
            <option value="51-200">51-200</option>
            <option value="201-500">201-500</option>
            <option value="500+">500+</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
          <textarea
            v-model="form.company_description"
            rows="4"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
          ></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
          <select v-model="form.country_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            <option value="">Select country</option>
            <option v-for="country in countries" :key="country.id" :value="country.id">
              {{ country.name }}
            </option>
          </select>
        </div>

        <div class="flex gap-4">
          <button
            type="submit"
            :disabled="submitting"
            class="flex-1 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white px-4 py-2 rounded-lg font-medium"
          >
            {{ submitting ? 'Saving...' : 'Save Company' }}
          </button>
          <Link href="/dashboard" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-900 px-4 py-2 rounded-lg font-medium text-center">
            Cancel
          </Link>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  company: Object,
  countries: Array,
});

const submitting = ref(false);
const errors = ref({});

const form = ref({
  name: props.company?.name || '',
  company_type: props.company?.company_type || '',
  website_url: props.company?.website_url || '',
  company_size: props.company?.company_size || '',
  company_description: props.company?.company_description || '',
  country_id: props.company?.country_id || '',
});

const submitForm = async () => {
  submitting.value = true;
  errors.value = {};

  try {
    const method = props.company ? 'PATCH' : 'POST';
    const url = props.company ? `/company` : `/company`;
    
    const response = await fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
      },
      body: JSON.stringify(form.value),
    });

    if (response.ok) {
      window.location.href = '/dashboard';
    } else {
      const data = await response.json();
      errors.value = data.errors || {};
    }
  } finally {
    submitting.value = false;
  }
};
</script>
