<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function profile() {
        $user = auth()->user();
        return ResponseHelper::success(new ProfileResource($user));
    }

    public function posts(Request $request) {

        $posts = Post::with(['user','category','image'])->when($request->category_id, fn($q)=> $q->where('category_id', $request->category_id))
        ->when($request->search, fn($q)=> $q->where('title','like', '%'.$request->search.'%')->orWhere('description','like', '%'.$request->search.'%'))
        ->where('user_id', Auth::id())
        ->orderByDesc('created_at')
        ->paginate(10);
        return PostResource::collection($posts)->additional(['message' => 'success']);
    }

}
