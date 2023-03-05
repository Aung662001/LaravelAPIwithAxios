<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $post = Post::all();///all('id','title')1
        return response()->json($post,200);//2--3 at Post.php
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        ////4
        $validator = Validator::make(request()->all(), [
            'title' => 'required',
            'description' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            } else {
                // Validation passed
                // Process the data
                $store = Post::create([
                    'title'=>$request->title,
                    'description'=>$request->description,
                ]);
                return response()->json($store,200,);
            }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //5
        return response()->json(Post::find($id),200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //6
        $post = Post::findOrFail($id);
        $post->update([
            'title'=>$request->title,
            'description'=>$request->description,
        ]);
        return response()->json(['message'=>'Update Success'],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //7
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json(['message'=>'Delete Success',200]);
    }
}