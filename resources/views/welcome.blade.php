<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-2xl shadow-md w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 overflow-hidden">

        <div class="flex items-center justify-center p-6">
            <img src="{{ asset('images/gambar 1.svg') }}" alt="Hello illustration" class="w-full max-w-md">
        </div>

        <div class="flex flex-col justify-center text-center md:text-left p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Project Management</h1>
            <p class="text-gray-600 mb-6">
                Create, assign, and track tasks with ease. Set priorities and deadlines to keep our team on track.
            </p>
            <a href="/admin" class="px-6 py-2 border text-center border-gray-800 rounded-full hover:bg-gray-800 hover:text-white transition-all">
                Access Admin Panel !
            </a>
        </div>
    </div>
</body>
</html>
