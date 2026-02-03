<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">

    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">✏️ Edit Artikel</h1>

        <form action="{{ route('posts.update', $post->id) }}" method="POST">
            @csrf
            
            @method('PUT') 

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Judul Artikel</label>
                <input type="text" name="title" 
                       class="w-full px-3 py-2 border rounded-lg"
                       value="{{ old('title', $post->title) }}">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Isi Artikel</label>
                <textarea name="content" rows="4" 
                          class="w-full px-3 py-2 border rounded-lg">{{ old('content', $post->content) }}</textarea>
            </div>

            <button type="submit" class="w-full bg-yellow-500 text-white font-bold py-2 px-4 rounded hover:bg-yellow-600 transition">
                Update Artikel
            </button>
        </form>
    </div>

</body>
</html>