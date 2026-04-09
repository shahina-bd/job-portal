<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job Post - Job Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <main class="max-w-3xl mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Create Job Post</h1>
            <p class="text-gray-600 mt-1">Fill in the details to publish a new job.</p>
        </div>

        <div class="bg-white rounded-lg shadow p-4 mb-5">
            <p class="text-sm font-medium text-gray-700 mb-3">Choose Job Type Option</p>
            <div class="flex flex-wrap gap-2">
                <a href="{{ url('/jobs/create?job_type=full-time') }}" class="px-3 py-1.5 rounded-md border border-gray-300 hover:bg-gray-50 text-sm">Full-time</a>
                <a href="{{ url('/jobs/create?job_type=part-time') }}" class="px-3 py-1.5 rounded-md border border-gray-300 hover:bg-gray-50 text-sm">Part-time</a>
                <a href="{{ url('/jobs/create?job_type=remote') }}" class="px-3 py-1.5 rounded-md border border-gray-300 hover:bg-gray-50 text-sm">Remote</a>
            </div>
        </div>

        <form method="POST" action="{{ url('/jobs') }}" class="bg-white rounded-lg shadow p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Job Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Select category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Publish Date</label>
                    <input type="date" name="publish_date" value="{{ old('publish_date', now()->toDateString()) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    @error('publish_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    @error('end_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Job Type</label>
                    <select name="job_type" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Select type</option>
                        <option value="full-time" @selected(old('job_type', request('job_type')) === 'full-time')>Full-time</option>
                        <option value="part-time" @selected(old('job_type', request('job_type')) === 'part-time')>Part-time</option>
                        <option value="remote" @selected(old('job_type', request('job_type')) === 'remote')>Remote</option>
                    </select>
                    @error('job_type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Salary</label>
                    <input type="number" step="0.01" min="0" name="salary" value="{{ old('salary') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    @error('salary') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                    <input type="text" name="currency" value="{{ old('currency', 'USD') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    @error('currency') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Job Description</label>
                <textarea name="job_description" rows="5" required class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ old('job_description') }}</textarea>
                @error('job_description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Requirements</label>
                <textarea name="requirements" rows="4" required class="w-full border border-gray-300 rounded-lg px-3 py-2">{{ old('requirements') }}</textarea>
                @error('requirements') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create Job</button>
                <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</a>
            </div>
        </form>
    </main>
</body>
</html>
