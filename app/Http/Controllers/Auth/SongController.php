<?php

namespace App\Http\Controllers\Auth;

use App\Models\Song;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Song\StoreSongRequest;

class SongController extends Controller
{

    public function store(StoreSongRequest $request)
    {
        try {
            $file = $request->song;
            if (empty($file)) {
                return response()->json('No song uploaded', 400);
            }
            $user = User::findOrFail($request->user_id);
            $song = $file->getClientOriginalName();
            $file->move('songs/' . $user->id, $song);

            Song::create([
                'user_id' => $request->get('user_id'),
                'title' => $request->get('title'),
                'song' => $song,
            ]);

            return response()->json('Song Saved!', 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong in SongController.store',
                'error' => $e->getMessage()
            ], 400);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Song $song)
    {
        //
    }
}
