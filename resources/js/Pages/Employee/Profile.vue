<template>
  <div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">My Profile</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Basic Info -->
      <div class="md:col-span-2">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
          <h2 class="text-2xl font-bold text-gray-900 mb-4">Basic Information</h2>
          <div class="space-y-4">
            <div>
              <label class="block text-sm text-gray-600">Username</label>
              <p class="text-lg font-medium text-gray-900">{{ user.username }}</p>
            </div>
            <div>
              <label class="block text-sm text-gray-600">Email</label>
              <p class="text-lg font-medium text-gray-900">{{ user.email }}</p>
            </div>
            <div>
              <label class="block text-sm text-gray-600">Phone</label>
              <p class="text-lg font-medium text-gray-900">{{ user.phone || 'Not provided' }}</p>
            </div>
          </div>
        </div>

        <!-- Education Section -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-900">Education</h2>
            <button
              @click="showEducationForm = !showEducationForm"
              class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
            >
              Add Education
            </button>
          </div>

          <div v-if="showEducationForm" class="bg-gray-50 p-6 rounded mb-6">
            <h3 class="text-lg font-bold mb-4">Add Education</h3>
            <form @submit.prevent="addEducation" class="space-y-4">
              <input
                v-model="educationForm.degree"
                type="text"
                placeholder="Degree"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
              />
              <input
                v-model="educationForm.institution"
                type="text"
                placeholder="Institution"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
              />
              <input
                v-model="educationForm.field_of_study"
                type="text"
                placeholder="Field of Study"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
              />
              <input
                v-model="educationForm.start_date"
                type="date"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
              />
              <input
                v-model="educationForm.end_date"
                type="date"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
              />
              <button
                type="submit"
                :disabled="submitting"
                class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white px-4 py-2 rounded"
              >
                {{ submitting ? 'Saving...' : 'Save Education' }}
              </button>
            </form>
          </div>

          <div class="space-y-4">
            <div v-for="edu in user.educations" :key="edu.id" class="border-l-4 border-blue-600 pl-4">
              <h3 class="font-bold text-gray-900">{{ edu.degree }} in {{ edu.field_of_study }}</h3>
              <p class="text-gray-600">{{ edu.institution }}</p>
              <p class="text-sm text-gray-500">{{ edu.start_date }} - {{ edu.end_date }}</p>
              <button
                @click="deleteEducation(edu.id)"
                class="text-red-600 hover:text-red-800 text-sm mt-2"
              >
                Delete
              </button>
            </div>
            <p v-if="!user.educations || user.educations.length === 0" class="text-gray-500">
              No education added yet.
            </p>
          </div>
        </div>

        <!-- Experience Section -->
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-900">Experience</h2>
            <button
              @click="showExperienceForm = !showExperienceForm"
              class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
            >
              Add Experience
            </button>
          </div>

          <div v-if="showExperienceForm" class="bg-gray-50 p-6 rounded mb-6">
            <h3 class="text-lg font-bold mb-4">Add Experience</h3>
            <form @submit.prevent="addExperience" class="space-y-4">
              <input
                v-model="experienceForm.title"
                type="text"
                placeholder="Job Title"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
              />
              <input
                v-model="experienceForm.company"
                type="text"
                placeholder="Company"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
              />
              <input
                v-model="experienceForm.start_date"
                type="date"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
              />
              <input
                v-model="experienceForm.end_date"
                type="date"
                placeholder="Leave empty if current"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
              />
              <label class="flex items-center">
                <input v-model="experienceForm.is_current" type="checkbox" class="mr-2" />
                <span>This is my current job</span>
              </label>
              <button
                type="submit"
                :disabled="submitting"
                class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white px-4 py-2 rounded"
              >
                {{ submitting ? 'Saving...' : 'Save Experience' }}
              </button>
            </form>
          </div>

          <div class="space-y-4">
            <div v-for="exp in user.experiences" :key="exp.id" class="border-l-4 border-green-600 pl-4">
              <h3 class="font-bold text-gray-900">{{ exp.title }}</h3>
              <p class="text-gray-600">{{ exp.company }}</p>
              <p class="text-sm text-gray-500">{{ exp.start_date }} - {{ exp.end_date || 'Present' }}</p>
              <button
                @click="deleteExperience(exp.id)"
                class="text-red-600 hover:text-red-800 text-sm mt-2"
              >
                Delete
              </button>
            </div>
            <p v-if="!user.experiences || user.experiences.length === 0" class="text-gray-500">
              No experience added yet.
            </p>
          </div>
        </div>
      </div>

      <!-- Sidebar: Skills and Stats -->
      <div>
        <div class="bg-white rounded-lg shadow p-6 sticky top-4">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Skills</h2>
          <div class="space-y-2 mb-4">
            <div
              v-for="skill in user.skills"
              :key="skill.id"
              class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm flex justify-between items-center"
            >
              <span>{{ skill.name }}</span>
              <button
                @click="deleteSkill(skill.id)"
                class="text-red-600 hover:text-red-800 ml-2"
              >
                ×
              </button>
            </div>
          </div>
          <p v-if="!user.skills || user.skills.length === 0" class="text-gray-500 text-sm mb-4">
            No skills added yet.
          </p>
          <button
            @click="showSkillForm = !showSkillForm"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
          >
            Add Skill
          </button>

          <div v-if="showSkillForm" class="mt-4 space-y-3">
            <input
              v-model="skillForm.name"
              type="text"
              placeholder="Skill name"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
            />
            <select v-model="skillForm.level" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
              <option value="">Select level</option>
              <option value="Beginner">Beginner</option>
              <option value="Intermediate">Intermediate</option>
              <option value="Expert">Expert</option>
            </select>
            <button
              @click="addSkill"
              :disabled="submitting"
              class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white px-3 py-2 rounded text-sm"
            >
              {{ submitting ? 'Saving...' : 'Add' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  user: Object,
});

