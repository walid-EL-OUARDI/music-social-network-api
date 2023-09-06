<?php

namespace App\Http\Controllers\Auth;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorPostRequest;
use App\Http\Requests\Post\UpdatePostRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getPosts(int $userId)
    {
        try {
            $posts = Post::where('user_id', $userId)->get();
            return response()->json([
                'posts' => $posts,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong in PostController.getPosts',
                'error' => $e->getMessage()
            ], 400);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StorPostRequest $request)
    {
        try {
            if (!$request->hasFile('image')) {
                return response()->json(['error' => 'There is no image to upload.'], 400);
            }
            $image = $request->image;
            $extension = $image->getClientOriginalExtension();
            $name = time() . '.' . $extension;
            $image->move(public_path() . '/postsImages/', $name);

            $post = Post::create([
                'user_id' => $request->user_id,
                'image' => $name,
                'title' => $request->title,
                'location' => $request->location,
                'description' => $request->description,
            ]);

            return response()->json('New post created', 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in PostController.store',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $post = Post::where('id', $id)->get();
            return response()->json([
                'post' => $post,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong in PostController.show',
                'error' => $e->getMessage()
            ], 400);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        try {
            if ($request->image) {
                $oldImage = $post->image;
                unlink(public_path() . '/postsImages/' . $oldImage);
                $newImage = $request->image;
                $extension = $newImage->getClientOriginalExtension();
                $name = time() . '.' . $extension;
                $newImage->move(public_path() . '/postsImages/', $name);
                $post->image = $name;
            }
            $post->title = $request->title;
            $post->description = $request->description;
            $post->location = $request->location;
            $post->save();
            return response()->json('Post was updated!', 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong in PostController.update',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {

        try {
            $currentImage = public_path() . '/postsImages/' . $post->image;
            unlink($currentImage);
            $post->delete();
            return response()->json('Post deleted', 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong in PostController.dsetroy',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
