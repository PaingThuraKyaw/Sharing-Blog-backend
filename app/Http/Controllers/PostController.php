<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\File;
use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = new Post();

        return response()->json(["data" => PostResource::collection($posts->all())], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|min:3",
            "description" => "required",
            "image" => "image|mimes:jpeg,png,jpg,gif|max:2048", // Adjust the validation rules for your needs
        ]);


        // condition user
        if (Auth::check()) {

            $user = Auth::user();

            $user = User::find($user->id);

            // post
            $postAttribute = [
                "title" => $request->title,
                "description" => $request->description,
                "owner"  => $user->name
            ];

            // create post
            $post =  $user->posts()->create($postAttribute);

            // condition image contain or not
            if ($request->hasFile("image")) {

                // this code is error !!
                $filePath = $request->file('image')->store('image', 'public');
                $file = new File();
                $file->image = $filePath;
                $file->post_id = $post->id;
                $file->post_type = Post::class;
                $file->save();
            }


            return response()->json([
                "data" => $post
            ], 201);
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
        $post = Post::findOrFail($id);
        $detailPost = new PostResource($post);
        return response()->json(["data" => $detailPost]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        try {
            // // Validate the incoming request data
            $request->validate([
                "title" => "required|min:3",
                "description" => "required",
                "image" => "image|mimes:jpeg,png,jpg,gif|max:2048", // Adjust the validation rules for your needs
            ]);

            $post = Post::findOrFail($id);
            $image = File::findOrFail($post->id);

            // Update post attributes
            $post->title = $request->title;
            $post->description = $request->description;

            // // Update image if provided in the request
            if ($request->hasFile("image")) {
                // Delete the old image file, if exists
                if ($image->image) {
                    Storage::disk('public')->delete($image->image);
                    $image->delete();
                }

                //     // Store the new image file
                $filePath = $request->file('image')->store('image', 'public');
                $file = new File();
                $file->image = $filePath;
                $file->post_id = $post->id;
                $file->post_type = Post::class;
                $file->save();
            }

            // // Save the changes
            $post->save();

            // Return the updated post
            return response()->json([
                "data" => new PostResource($post),
                "message" => "Post updated successfully"
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Something went wrong!",
                "error" => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $image = File::findOrFail($post->id);

        Storage::disk('public')->delete($image->image);
        $image->delete();
        $post->delete();
        return response()->json(["data" => [
            "status"  => true,
            "message" => "post deleted"
        ]]);
    }

    public function profile(){
     try{
           if(auth()->check()){
            $user = auth()->user();

              return response()->json([
                'success' => true,
                'message' => 'Authentication successful',
                'data' => $user,]);
        }
     }catch(Exception $e){
        return response()->json([
                'success' => false,
                'message' => 'Authentication failed',
            ], 401);
     }
    }
}
