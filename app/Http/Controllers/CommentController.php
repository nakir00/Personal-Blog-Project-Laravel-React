<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class CommentController extends Controller implements HasMiddleware
{


    public static function middleware() {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        try {
            // Vérifier si l'utilisateur est authentifié
            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }

            $comment = new Comment();
            $comment->content = $request->content;
            $comment->user_id = $user->id;
            $comment->post_id = $post->id;
            $comment->save();

            return response()->json($comment, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Comment $comment)
    {
        // Vérifier si l'utilisateur est authentifié
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Vérifier si l'utilisateur est autorisé à supprimer le commentaire
        if (Gate::denies('modify', $comment)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $comment->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
}
}

