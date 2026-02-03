<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    public function index() {
        // $posts = Post::all();
        $posts = Post::with('user')->get();
        return view('posts.index', compact('posts'));
    }

    public function create() {
        return view('posts.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|min:5|max:255',
            'content' => 'required|min:10',
        ]);

        $user = User::first();

        $user->posts()->create($validated);

        return redirect()->route('posts.index');
    }

    // Menampilkan form edit dengan data yang sudah ada
    public function edit(Post $post) // <--- Magic di sini!
    {
        return view('posts.edit', compact('post'));
    }

    // Menyimpan perubahan
    public function update(Request $request, Post $post)
    {
        // 1. Validasi (Sama seperti create)
        $validated = $request->validate([
            'title' => 'required|min:5|max:255',
            'content' => 'required|min:10',
        ]);

        // 2. Update Data
        $post->update($validated);

        // 3. Redirect
        return redirect()->route('posts.index');
    }

    public function destroy(Post $post)
    {
        // Hapus data
        $post->delete();

        // Balik ke index
        return redirect()->route('posts.index');
    }
}
