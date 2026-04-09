<template>
  <div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-500 text-sm font-medium">Posted Jobs</h3>
        <p class="mt-2 text-3xl font-bold text-gray-900">{{ jobs.length }}</p>
      </div>
      
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-500 text-sm font-medium">Total Applications</h3>
        <p class="mt-2 text-3xl font-bold text-gray-900">{{ applicationCount }}</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-500 text-sm font-medium">Company</h3>
        <p class="mt-2 text-lg font-semibold text-gray-900">{{ company?.name || 'Not Set' }}</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <Link href="/company" class="text-blue-600 hover:text-blue-800 font-medium">
          Edit Company →
        </Link>
      </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Recent Jobs</h2>
        <Link href="/jobs/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
          Post New Job
        </Link>
      </div>

      <table class="w-full">
        <thead class="bg-gray-50 border-b">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Title</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Type</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Posted</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Applications</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="job in jobs" :key="job.id" class="border-b hover:bg-gray-50">
            <td class="px-6 py-4 text-sm text-gray-900">{{ job.title }}</td>
            <td class="px-6 py-4 text-sm text-gray-600">{{ job.job_type }}</td>
            <td class="px-6 py-4 text-sm text-gray-600">{{ formatDate(job.created_at) }}</td>
            <td class="px-6 py-4 text-sm text-gray-900 font-medium">{{ job.applications?.length || 0 }}</td>
            <td class="px-6 py-4 text-sm">
              <Link :href="`/jobs/${job.id}/edit`" class="text-blue-600 hover:text-blue-800 mr-4">
                Edit
              </Link>
              <a href="#" @click.prevent="deleteJob(job.id)" class="text-red-600 hover:text-red-800">
                Delete
              </a>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="jobs.length === 0" class="text-center py-8 text-gray-500">
        No jobs posted yet. Start by posting your first job!
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  jobs: Array,
  company: Object,
});

const applicationCount = computed(() => {
  return props.jobs.reduce((total, job) => total + (job.applications?.length || 0), 0);
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString();
};

const deleteJob = (jobId) => {
  if (confirm('Are you sure?')) {
    // dispatch delete action
  }
};
</script>
