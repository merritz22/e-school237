<?php

use Livewire\Component;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Auth;
use App\Models\AppSettings;
use App\Models\Subscription;
use App\Models\DownloadLog;
use Carbon\Carbon;

new class extends Component
{
    #[Url]  // ← lie automatiquement $reason au query param ?reason=...
    public string $reason = 'daily';

    public int $dailyDownloads = 0;
    public int $monthlyDownloads = 0;
    public int $dailyLimit = 0;
    public int $monthlyLimit = 0;
    public int $freeLimit = 0;
    public string $adminEmail = '';
    public string $monthlyResetDate = '';

    public function mount(): void
    {
        $user = Auth::user();

        
        $this->dailyLimit = (int) AppSettings::where('code', 'DAILY_DOWNLOAD_LIMIT')->value('value');

        $this->freeLimit  = (int) AppSettings::where('code', 'FREE_DOWNLOAD_LIMIT')->value('value') ?: 5;

        $this->dailyDownloads = DownloadLog::where('user_id', $user->id)
            ->whereDate('downloaded_at', Carbon::today())
            ->count();

        $this->monthlyDownloads = DownloadLog::where('user_id', $user->id)
            ->whereMonth('downloaded_at', Carbon::now()->month)
            ->whereYear('downloaded_at', Carbon::now()->year)
            ->count();

        $subscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if ($subscription) {
            $limitCode = match ($subscription->type) {
                'CLASSIC'  => 'MONTHLY_DOWNLOAD_LIMIT_P1',
                'PREMIUM'  => 'MONTHLY_DOWNLOAD_LIMIT_P2',
                default    => null,
            };
            if ($limitCode) {
                $this->monthlyLimit = (int) AppSettings::where('code', $limitCode)->value('value');
            }
        }

        $this->adminEmail = config('mail.admin_address', 'admin@e-school237.com');

        $this->monthlyResetDate = now()->addMonth()->translatedFormat('F Y');
    }
}


?>

