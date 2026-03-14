<?php

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public Model  $model;
    public string $modelType;
    public string $body           = '';
    public ?int   $replyingTo     = null;
    public ?int   $editingId      = null;
    public string $replyBody      = '';
    public string $editBody       = '';
    public array  $mentionSuggestions = [];
    public bool   $showSuggestions    = false;
    public string $deletedDisplay = 'blurred';

    public function mount(Model $model, string $deletedDisplay = 'blurred'): void
    {
        $this->model          = $model;
        $this->modelType      = get_class($model);
        $this->deletedDisplay = $deletedDisplay;
    }

    public function searchMentions(string $query): void
    {
        if (strlen($query) < 2) {
            $this->mentionSuggestions = [];
            $this->showSuggestions    = false;
            return;
        }

        $this->mentionSuggestions = User::where('name', 'like', "%{$query}%")
            ->limit(5)->get(['id', 'name'])->toArray();

        $this->showSuggestions = count($this->mentionSuggestions) > 0;
    }

    public function insertMention(string $name): void
    {
        $this->body               = preg_replace('/@\w*$/', "@{$name} ", $this->body);
        $this->mentionSuggestions = [];
        $this->showSuggestions    = false;
    }

    public function postComment(): void
    {
        $this->validate(['body' => 'required|min:2|max:2000']);

        $comment = Comment::create([
            'user_id'          => Auth::id(),
            'commentable_type' => $this->modelType,
            'commentable_id'   => $this->model->id,
            'body'             => $this->body,
            'parent_id'        => null,
        ]);

        $this->syncMentions($comment);
        $this->body = '';
    }

    public function postReply(): void
    {
        $this->validate(['replyBody' => 'required|min:2|max:2000']);

        $comment = Comment::create([
            'user_id'          => Auth::id(),
            'commentable_type' => $this->modelType,
            'commentable_id'   => $this->model->id,
            'body'             => $this->replyBody,
            'parent_id'        => $this->replyingTo,
        ]);

        $this->syncMentions($comment);
        $this->replyBody  = '';
        $this->replyingTo = null;
    }

    public function startEdit(int $id): void
    {
        $comment         = Comment::findOrFail($id);
        $this->editingId = $id;
        $this->editBody  = $comment->body;
    }

    public function saveEdit(): void
    {
        $this->validate(['editBody' => 'required|min:2|max:2000']);

        $comment = Comment::findOrFail($this->editingId);
        if ($comment->user_id !== Auth::id()) return;

        $comment->update(['body' => $this->editBody]);
        $this->syncMentions($comment);
        $this->editingId = null;
        $this->editBody  = '';
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->editBody  = '';
    }

    public function deleteComment(int $id): void
    {
        $comment = Comment::find($id);
        if (!$comment) return;
        if ($comment->user_id !== Auth::id() && !Auth::user()?->isAdmin()) return;
        $comment->softDeleteComment($this->deletedDisplay);
    }

    public function toggleLike(int $id): void
    {
        if (!Auth::check()) return;

        $comment = Comment::with('likes')->findOrFail($id);
        $user    = Auth::user();

        if ($comment->likes->contains($user->id)) {
            $comment->likes()->detach($user->id);
            $comment->decrement('likes_count');
        } else {
            $comment->likes()->attach($user->id);
            $comment->increment('likes_count');
        }
    }

    public function renderMentions(string $body): string
    {
        return preg_replace_callback('/@([\w]+)/', function ($matches) {
            $name = $matches[1];
            $user = User::where('name', $name)->first();
            if (!$user) return '<span class="text-zinc-400">@' . e($name) . '</span>';
            return '<span class="inline-flex items-center bg-primary/10 text-primary font-semibold px-1.5 py-0.5 rounded-md text-xs">@' . e($name) . '</span>';
        }, e($body));
    }

    private function syncMentions(Comment $comment): void
    {
        $usernames = Comment::extractMentions($comment->body);
        if (empty($usernames)) return;
        $comment->mentions()->sync(
            User::whereIn('name', $usernames)->pluck('id')
        );
    }

    public function with(): array
    {
        $comments = $this->model->comments()->get();
        return [
            'comments' => $comments,
            'total'    => $comments->count(),
        ];
    }

    public function formatCount(int $count): string
    {
        if ($count >= 1_000_000) return number_format($count / 1_000_000, 1) . 'M';
        if ($count >= 1_000)     return number_format($count / 1_000, 2) . 'K';
        return (string) $count;
    }

    public function isMentionedWithoutReaction(Comment $comment): bool
    {
        if (!Auth::check()) return false;

        $isMentioned = $comment->mentions->contains(Auth::id());
        if (!$isMentioned) return false;

        $hasLiked    = $comment->likes->contains(Auth::id());
        $hasReplied  = $comment->replies->where('user_id', Auth::id())->isNotEmpty();

        return !$hasLiked && !$hasReplied;
    }
};
?>

<div class="space-y-8 max-w-3xl mx-auto">
    @include('livewire.comments.partials._header', ['total' => $total])
    @include('livewire.comments.partials._form')

    <div class="space-y-1" wire:poll.60s="$refresh">
        @forelse($comments as $comment)
            @include('livewire.comments.partials._comment-item', ['comment' => $comment])
        @empty
            @include('livewire.comments.partials._empty')
        @endforelse
    </div>
</div>