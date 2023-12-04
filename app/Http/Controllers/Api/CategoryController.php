<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    function index() {
        $categories = Category::orderby("name")->get();
        return ResponseHelper::success(CategoryResource::collection($categories));
    }
}
