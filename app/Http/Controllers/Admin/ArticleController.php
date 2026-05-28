<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller {

    private function storeArticleImage(Request $request, ?string $currentPath = null): ?string
    {
        if (!$request->hasFile('image')) {
            return $currentPath;
        }

        if ($currentPath) {
            Storage::disk('public')->delete($currentPath);
        }

        return $request->file('image')->store('articles', 'public');
    }

    public function index() {
        $articles = Article::with('author')
            ->when(request('search'), function ($query) {
                $search = request('search');

                $query->where(function ($nested) use ($search) {
                    $nested->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%");
                });
            })
            ->when(request('category'), fn ($query) => $query->where('category', request('category')))
            ->when(request('status') === 'published', fn ($query) => $query->where('is_published', true))
            ->when(request('status') === 'draft', fn ($query) => $query->where('is_published', false))
            ->orderBy('title')
            ->orderBy('id')
            ->paginate(15);

        return view('admin.articles.index', compact('articles'));
    }

    public function create() { return view('admin.articles.create'); }

    public function store(Request $request) {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'excerpt'      => 'nullable|string|max:500',
            'content'      => 'required|string',
            'category'     => 'required|in:nutrisi,lifestyle,resep,kesehatan',
            'read_time'    => 'nullable|integer|min:1',
            'is_published' => 'boolean',
            'image'        => 'nullable|image|max:4096',
        ]);

        $data['author_id']    = Auth::id();
        $data['is_published'] = $request->boolean('is_published');
        $data['read_time']    = $request->read_time ?? 3;
        $data['image']       = $this->storeArticleImage($request);

        Article::create($data);
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil disimpan!');
    }

    public function edit(Article $article) { return view('admin.articles.edit', compact('article')); }

    public function update(Request $request, Article $article) {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'excerpt'      => 'nullable|string|max:500',
            'content'      => 'required|string',
            'category'     => 'required|in:nutrisi,lifestyle,resep,kesehatan',
            'read_time'    => 'nullable|integer|min:1',
            'is_published' => 'boolean',
            'image'        => 'nullable|image|max:4096',
        ]);
        $data['is_published'] = $request->boolean('is_published');
        $data['read_time']    = $request->read_time ?? $article->read_time ?? 3;
        $data['image']        = $this->storeArticleImage($request, $article->image);
        $article->update($data);
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy(Article $article) {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();
        return back()->with('success', 'Artikel dihapus.');
    }
}