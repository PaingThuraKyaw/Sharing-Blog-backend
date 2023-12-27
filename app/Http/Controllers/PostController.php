<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = new Post();
        return response()->json(["data" => $posts->all()], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|min:3",
            "description" => "required",
            "image" => "required|image|mimes:jpeg,png,jpg,gif|max:2048", // Adjust the validation rules for your needs
        ]);


        if (Auth::check()) {

            $user = Auth::user();

            $user = User::find($user->id);

              $post =  $user->posts()->create([
                "title" => $request->title,
                "description" => $request->description,
                "image"  => $request->image
            ]);

            return response()->json([
                "data" => $post
            ]);
        }

        return response()->json([
            'error' => 'Server Error',
        ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
