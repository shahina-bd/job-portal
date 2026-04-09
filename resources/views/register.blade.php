<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Job Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <!-- Navigation Header -->
    <header class="border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-800">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <div class="flex-shrink-0">
                <a href="/" class="text-2xl font-bold text-blue-600 hover:text-blue-700">
                    Job Portal
                </a>
            </div>
            <p class="text-gray-600 dark:text-gray-400">
                Already have an account? <a href="/login" class="text-blue-600 hover:text-blue-700 font-medium">Sign In</a>
            </p>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="min-h-[calc(100vh-64px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Create Account</h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Join our job portal and start your journey today
                </p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                Registration failed. Please check the errors below:
                            </h3>
                            <div class="mt-3 text-sm text-red-700 dark:text-red-300">
                                <ul class="list-disc space-y-1 pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="/register" class="space-y-5 bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                @csrf

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Username
                    </label>
                    <input
                        id="username"
                        name="username"
                        type="text"
                        autocomplete="username"
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="Choose a username"
                        value="{{ old('username') }}"
                    />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email Address
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="you@example.com"
                        value="{{ old('email') }}"
                    />
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Phone Number <span class="text-gray-400">(Optional)</span>
                    </label>
                    <input
                        id="phone"
                        name="phone"
                        type="tel"
                        autocomplete="tel"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="+1 (555) 123-4567"
                        value="{{ old('phone') }}"
                    />
                </div>

                <!-- User Type -->
                <div>
                    <label for="user_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Account Type
                    </label>
                    <select
                        id="user_type"
                        name="user_type"
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                    >
                        <option value="">Select account type</option>
                        <option value="employee" {{ old('user_type') == 'employee' ? 'selected' : '' }}>Job Seeker</option>
                        <option value="employer" {{ old('user_type') == 'employer' ? 'selected' : '' }}>Employer</option>
                    </select>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Password
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="new-password"
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="At least 8 characters"
                    />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Must be at least 8 characters long</p>
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Confirm Password
                    </label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="Re-enter your password"
                    />
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full mt-6 px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition duration-200"
                >
                    Create Account
                </button>

                <!-- Terms -->
                <p class="text-center text-xs text-gray-500 dark:text-gray-400">
                    By creating an account, you agree to our 
                    <a href="#" class="text-blue-600 hover:text-blue-700">Terms of Service</a> and 
                    <a href="#" class="text-blue-600 hover:text-blue-700">Privacy Policy</a>
                </p>
            </form>

            <!-- Sign In Link -->
            <p class="text-center mt-6 text-gray-600 dark:text-gray-400">
                Already have an account?
                <a href="/login" class="font-semibold text-blue-600 hover:text-blue-700">
                    Sign In
                </a>
            </p>
        </div>
    </div>
</body>
</html>
