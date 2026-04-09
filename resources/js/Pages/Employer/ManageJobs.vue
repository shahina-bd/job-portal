<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Posted Jobs</h1>

    <div class="grid grid-cols-1 gap-6">
      <div v-for="job in jobs" :key="job.id" class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-4">
          <div>
            <h3 class="text-xl font-bold text-gray-900">{{ job.title }}</h3>
            <p class="text-gray-600 text-sm mt-1">Category: {{ job.category?.name }}</p>
          </div>
          <span :class="`px-3 py-1 rounded-full text-sm font-medium ${
            job.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
          }`">
            {{ job.status ? 'Active' : 'Closed' }}
          </span>
        </div>

        <p class="text-gray-700 mb-4">{{ truncate(job.job_description, 150) }}</p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4 text-sm">
          <div>
            <span class="text-gray-600">Type:</span>
            <p class="font-medium">{{ job.job_type }}</p>
          </div>
          <div>
            <span class="text-gray-600">Salary:</span>
            <p class="font-medium">{{ job.salary ? `${job.currency} ${job.salary}` : 'Negotiable' }}</p>
          </div>
          <div>
            <span class="text-gray-600">Posted:</span>
            <p class="font-medium">{{ formatDate(job.created_at) }}</p>
          </div>
          <div>
            <span class="text-gray-600">Applications:</span>
            <p class="font-medium">{{ job.applications?.length || 0 }}</p>
          </div>
        </div>

        <div class="flex gap-4">
          <Link :href="`/jobs/${job.id}`" class="text-blue-600 hover:text-blue-800 font-medium">
            View Details
          </Link>
          <Link :href="`/jobs/${job.id}/edit`" class="text-blue-600 hover:text-blue-800 font-medium">
            Edit
          </Link>
        <button @click="deleteJob(job.id)" class="bg-red-500 text-white px-2 py-1">
          Delete
        </button>
        <button @click="toggleStatus(job.id, job.status)" class="bg-gray-500 text-white px-2 py-1">
          Toggle Status
        </button>
        </div>
      </div>
    </div>

    <div v-if="jobs.length === 0" class="bg-white rounded-lg shadow p-8 text-center">
      <p class="text-gray-600 mb-4">No jobs posted yet.</p>
      <Link href="/jobs/create" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
        Post Your First Job
      </Link>
    </div>
  </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
  jobs: Array,
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString();
};

const truncate = (text, len) => {
  return text?.length > len ? text.substring(0, len) + '...' : text;
};

const deleteJob = (jobId) => {
  if (confirm('Are you sure?')) {
    router.delete(`/jobs/${jobId}`);
  }
};

const toggleStatus = (jobId, status) => {
  router.put(`/jobs/${jobId}/toggle`, {
    status: !status
  });
};
</script>