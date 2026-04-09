<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Job Portal - Find Your Dream Job</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
        <!-- Navigation Header -->
        <header class="border-b border-gray-200 dark:border-gray-800">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
                <div class="flex-shrink-0">
                    <h1 class="text-2xl font-bold text-blue-600">Job Portal</h1>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 font-medium">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
                            Register
                        </a>
                    @endauth
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Hero Section -->
            <section class="text-center mb-20">
                <h2 class="text-5xl md:text-6xl font-bold mb-6 text-gray-900 dark:text-white">
                    Find Your <span class="text-blue-600">Dream Job</span>
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-400 mb-8 max-w-3xl mx-auto">
                    Connect with thousands of job opportunities and advance your career with our comprehensive job portal platform.
                </p>
                <div class="flex gap-4 justify-center flex-col sm:flex-row">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-block px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-block px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                            Register as Job Seeker
                        </a>
                        <a href="{{ route('register') }}" class="inline-block px-8 py-3 border-2 border-blue-600 text-blue-600 rounded-lg font-semibold hover:bg-blue-50 dark:hover:bg-gray-800 transition">
                            Hire Talent
                        </a>
                    @endauth
                </div>
            </section>

            <!-- Features Grid -->
            <section class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
                <div class="p-8 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg transition">
                    <div class="text-5xl mb-4">👤</div>
                    <h3 class="text-2xl font-bold mb-3 text-gray-900 dark:text-white">For Job Seekers</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        Browse thousands of job listings, build your professional profile, and track your applications in one place.
                    </p>
                </div>

                <div class="p-8 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg transition">
                    <div class="text-5xl mb-4">🏢</div>
                    <h3 class="text-2xl font-bold mb-3 text-gray-900 dark:text-white">For Employers</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        Post job openings, manage applications, and find the perfect candidates for your team quickly.
                    </p>
                </div>

                <div class="p-8 bg-gray-50 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg transition">
                    <div class="text-5xl mb-4">⚡</div>
                    <h3 class="text-2xl font-bold mb-3 text-gray-900 dark:text-white">Platform Features</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                        Advanced search, real-time notifications, secure messaging, and comprehensive profile management.
                    </p>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-2xl p-12 text-center">
                <h2 class="text-4xl font-bold mb-4">Ready to Get Started?</h2>
                <p class="text-lg text-blue-100 mb-8 max-w-2xl mx-auto">
                    Join thousands of professionals who have found success through our platform. Sign up today and take the next step in your career.
                </p>
                @guest
                    <a href="{{ route('register') }}" class="inline-block px-8 py-3 bg-white text-blue-600 rounded-lg font-semibold hover:bg-gray-100 transition">
                        Create Account Now
                    </a>
                @endguest
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 dark:bg-gray-950 text-gray-400 border-t border-gray-800 mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                    <div>
                        <h4 class="text-white font-bold mb-4">Job Portal</h4>
                        <p class="text-sm">Your gateway to career opportunities and success.</p>
                    </div>
                    <div>
                        <h4 class="text-white font-bold mb-4">For Job Seekers</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-white transition">Browse Jobs</a></li>
                            <li><a href="#" class="hover:text-white transition">Build Profile</a></li>
                            <li><a href="#" class="hover:text-white transition">Career Advice</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-bold mb-4">For Employers</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-white transition">Post Job</a></li>
                            <li><a href="#" class="hover:text-white transition">Manage Postings</a></li>
                            <li><a href="#" class="hover:text-white transition">Pricing</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-bold mb-4">Company</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-white transition">About Us</a></li>
                            <li><a href="#" class="hover:text-white transition">Contact</a></li>
                            <li><a href="#" class="hover:text-white transition">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 pt-8 text-center text-sm">
                    <p>&copy; 2026 Job Portal. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
