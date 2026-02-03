<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Artikel Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">

    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">📚 Daftar Artikel</h1>
        <a href="{{ route('posts.create') }}" class="inline-block mb-6 bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition">
            + Buat Artikel Baru
        </a>
        @foreach($posts as $post)
            <div class="bg-white p-6 rounded-lg shadow-md mb-4 hover:shadow-lg transition">
                <h2 class="text-xl font-bold text-blue-600">
                    {{ $post->title }}
                </h2>
                
                <p class="text-gray-600 mt-2">
                    {{ $post->content }}
                </p>

                <div class="mt-4 text-sm text-gray-400 border-t pt-2 flex justify-between">
                    <span>✍️ Penulis: <span class="font-semibold text-gray-700">{{ $post->user->name }}</span></span>
                    <span>🕒 {{ $post->created_at->diffForHumans() }}</span>
                </div>

                <div class="mt-4 flex gap-2">
                    <a href="{{ route('posts.edit', $post->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600">
                        Edit
                    </a>

                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
        
        @if($posts->isEmpty())
            <p class="text-center text-gray-500">Belum ada artikel yang ditulis.</p>
        @endif
            
    </div>

</body>
</html>