<div class="w-full max-w-lg mx-auto">

    {{-- Icône animée --}}
    <div class="flex justify-center mb-8">
        <div class="relative">
            <div class="w-20 h-20 rounded-2xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shadow-lg">
                @if ($reason === 'no_subscription')
                    <flux:icon name="lock-closed" class="w-10 h-10 text-amber-500" />
                @elseif ($reason === 'wrong_level')
                    <flux:icon name="academic-cap" class="w-10 h-10 text-blue-500" />
                @else
                    <flux:icon name="arrow-down-tray" class="w-10 h-10 text-amber-500" />
                @endif
            </div>
            <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-red-500 flex items-center justify-center shadow">
                <flux:icon name="exclamation-triangle" class="w-3.5 h-3.5 text-white" />
            </div>
        </div>
    </div>

    {{-- Titre & description --}}
    <div class="text-center mb-8 space-y-2">
        <h1 class="text-2xl font-bold text-zinc-800 dark:text-white">
            {{ __('app.download_limit.' . $reason . '.title') }}
        </h1>
        <p class="text-zinc-500 dark:text-zinc-400 text-sm leading-relaxed">
            @if ($reason === 'monthly')
                {!! __('app.download_limit.monthly.description', ['count' => $monthlyDownloads]) !!}
            @elseif ($reason === 'daily')
                {!! __('app.download_limit.daily.description', ['limit' => $dailyLimit]) !!}
            @elseif ($reason === 'free_download_limit')
                {!! __('app.download_limit.free_download_limit.description', ['limit' => $freeLimit]) !!}
            @else
                {{ __('app.download_limit.' . $reason . '.description') }}
            @endif
        </p>
    </div>

    {{-- Carte principale --}}
    <flux:card class="shadow-sm border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 space-y-6 bg-white dark:bg-zinc-900">

        {{-- Statistiques --}}
        @if(in_array($reason, ['daily', 'monthly']))
        <div class="grid grid-cols-2 gap-4">
            <div class="rounded-xl bg-zinc-50 dark:bg-zinc-800 p-4 text-center">
                <p class="text-xs text-zinc-400 dark:text-zinc-500 uppercase tracking-wider mb-1">
                    {{ __('app.download_limit.stats.today') }}
                </p>
                <p class="text-2xl font-bold text-zinc-800 dark:text-white">{{ $dailyDownloads }}</p>
                <p class="text-xs text-zinc-400 mt-0.5">
                    {{ __('app.download_limit.stats.downloads_of', ['limit' => $dailyLimit]) }}
                </p>
            </div>
            <div class="rounded-xl bg-zinc-50 dark:bg-zinc-800 p-4 text-center">
                <p class="text-xs text-zinc-400 dark:text-zinc-500 uppercase tracking-wider mb-1">
                    {{ __('app.download_limit.stats.this_month') }}
                </p>
                <p class="text-2xl font-bold text-zinc-800 dark:text-white">{{ $monthlyDownloads }}</p>
                <p class="text-xs text-zinc-400 mt-0.5">
                    {{ __('app.download_limit.stats.downloads') }}
                </p>
            </div>
        </div>
        @endif

        <flux:separator />

        {{-- Options --}}
        <div class="space-y-3">
            <p class="text-xs font-semibold text-zinc-400 dark:text-zinc-500 uppercase tracking-wider">
                {{ __('app.download_limit.actions.title') }}
            </p>

            @if ($reason === 'no_subscription' || $reason === 'free_download_limit')
                <flux:button
                    href="{{ route('subscriptions.index') }}"
                    variant="primary"
                    class="w-full justify-center"
                    icon="sparkles"
                >
                    {{ __('app.download_limit.actions.subscribe') }}
                </flux:button>
            @elseif (in_array($reason, ['daily', 'monthly', 'wrong_level']))
                <flux:button
                    href="mailto:{{ $adminEmail }}?subject={{ urlencode(__('app.download_limit.contact_subject')) }}&body={{ urlencode(__('app.download_limit.contact_body')) }}"
                    variant="primary"
                    class="w-full justify-center"
                    icon="envelope"
                >
                    {{ __('app.download_limit.actions.contact_admin') }}
                </flux:button>
            @endif
        </div>

        {{-- Info temporelle — ajouter le cas free --}}
        @if ($reason === 'free_download_limit')
        <div class="flex items-start gap-3 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-100 dark:border-amber-900 p-4">
            <flux:icon name="clock" class="w-4 h-4 text-amber-400 mt-0.5 shrink-0" />
            <p class="text-xs text-amber-600 dark:text-amber-400 leading-relaxed">
                {!! __('app.download_limit.info.daily_reset') !!}
            </p>
        </div>
        @elseif ($reason === 'daily')
        <div class="flex items-start gap-3 rounded-xl bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900 p-4">
            <flux:icon name="clock" class="w-4 h-4 text-blue-400 mt-0.5 shrink-0" />
            <p class="text-xs text-blue-600 dark:text-blue-400 leading-relaxed">
                {!! __('app.download_limit.info.daily_reset') !!}
            </p>
        </div>
        @elseif ($reason === 'monthly')
        <div class="flex items-start gap-3 rounded-xl bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900 p-4">
            <flux:icon name="calendar" class="w-4 h-4 text-blue-400 mt-0.5 shrink-0" />
            <p class="text-xs text-blue-600 dark:text-blue-400 leading-relaxed">
                {!! __('app.download_limit.info.monthly_reset', [
                    'date' => $monthlyResetDate
                ]) !!}
            </p>
        </div>
        @endif

    </flux:card>

    {{-- Footer --}}
    <p class="text-center text-xs text-zinc-400 dark:text-zinc-600 mt-6">
        {{ __('app.download_limit.footer.help') }}
        <a href="mailto:{{ $adminEmail }}" class="underline hover:text-zinc-600 dark:hover:text-zinc-400 transition-colors">
            {{ $adminEmail }}
        </a>
    </p>

</div>

