<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Article;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Article::latest()->get();
        return response()->json([ArticleResource::collection($data), 'Articles fetched.']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required|string|max:255',
            'body' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $article = Article::create([
            'title' => $request->title,
            'body' => $request->body
         ]);
        
        return response()->json(['Article created successfully.', new ArticleResource($article)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::find($id);
        if (is_null($article)) {
            return response()->json('Data not found', 404); 
        }
        return response()->json([new ArticleResource($article)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required|string|max:255',
            'body' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $article->title = $request->title;
        $article->body = $request->body;
        $article->save();
        
        return response()->json(['Article updated successfully.', new ArticleResource($article)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return response()->json('Article deleted successfully');
    }
}