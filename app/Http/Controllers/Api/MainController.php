<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\Promotion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Response;

class MainController
{
    /**
     * @return LengthAwarePaginator<Post>
     */
    public function blogs(Request $request): LengthAwarePaginator
    {
        return Post::listing($request);
    }

    public function blog(string $uuid): JsonResponse
    {
        $post = Post::where('uuid', $uuid)->first();

        if (! $post) {
            return Response::error(404, 'Post not found');
        }

        return Response::success(200, $post->toArray());
    }

    /**
     * @return LengthAwarePaginator<Promotion>
     */
    public function promotions(Request $request): LengthAwarePaginator
    {
        return Promotion::listing($request);
    }
}
