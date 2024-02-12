<?php

namespace App\Http\Controllers\Api;

// import Model "Post"
use App\Models\Post;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// import Resource "PostResource"
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Storage;
//import Facade "Validator" 
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /** 
     * index 
     * 
     * @return void 
     */
    public function index()
    {
        //get all posts 
        $posts = Post::latest()->paginate(5);
        //return collection of posts as a resource 
        return new PostResource(true, 'List Data Posts', $posts);
    }

    /** 
     * store 
     * 
     * @param  mixed $request 
     * @return void 
     */
    public function store(Request $request)
    {
        //define validation rules 
        $validator = Validator::make($request->all(), [
            'image' =>
                'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required',
            'content' => 'required',
        ]);

        //check if validation fails 
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image 
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        //create post 
        $post = Post::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        //return response 
        return new PostResource(
            true,
            'Data Post Berhasil Ditambahkan!',
            $post
        );
    }

    // PostController

    public function update(Request $request, $id)
    {
        // cari berdasarkan id
        $post = Post::find($id);

        // validasi(memastikan inputan sesuai dengan yg diinginkan)
        $validator = Validator::make($request->all(), [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required',
            'content' => 'required',
        ]);

        // jika validasi gagal kirim response dengan pesan error dalam bentuk json
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Jika terdapat file gambar baru dalam request
        if ($request->hasFile('image')) {
            // hapus gambar lama
            Storage::delete('public/posts/' . $post->image);

            // upload gambar baru
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            // update image
            $post->update([
                'image' => $image->hashName(),
            ]);
        }

        // update post title dan content
        $post->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        // return pesan response 
        return new PostResource(true, 'Data Post Berhasil Diupdate!', $post);
    }

    /** 
     * show
     * 
     * @param  mixed $post
     * @return void 
     */
    public function show($id)
    {
        // Find post by ID
        $post = Post::find($id);

        // return single post as a resource
        return new PostResource(true, 'Detail Data Post!', $post);
    }

    public function destroy($id)
    {
        // Find post by ID
        $post = Post::find($id);

        // delete id
        $post->delete();

        // return single post as a resource
        return new PostResource(true, 'Data berhasil dihapus!', $post);
    }
}
