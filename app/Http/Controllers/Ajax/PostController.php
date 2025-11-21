<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ajax\StatusPostRequest;
use App\Models\Post\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function status(StatusPostRequest $request)
    {
        $post = Post::findOrFail($request->id);
        if ($post->post_status === 'publish') {
            $status = 'draft';
        } else {
            $status = 'publish';
        }
        $post->update([
            'post_status' => $status
        ]);
        return response()->json([
            'success' => true,
            'message' => $post->post_name.' has been set to '.$status,
        ]);
    }
}
