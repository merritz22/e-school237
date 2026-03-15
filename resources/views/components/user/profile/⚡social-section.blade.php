<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use App\Models\User;

new class extends Component
{
    public User $user;

    #[Validate('nullable|url|max:255', message: 'URL Facebook invalide.')]
    public string $facebook_url = '';

    #[Validate('nullable|url|max:255', message: 'URL TikTok invalide.')]
    public string $tiktok_url = '';

    #[Validate('nullable|url|max:255', message: 'URL Instagram invalide.')]
    public string $instagram_url = '';

    #[Validate('nullable|url|max:255', message: 'URL Twitter invalide.')]
    public string $twitter_url = '';

    #[Validate('nullable|url|max:255', message: 'URL YouTube invalide.')]
    public string $youtube_url = '';

    #[Validate('nullable|url|max:255', message: 'URL LinkedIn invalide.')]
    public string $linkedin_url = '';

    #[Validate('nullable|url|max:255', message: 'URL site web invalide.')]
    public string $website_url = '';

    public function mount(): void
    {
        $this->facebook_url  = $this->user->facebook_url  ?? '';
        $this->tiktok_url    = $this->user->tiktok_url    ?? '';
        $this->instagram_url = $this->user->instagram_url ?? '';
        $this->twitter_url   = $this->user->twitter_url   ?? '';
        $this->youtube_url   = $this->user->youtube_url   ?? '';
        $this->linkedin_url  = $this->user->linkedin_url  ?? '';
        $this->website_url   = $this->user->website_url   ?? '';
    }

    #[On('save-profile')]
    public function save(): void
    {
        $this->validate();
        $this->user->update([
            'facebook_url'  => $this->facebook_url,
            'tiktok_url'    => $this->tiktok_url,
            'instagram_url' => $this->instagram_url,
            'twitter_url'   => $this->twitter_url,
            'youtube_url'   => $this->youtube_url,
            'linkedin_url'  => $this->linkedin_url,
            'website_url'   => $this->website_url,
        ]);
    }
};
?>

<div>
    <flux:heading size="sm" class="mb-4 flex items-center gap-2">
        <flux:icon name="share" class="w-4 h-4" />
        {{ __('app.profile.sections.social') }}
    </flux:heading>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <flux:input wire:model="facebook_url"  label="Facebook"    icon="link" placeholder="https://facebook.com/..." />
            <flux:error name="facebook_url" />
        </div>
        <div>
            <flux:input wire:model="instagram_url" label="Instagram"   icon="link" placeholder="https://instagram.com/..." />
            <flux:error name="instagram_url" />
        </div>
        <div>
            <flux:input wire:model="tiktok_url"    label="TikTok"      icon="link" placeholder="https://tiktok.com/@..." />
            <flux:error name="tiktok_url" />
        </div>
        <div>
            <flux:input wire:model="twitter_url"   label="X / Twitter" icon="link" placeholder="https://x.com/..." />
            <flux:error name="twitter_url" />
        </div>
        <div>
            <flux:input wire:model="youtube_url"   label="YouTube"     icon="link" placeholder="https://youtube.com/@..." />
            <flux:error name="youtube_url" />
        </div>
        <div>
            <flux:input wire:model="linkedin_url"  label="LinkedIn"    icon="link" placeholder="https://linkedin.com/in/..." />
            <flux:error name="linkedin_url" />
        </div>
        <div>
            <flux:input wire:model="website_url"   label="Site web"    icon="globe-alt" placeholder="https://..." />
            <flux:error name="website_url" />
        </div>
    </div>
</div>