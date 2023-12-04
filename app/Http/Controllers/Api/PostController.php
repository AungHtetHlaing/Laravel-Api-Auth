<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use App\Models\Media;
use App\Models\Post;
use Dflydev\DotAccessData\Data;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request) {

        $posts = Post::with(['user','category','image'])->when($request->category_id, fn($q)=> $q->where('category_id', $request->category_id))
        ->when($request->search, fn($q)=> $q->where('title','like', '%'.$request->search.'%')->orWhere('description','like', '%'.$request->search.'%'))
        ->orderByDesc('created_at')
        ->paginate(10);
        return PostResource::collection($posts)->additional(['message' => 'success']);
    }

    public function create(Request $request) {

        $request->validate(
            [
                "title" => "required|string",
                "description" => "required|string",
                "category_id" => "required"
            ],
            [
                "category_id.required" => "category is required"
            ]
            );


            DB::beginTransaction();
            try {

                if($request->hasFile('image')) {
                    $file = $request->file('image');
                    $file_name = uniqid() . '-' . date('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();
                    Storage::put('media/' . $file_name, file_get_contents($file)); // in .env => FILESYSTEM_DISK=public
                }

                $post = new Post();
                $post->title = $request->title;
                $post->description = $request->description;
                $post->category_id = $request->category_id;
                $post->user_id = Auth::id();
                $post->save();


                $media = new Media();
                $media->file_name = $file_name;
                $media->file_type = "image";
                $media->model_id = $post->id;
                $media->model_type = Post::class;
                $media->save();

                DB::commit();
                return ResponseHelper::success([], "Successfully created");


            } catch (Exception $e) {
                DB::rollBack();
                return ResponseHelper::fail($e->getMessage());
            }


    }

    public function show($id) {
        $post = Post::with(['user','category','image'])->where('id', $id)->firstOrFail();
        return ResponseHelper::success(new PostDetailResource($post));
    }
}
