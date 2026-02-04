<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="text-center">
        <h1 class="text-9xl font-bold text-gray-300">404</h1>
        <p class="text-2xl font-semibold text-gray-700 mt-4">Oops! Halaman hilang.</p>
        <p class="text-gray-500 mt-2">Sepertinya halaman yang Anda cari tidak ada atau metode akses salah.</p>
        <a href="{{ url('/dashboard') }}" class="mt-6 inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            Kembali ke Dashboard
        </a>
    </div>
</body>
</html>