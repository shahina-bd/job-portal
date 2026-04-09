<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard - Job Portal</title>
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
                <span class="text-gray-700 dark:text-gray-300">Welcome, {{ auth()->user()->username ?? auth()->user()->name }}!</span>
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
            <p class="text-gray-600 dark:text-gray-400">Manage your company and job postings</p>
        </div>

        @if(auth()->check() && auth()->user()->user_type === 'employer')
        <!-- Company Status Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Company Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $jobPostsCount ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Active Job Posts</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $applicationsCount ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Applications</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pendingApplications ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Pending Reviews</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $hiredCount ?? 0 }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Candidates Hired</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

            <!-- Post New Job -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Post New Job</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Create a new job posting to attract qualified candidates.</p>
               @if(auth()->check() && auth()->user()->user_type === 'employer')
                <a href="{{ url('/jobs/create') }}"
                class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Create Job Post
                </a>
            @endif
            </div>

            <!-- Manage Job Posts -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Manage Job Posts</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">View and edit your existing job postings.</p>
                <a href="{{ url('/profile/jobs') }}" 
                   class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                   View All Jobs
                </a>
            </div>

            <!-- Review Applications -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Review Applications</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Review and manage candidate applications.</p>
                <a href="{{ url('/profile/applications') }}" 
                   class="block w-full text-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                   Review Applications
                </a>
            </div>

            <!-- Manage Company -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center mb-4">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-gray-900 dark:text-white">Company Profile</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Update your company information and branding.</p>
                <a href="{{ url('/profile/company') }}" 
                   class="block w-full text-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                   Manage Company
                </a>
            </div>

        </div> <!-- End Quick Actions -->
        @endif

        <!-- Non-employer content can go here -->
        @if(!auth()->check() || auth()->user()->user_type !== 'employer')
        <p class="text-gray-600 dark:text-gray-400">You don’t have access to job management features. Contact admin to upgrade your account.</p>
        @endif

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