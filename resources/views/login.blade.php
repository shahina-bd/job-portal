<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Job Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <header class="bg-white dark:bg-gray-800 shadow-sm">
        <nav class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <a href="/" class="text-xl font-bold text-blue-600 dark:text-blue-400">Job Portal</a>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Don't have an account?
                <a href="/register" class="text-blue-600 dark:text-blue-400 font-medium hover:underline">Create one</a>
            </p>
        </nav>
    </header>

    <main class="min-h-[calc(100vh-200px)] flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">
            <!-- Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Welcome Back</h1>
                    <p class="text-gray-600 dark:text-gray-400">Sign in to your account to continue</p>
                </div>

                <!-- Error Display -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <p class="text-red-600 dark:text-red-400 text-sm">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="/login" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email Address
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="you@example.com"
                            value="{{ old('email') }}"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        >
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Password
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        >
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2">
                            <input
                                type="checkbox"
                                name="remember"
                                class="w-4 h-4 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-blue-600 focus:ring-2 focus:ring-blue-500 cursor-pointer"
                            >
                            <span class="text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                        </label>
                        <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">
                            Forgot password?
                        </a>
                    </div>

                    <!-- Sign In Button -->
                    <button
                        type="submit"
                        class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 shadow-sm"
                    >
                        Sign In
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">or</span>
                    </div>
                </div>

                <!-- Social Login (Optional) -->
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" class="py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-medium text-gray-700 dark:text-gray-300">
                        Continue with Google
                    </button>
                    <button type="button" class="py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-medium text-gray-700 dark:text-gray-300">
                        Continue with GitHub
                    </button>
                </div>

                <!-- Sign Up Link -->
                <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-6">
                    Don't have an account?
                    <a href="/register" class="text-blue-600 dark:text-blue-400 font-medium hover:underline">Sign up here</a>
                </p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center text-sm text-gray-600 dark:text-gray-400">
                <p>&copy; 2026 Job Portal. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
