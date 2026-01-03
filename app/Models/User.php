<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'role',
        'avatar_url',
        'bio',
        'is_active',
        'last_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login' => 'datetime',
    ];

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is moderator or admin.
     */
    public function isModerator(): bool
    {
        return in_array($this->role, ['admin', 'moderator']);
    }

    /**
     * Check if user have rights.
     */
    public function hasRole($roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Check if user can publish content.
     */
    public function canPublish(): bool
    {
        return in_array($this->role, ['admin', 'moderator', 'author']);
    }

    // Relations

    /**
     * Articles authored by this user.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    /**
     * Evaluation subjects created by this user.
     */
    public function evaluationSubjects(): HasMany
    {
        return $this->hasMany(EvaluationSubject::class, 'author_id');
    }

    /**
     * Educational resources uploaded by this user.
     */
    public function educationalResources(): HasMany
    {
        return $this->hasMany(EducationalResource::class, 'uploader_id');
    }

    /**
     * Forum topics created by this user.
     */
    public function forumTopics(): HasMany
    {
        return $this->hasMany(ForumTopic::class, 'author_id');
    }

    /**
     * Forum replies by this user.
     */
    public function forumReplies(): HasMany
    {
        return $this->hasMany(ForumReply::class, 'author_id');
    }

    /**
     * User likes.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(UserLike::class);
    }

    /**
     * Download logs for this user.
     */
    public function downloadLogs(): HasMany
    {
        return $this->hasMany(DownloadLog::class);
    }

    /**
     * Scope for active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for users by role.
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Relation Eloquent :
     * Un utilisateur (ou modèle courant) peut avoir plusieurs abonnements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        // Déclare une relation "hasMany" vers le modèle Subscription
        // Laravel suppose automatiquement :
        // - clé étrangère : subscription.user_id
        // - clé primaire locale : users.id
        return $this->hasMany(Subscription::class);
    }

    /**
     * Récupère uniquement les abonnements actifs.
     *
     * Conditions :
     * - le statut doit être "active"
     * - la date de fin (ends_at) ne doit pas être dépassée
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function activeSubscriptions()
    {
        // On repart de la relation subscriptions()
        // afin de chaîner des conditions supplémentaires
        return $this->subscriptions()
            ->where('status', 'active')     // abonnement marqué comme actif
            ->where('ends_at', '>=', now()); // abonnement toujours valide (non expiré)
    }

    /**
     * Vérifie si l'utilisateur possède un abonnement actif
     * pour une matière (subject) donnée.
     *
     * Règles de validation :
     * - l'abonnement doit être lié au subject passé en paramètre
     * - son statut doit être "active"
     * - sa date de fin ne doit pas être dépassée
     *
     * @param  int  $subjectId  Identifiant du subject à vérifier
     * @return bool Retourne true si un abonnement valide existe, sinon false
     */
    public function hasActiveSubscriptionForSubject($subjectId): bool
    {
        // On interroge la relation subscriptions() de l'utilisateur
        // sans charger les données en mémoire (exists() est optimisé)
        return $this->subscriptions()
            ->where('subject_id', $subjectId) // abonnement lié à la matière ciblée
            ->where('status', 'active')       // abonnement actif
            ->where('ends_at', '>=', now())   // abonnement non expiré
            ->exists();                       // true/false selon l'existence
    }

}