const submitting = ref(false);
const showEducationForm = ref(false);
const showExperienceForm = ref(false);
const showSkillForm = ref(false);

const educationForm = ref({
  degree: '',
  institution: '',
  field_of_study: '',
  start_date: '',
  end_date: '',
});

const experienceForm = ref({
  title: '',
  company: '',
  start_date: '',
  end_date: '',
  is_current: false,
});

const skillForm = ref({
  name: '',
  level: '',
});

const addEducation = async () => {
  submitting.value = true;
  try {
    await fetch('/education', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
      },
      body: JSON.stringify(educationForm.value),
    });
    showEducationForm.value = false;
    educationForm.value = { degree: '', institution: '', field_of_study: '', start_date: '', end_date: '' };
    window.location.reload();
  } finally {
    submitting.value = false;
  }
};

const addExperience = async () => {
  submitting.value = true;
  try {
    await fetch('/experience', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
      },
      body: JSON.stringify(experienceForm.value),
    });
    showExperienceForm.value = false;
    experienceForm.value = { title: '', company: '', start_date: '', end_date: '', is_current: false };
    window.location.reload();
  } finally {
    submitting.value = false;
  }
};

const addSkill = async () => {
  submitting.value = true;
  try {
    await fetch('/skills', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
      },
      body: JSON.stringify(skillForm.value),
    });
    showSkillForm.value = false;
    skillForm.value = { name: '', level: '' };
    window.location.reload();
  } finally {
    submitting.value = false;
  }
};

const deleteEducation = (id) => {
  if (confirm('Delete this education?')) {
    fetch(`/education/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
      },
    }).then(() => window.location.reload());
  }
};

const deleteExperience = (id) => {
  if (confirm('Delete this experience?')) {
    fetch(`/experience/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
      },
    }).then(() => window.location.reload());
  }
};

const deleteSkill = (id) => {
  if (confirm('Delete this skill?')) {
    fetch(`/skills/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
      },
    }).then(() => window.location.reload());
  }
};
</script>
