<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MusicController extends Controller
{
    public function index()
    {
        // Retrieve all music records
        return Music::all();
    }

    public function store(Request $request)
    {
        try{
            $request->validate([
                'title' => 'required|string|max:255',
                'file' => 'required|file',
                'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            ]);
    
            $music = new Music();
            $music->title = $request->title;
    
            // Store music file
            if ($request->hasFile('file')) {
                $music->file_path = $request->file('file')->store('music', 'public');
            }
    
            // Store image file
            if ($request->hasFile('image')) {
                $music->image_path = $request->file('image')->store('images', 'public');
            }
    
            $music->save();
    
            return response()->json($music, 201); // Return the created music record

        }catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
        
    }

    public function show(Music $music)
    {
        // Return a single music record
        return response()->json($music);
    }

    public function update(Request $request, Music $music)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'file' => 'nullable|file|mimes:mp3,wav|max:10240',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Update title if provided
        if ($request->has('title')) {
            $music->title = $request->title;
        }

        // Update music file if a new one is provided
        if ($request->hasFile('file')) {
            // Optionally delete the old file
            Storage::disk('public')->delete($music->file_path);
            $music->file_path = $request->file('file')->store('music', 'public');
        }

        // Update image file if a new one is provided
        if ($request->hasFile('image')) {
            // Optionally delete the old image
            Storage::disk('public')->delete($music->image_path);
            $music->image_path = $request->file('image')->store('images', 'public');
        }

        $music->save();

        return response()->json($music);
    }

    public function destroy(Music $music)
    {
        // Delete the files from storage
        Storage::disk('public')->delete($music->file_path);
        Storage::disk('public')->delete($music->image_path);

        $music->delete();

        return response()->noContent(); // Return 204 No Content
    }
}
