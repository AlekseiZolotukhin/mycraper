<?php

namespace App\Http\Controllers\Api\v1;

use App\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Boolean;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = $this->tokenCheck();

        if (is_numeric($user_id) && stripos(request()->headers->get('referer'), '/admin/') !== false) {
            return DB::table('posts')->where('user_id', $user_id)->orderBy('post_id', 'desc')->get();
        }

        return DB::table('posts')
            ->orderBy('post_id', 'desc')
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $user_id = $this->tokenCheck();
        if (is_numeric($user_id)) {
            $input['user_id'] = $user_id;
        }

        return Post::create($input);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($post_id)
    {
        $post = Post::findOrFail($post_id);
        $user_id = $this->tokenCheck();
        if (is_numeric($user_id) && $user_id != $post->user_id
            && stripos(request()->headers->get('referer'), '/admin/') !== false)
        {
            return false;
        }
        return $post;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $this->tokenCheck($post);

        $post->update($request->all());

        return $post;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id): Boolean
    {
        $post = Post::findOrFail($id);

        $this->tokenCheck($post);

        $post->delete();

        return true;
    }
}
