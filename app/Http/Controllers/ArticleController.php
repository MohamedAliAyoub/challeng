<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

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
//        dd($article);
        if ($article->user_id !== Auth::id())
            abort('unauthorized');

        else{
        $this->validate($request, $this->rules());
        $article->update([
            'title' => $request->post('title'),
            'content' => $request->post('content'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'artical updated successfully',
            'code' => 200
        ]);

        }
    }

    public function delete(Request $request, $articleId)
    {
        /** @var Article $article */
        $article = Article::query()->findOrFail($articleId);
        if ($article->user_id !== Auth::id())
            abort('unauthorized');
        $article->delete();
        return response()->json([
            'success' => true,
            'message' => 'artical deleted  successfully',
            'code' => 200
        ]);
    }

    public function rules()
    {
        return [
            'title' => 'required|array',
            'title.*' => 'required|string|min:4',
            'content' => 'required|array',
            'content.*' => 'required|string',
        ];
    }
}
