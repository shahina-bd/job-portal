<template>
  <div class="container mx-auto px-4 py-8">
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-4">Browse Jobs</h1>
      
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <input
            v-model="filters.search"
            type="text"
            placeholder="Search by title..."
            class="px-4 py-2 border border-gray-300 rounded-lg"
            @input="applyFilters"
          />
          <select
            v-model="filters.category_id"
            class="px-4 py-2 border border-gray-300 rounded-lg"
            @change="applyFilters"
          >
            <option value="">All Categories</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
          </select>
          <select
            v-model="filters.job_type"
            class="px-4 py-2 border border-gray-300 rounded-lg"
            @change="applyFilters"
          >
            <option value="">All Types</option>
            <option value="full-time">Full-time</option>
            <option value="part-time">Part-time</option>
            <option value="remote">Remote</option>
          </select>
          <button
            @click="resetFilters"
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg"
          >
            Reset Filters
          </button>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
      <div
        v-for="job in filteredJobs"
        :key="job.id"
        class="bg-white rounded-lg shadow hover:shadow-lg transition p-6 cursor-pointer"
        @click="goToJob(job.id)"
      >
        <div class="flex justify-between items-start mb-3">
          <div>
            <h3 class="text-2xl font-bold text-gray-900">{{ job.title }}</h3>
            <p class="text-gray-600">{{ job.employer?.username || 'Unknown Company' }}</p>
          </div>
          <span :class="`px-3 py-1 rounded-full text-sm font-medium ${
            job.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
          }`">
            {{ job.status ? 'Open' : 'Closed' }}
          </span>
        </div>

        <p class="text-gray-700 mb-4">{{ truncate(job.job_description, 200) }}</p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4 text-sm">
          <div>
            <span class="text-gray-600">Type:</span>
            <p class="font-medium">{{ job.job_type }}</p>
          </div>
          <div>
            <span class="text-gray-600">Salary:</span>
            <p class="font-medium">{{ job.salary ? `${job.currency} ${number_format(job.salary, 0)}` : 'Not specified' }}</p>
          </div>
          <div>
            <span class="text-gray-600">Category:</span>
            <p class="font-medium">{{ job.category?.name }}</p>
          </div>
          <div>
            <span class="text-gray-600">Posted:</span>
            <p class="font-medium">{{ formatDate(job.created_at) }}</p>
          </div>
        </div>

        <div class="flex gap-4">
          <button
            @click.stop="applyForJob(job.id)"
            :disabled="hasApplied(job.id)"
            :class="`flex-1 px-4 py-2 rounded font-medium ${
              hasApplied(job.id)
                ? 'bg-gray-400 text-gray-700 cursor-not-allowed'
                : 'bg-blue-600 hover:bg-blue-700 text-white'
            }`"
          >
            {{ hasApplied(job.id) ? 'Already Applied' : 'Apply Now' }}
          </button>
          <Link :href="`/jobs/${job.id}`" class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-50 font-medium">
            View Details
          </Link>
        </div>
      </div>
    </div>

    <div v-if="filteredJobs.length === 0" class="bg-white rounded-lg shadow p-8 text-center">
      <p class="text-gray-600">No jobs found matching your criteria.</p>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
  jobs: Array,
  categories: Array,
  appliedJobs: Array,
});

const filters = ref({
  search: '',
  category_id: '',
  job_type: '',
});

const filteredJobs = computed(() => {
  return props.jobs.filter(job => {
    const matchesSearch = job.title.toLowerCase().includes(filters.value.search.toLowerCase());
    const matchesCategory = !filters.value.category_id || job.category_id == filters.value.category_id;
    const matchesType = !filters.value.job_type || job.job_type === filters.value.job_type;
    return matchesSearch && matchesCategory && matchesType;
  });
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString();
};

const truncate = (text, len) => {
  return text.length > len ? text.substring(0, len) + '...' : text;
};

const number_format = (num, decimals = 0) => {
  return num.toLocaleString('en-US', { maximumFractionDigits: decimals });
};

const hasApplied = (jobId) => {
  return props.appliedJobs.includes(jobId);
};

const goToJob = (jobId) => {
  window.location.href = `/jobs/${jobId}`;
};

const applyForJob = async (jobId) => {
  if (confirm('Apply for this job?')) {
    const response = await fetch(`/jobs/${jobId}/apply`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
      },
    });

    if (response.ok) {
      alert('Application submitted successfully!');
      window.location.reload();
    } else {
      const data = await response.json();
      alert(data.error || 'Failed to apply');
    }
  }
};

const applyFilters = () => {
  // Filters are reactive through computed property
};

const resetFilters = () => {
  filters.value = { search: '', category_id: '', job_type: '' };
};
</script>
