<?php

use Livewire\Component;
use App\Models\Article;
use App\Models\UserLike;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

new class extends Component
{
    public Article $article;
    public $related_articles = [];
    public $liked = false;
    public $likes_count = 0;

    public function mount(Article $article)
    {
        $article->load(['author', 'subject', 'likes']);
        // if ($article->status != 'published' && !Auth::user()?->hasRole(['admin','author'])) {
        //     abort(404);
        // }

        $this->article = $article;
        $this->article->increment('views_count');

        $this->related_articles = Article::where('status','published')
            ->where('id', '!=', $article->id)
            ->where('subject_id', $article->subject_id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $this->liked = Auth::check()
            ? UserLike::hasLiked( Auth::id(), Article::class, $article->id )
            : false;

        $this->likes_count = $article->likes()->count();
    }

    public function toggleLike()
    {
        if (!Auth::check()) return;
            $this->liked = UserLike::toggle( Auth::id(), Article::class, $this->article->id
        );

        $this->likes_count = $this->article->likes()->count();
    }

    public function getLikesFormattedProperty()
    {
        return Number::abbreviate($this->likes_count);
    }


    public function render()
    {
        return view('livewire.articles.show', [
            'article' => $this->article,
            'related_articles' => $this->related_articles
        ]);
    }
};
?>
