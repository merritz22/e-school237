<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{

    protected $fillable = [
        'user_id',
        'subject_id',
        'level_id',
        'starts_at',
        'ends_at',
        'status',
        'amount',
        'currency',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Relation Eloquent :
     * Cet élément (ex: inscription, abonnement, commande, etc.)
     * appartient à un utilisateur.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        // Relation inverse d’un hasMany :
        // - la clé étrangère est généralement user_id
        // - elle référence users.id
        return $this->belongsTo(User::class);
    }

    /**
     * Relation Eloquent :
     * Cet élément est associé à une matière / un sujet.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subject()
    {
        // Laravel suppose automatiquement :
        // - clé étrangère : subject_id
        // - table cible : subjects
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relation Eloquent :
     * Cet élément est associé à un niveau / un enregistrment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function level()
    {
        // Laravel suppose automatiquement :
        // - clé étrangère : level_id
        // - table cible : levels
        return $this->belongsTo(Level::class);
    }

    /**
     * Relation Eloquent :
     * Cet élément possède un paiement associé.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment()
    {
        // Relation one-to-one :
        // - la clé étrangère est payment.<model>_id (ex: subscription_id)
        // - chaque élément ne peut avoir qu’un seul paiement
        return $this->hasOne(Payment::class);
    }

}
