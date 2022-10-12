<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Blog;
use Validator;
use App\Http\Resources\Blog as BlogResource;

class BlogController extends BaseController{
    
    public function index(){
        $Blogs = Blog::all();
        return $this->sendResponse(BlogResource::collection($Blogs), 'Blogs retrieved successfully.');
    }

    public function store(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $Blog = Blog::create($input);
        return $this->sendResponse(new BlogResource($Blog), 'Blog created successfully.');
    }

    public function show($id){
        $Blog = Blog::find($id);
        if (is_null($Blog)) {
            return $this->sendError('Blog not found.');
        }
        return $this->sendResponse(new BlogResource($Blog), 'Blog retrieved successfully.');
    }

    public function update(Request $request, Blog $blog){

        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $blog->title = $input['title'];
        $blog->description = $input['description'];
        $blog->save();

        return $this->sendResponse(new BlogResource($blog), 'Blog updated successfully.');
    }

    public function destroy(Blog $blog){
        $blog->delete();
        return $this->sendResponse([], 'Blog deleted successfully.');
    }
}
