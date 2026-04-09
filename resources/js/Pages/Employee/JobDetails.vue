<template>
  <div class="container mx-auto px-4 py-8 max-w-4xl">
    <Link href="/jobs" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">← Back to Jobs</Link>
    
    <div class="bg-white rounded-lg shadow p-8">
      <div class="flex justify-between items-start mb-6">
        <div>
          <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ job.title }}</h1>
          <p class="text-xl text-gray-600">{{ job.employer?.username }}</p>
        </div>
        <span :class="`px-4 py-2 rounded-full text-lg font-medium ${
          job.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
        }`">
          {{ job.status ? 'Open' : 'Closed' }}
        </span>
      </div>

      <div class="grid grid-cols-2 md:grid-cols-5 gap-6 mb-8 pb-8 border-b">
        <div>
          <span class="text-gray-600 text-sm font-medium">Job Type</span>
          <p class="text-lg font-bold text-gray-900 mt-1">{{ job.job_type }}</p>
        </div>
        <div>
          <span class="text-gray-600 text-sm font-medium">Salary</span>
          <p class="text-lg font-bold text-gray-900 mt-1">
            {{ job.salary ? `${job.currency} ${number_format(job.salary)}` : 'Negotiable' }}
          </p>
        </div>
        <div>
          <span class="text-gray-600 text-sm font-medium">Category</span>
          <p class="text-lg font-bold text-gray-900 mt-1">{{ job.category?.name }}</p>
        </div>
        <div>
          <span class="text-gray-600 text-sm font-medium">Posted</span>
          <p class="text-lg font-bold text-gray-900 mt-1">{{ formatDate(job.created_at) }}</p>
        </div>
        <div>
          <span class="text-gray-600 text-sm font-medium">Applications</span>
          <p class="text-lg font-bold text-gray-900 mt-1">{{ job.applications?.length || 0 }}</p>
        </div>
      </div>

      <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Job Description</h2>
        <p class="text-gray-700 whitespace-pre-line">{{ job.job_description }}</p>
      </div>

      <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Requirements</h2>
        <p class="text-gray-700 whitespace-pre-line">{{ job.requirements }}</p>
      </div>

      <div class="mb-8 pb-8 border-b">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Deadline</h2>
        <p class="text-lg text-gray-700">
          Applications close on <strong>{{ formatDate(job.end_date) }}</strong>
        </p>
      </div>

      <div class="flex gap-4">
        <button
          @click="applyForJob"
          :disabled="hasApplied || !job.status"
          :class="`flex-1 px-6 py-3 rounded-lg font-bold text-lg transition ${
            hasApplied
              ? 'bg-gray-400 text-gray-700 cursor-not-allowed'
              : !job.status
              ? 'bg-gray-400 text-gray-700 cursor-not-allowed'
              : 'bg-blue-600 hover:bg-blue-700 text-white'
          }`"
        >
          {{ hasApplied ? 'Already Applied' : 'Apply for this Job' }}
        </button>
        <Link
          href="/jobs"
          class="px-6 py-3 rounded-lg font-bold text-lg border border-gray-300 hover:bg-gray-50 text-center"
        >
          Back to Jobs
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  job: Object,
  hasApplied: Boolean,
});

const applying = ref(false);

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};

const number_format = (num) => {
  return num.toLocaleString('en-US');
};

const applyForJob = async () => {
  if (!confirm('Submit your application for this job?')) {
    return;
  }

  applying.value = true;
  try {
    const response = await fetch(`/jobs/${props.job.id}/apply`, {
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
      alert(data.error || 'Failed to apply for this job');
    }
  } finally {
    applying.value = false;
  }
};
</script>
