<?php

namespace App\Http\Controllers\Auth;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\video\StoreVideoRequest;


class VideoController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVideoRequest $request)
    {
        try {
            Video::create([
                'user_id' => $request->user_id,
                'title' => $request->title,
                'video_url' => $request->video_url,
            ]);
            return response()->json('video is stored', 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong in VideoController.store',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $user_id)
    {
        try {
            $videos = Video::where('user_id', $user_id)->get();

            return response()->json([
                'videos' => $videos,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong in VideoController.show',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        try {
            $video->delete();
            return response()->json('video deleted', 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in VideoController.destroy',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
