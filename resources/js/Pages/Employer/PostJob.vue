<template>
  <div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ job ? 'Edit Job' : 'Post New Job' }}</h1>

    <form @submit.prevent="submitForm" class="bg-white rounded-lg shadow p-8">
      <div class="space-y-6">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Job Title <span class="text-red-600">*</span>
          </label>
          <input
            v-model="form.title"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            required
          />
          <p v-if="errors.title" class="text-red-600 text-sm mt-1">{{ errors.title }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Category <span class="text-red-600">*</span>
          </label>
          <select v-model="form.category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            <option value="">Select category</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
          </select>
          <p v-if="errors.category_id" class="text-red-600 text-sm mt-1">{{ errors.category_id }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Job Description <span class="text-red-600">*</span>
          </label>
          <textarea
            v-model="form.job_description"
            rows="6"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            required
          ></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Requirements <span class="text-red-600">*</span>
          </label>
          <textarea
            v-model="form.requirements"
            rows="4"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            required
          ></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Job Type</label>
            <select v-model="form.job_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
              <option value="full-time">Full-time</option>
              <option value="part-time">Part-time</option>
              <option value="remote">Remote</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Salary</label>
            <input
              v-model="form.salary"
              type="number"
              placeholder="Optional"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg"
            />
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Publish Date <span class="text-red-600">*</span>
            </label>
            <input
              v-model="form.publish_date"
              type="date"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg"
              required
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              End Date <span class="text-red-600">*</span>
            </label>
            <input
              v-model="form.end_date"
              type="date"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg"
              required
            />
          </div>
        </div>

        <div class="flex gap-4">
          <button
            type="submit"
            :disabled="submitting"
            class="flex-1 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white px-4 py-2 rounded-lg font-medium"
          >
            {{ submitting ? 'Posting...' : (job ? 'Update Job' : 'Post Job') }}
          </button>
          <Link href="/jobs" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-900 px-4 py-2 rounded-lg font-medium text-center">
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
  job: Object,
  categories: Array,
});

const submitting = ref(false);
const errors = ref({});

const form = ref({
  title: props.job?.title || '',
  category_id: props.job?.category_id || '',
  job_description: props.job?.job_description || '',
  requirements: props.job?.requirements || '',
  job_type: props.job?.job_type || 'full-time',
  salary: props.job?.salary || '',
  publish_date: props.job?.publish_date || new Date().toISOString().split('T')[0],
  end_date: props.job?.end_date || '',
});

const submitForm = async () => {
  submitting.value = true;
  errors.value = {};

  try {
    const method = props.job ? 'PATCH' : 'POST';
    const url = props.job ? `/jobs/${props.job.id}` : `/jobs`;
    
    const response = await fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
      },
      body: JSON.stringify(form.value),
    });

    if (response.ok) {
      window.location.href = '/jobs';
    } else {
      const data = await response.json();
      errors.value = data.errors || {};
    }
  } finally {
    submitting.value = false;
  }
};
</script>
