<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, $this->rules());

        Article::query()->create([
            'title' => $request->post('title'),
            'content' => $request->post('content'),
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function update(Request $request, $articleId)
    {
        /** @var Article $article */
        $article = Article::query()->findOrFail($articleId);
        if ($article->user_id !== Auth::id())
            abort(403, 'unauthorized');

        $this->validate($request, $this->rules());
        $article->update([
            'title' => $request->post('title'),
            'content' => $request->post('content'),
        ]);
        return response()->json([
            'success' => true,
            'message' => 'article updated successfully',
        ]);
    }

    public function delete(Request $request, $articleId)
    {
        /** @var Article $article */
        $article = Article::query()->findOrFail($articleId);
        if ($article->user_id !== Auth::id())
            abort(403, 'unauthorized');

        $article->delete();
        return response()->json([
            'success' => true,
            'message' => 'article deleted successfully',
        ]);
    }

    public function rules()
    {
        return array_merge([
            'title' => 'required|array',
            'content' => 'required|array',
        ], collect(array_map(function ($locale) {
            return [
                "title.$locale" => 'required|string|min:4',
                "content.$locale" => 'required|string',
            ];
        }, locales()))->collapse()->toArray());
    }
}
