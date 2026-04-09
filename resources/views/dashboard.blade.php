<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Job Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <!-- Navigation Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <div class="flex-shrink-0">
                <a href="/" class="text-xl font-bold text-blue-600 dark:text-blue-400">Job Portal</a>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-gray-700 dark:text-gray-300">Welcome, {{ auth()->user()->username }}!</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 font-medium">
                        Logout
                    </button>
                </form>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Dashboard</h1>
            <p class="text-gray-600 dark:text-gray-400">Welcome to your job portal dashboard</p>
        </div>

        <!-- User Info Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Your Profile</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Username:</span>
                    <p class="text-gray-900 dark:text-white">{{ auth()->user()->username }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Email:</span>
                    <p class="text-gray-900 dark:text-white">{{ auth()->user()->email }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Account Type:</span>
                    <p class="text-gray-900 dark:text-white">{{ ucfirst(auth()->user()->user_type) }}</p>
                </div>
                @if(auth()->user()->phone)
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone:</span>
                    <p class="text-gray-900 dark:text-white">{{ auth()->user()->phone }}</p>
                </div>
                @endif
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ auth()->user()->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                        {{ auth()->user()->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Member Since:</span>
                    <p class="text-gray-900 dark:text-white">{{ auth()->user()->created_at->format('M j, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Profile Management -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Profile Management</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Update your personal information and profile details.</p>
                <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Manage Profile
                </button>
            </div>

            <!-- Job Search (for job seekers) -->
            @if(auth()->user()->user_type === 'employee')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0V8a2 2 0 01-2 2H8a2 2 0 01-2-2V6m8 0H8m0 0V4"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Find Jobs</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Browse and apply for job opportunities.</p>
                <button class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Browse Jobs
                </button>
            </div>
            @endif

            <!-- Post Jobs (for employers) -->
            @if(auth()->user()->user_type === 'employer')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Post Jobs</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Create and manage job postings.</p>
                <button class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                    Post a Job
                </button>
            </div>
            @endif

            <!-- Applications (for job seekers) -->
            @if(auth()->user()->user_type === 'employee')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">My Applications</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Track your job applications and status.</p>
                <button class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                    View Applications
                </button>
            </div>
            @endif

            <!-- Company Management (for employers) -->
            @if(auth()->user()->user_type === 'employer')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Company Profile</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Manage your company information and settings.</p>
                <button class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Manage Company
                </button>
            </div>
            @endif

            <!-- Admin Panel (for admins) -->
            @if(auth()->user()->user_type === 'admin')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-red-100 dark:bg-red-900 rounded-lg">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Admin Panel</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Manage users, jobs, and system settings.</p>
                <a href="/admin/dashboard" class="block w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-center">
                    Admin Dashboard
                </a>
            </div>
            @endif
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Recent Activity</h2>
            <div class="space-y-4">
                <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Welcome to Job Portal!</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Your account has been successfully created.</p>
                    </div>
                    <div class="ml-auto text-sm text-gray-500 dark:text-gray-400">
                        {{ auth()->user()->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-sm text-gray-600 dark:text-gray-400">
                <p>&copy; 2026 Job Portal. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>