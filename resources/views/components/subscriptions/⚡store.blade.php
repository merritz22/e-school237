<?php

use Livewire\Component;
use App\Models\Subscription;
use App\Models\Level;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\GenericMail;
use App\Models\MailTemplate;
use App\Services\NotificationService;
use App\Services\SubscriptionPricing;
use App\Enums\SubscriptionStatus;
use App\Support\SubscriptionRules;

new class extends Component
{
    public $showModal = false;

    #[Validate('required', message: 'Le niveau est un champs obligatoire')]
    public $level;
    
    #[Validate('required', message: 'Le téléphone est un champs obligatoire')]
    #[Validate('regex:' . SubscriptionRules::PHONE_REGEX, message: 'Le numéro doit être un numéro camerounais valide (ex: 6XXXXXXXX)')]
    public $phone;

    #[Validate('required', message: 'Le prix est un champs obligatoire')]
    public $price;

    public $levels = [];

    public $plans = [];

    public $message = null;

    public function mount()
    {

        $this->levels = Level::where('is_active', 1)->get();
        $this->plans = config('subscriptions.plans');
    }

    public function closeModal()
    {

        $this->reset(['level', 'phone', 'price']);
        $this->showModal = false;

        // Redirige vers /home
        return $this->redirectRoute('home');
    }

    public function store()
    {

        $this->validate();

        $user = Auth::user();

        // Dates scolaires Cameroun
        [$startsAt, $endsAt] = SubscriptionPricing::schoolYearRange();

        $exists = Subscription::where('user_id', $user->id)
            ->where('level_id', $this->level)
            ->where('starts_at', $startsAt)
            ->exists();

        if ($exists) {
            $this->addError('level', 'Abonnement déjà existant pour ce niveau.');
            return;
        }

        $type = SubscriptionPricing::classify((float) $this->price);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'level_id' => $this->level,
            'subject_id' => null,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => SubscriptionStatus::Pending->value,
            'amount' => $this->price,
            'currency' => config('subscriptions.currency'),
            'phone' => $this->phone,
            'type' => $type->value,
        ]);

        // Création de la notification
        NotificationService::send(
            'WAITING_PAYMENT',
            Auth::user(),
            [
                'subscription_id' => $subscription->id
            ]
        );

        // construction et envoie du mail au demandeur et à l'admin
        $template = MailTemplate::where('code', 'SUBSCRIPTION_PENDING')->first();
        $templateAdmin = MailTemplate::where('code', 'SUBSCRIPTION_INFO_ADMIN')->first();

        $data = [
            'name' => $user->name,
            'amount' => $this->price,
            'level' => Level::find($this->level)->name,
        ];
        Mail::to($user->email)->send(new GenericMail($template, $data));
        Mail::to(config('mail.admin_address'))->send(new GenericMail($templateAdmin, $data));

        // 3️⃣ Afficher le modal de confirmation
        $this->showModal = true;

    }

    public function render()
    {

        return view('livewire.subscriptions.subscription', [
            'levels' => $this->levels,
            'plans' => $this->plans,
        ]);
    }
};
?>
