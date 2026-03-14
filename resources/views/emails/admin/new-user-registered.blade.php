<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .header { background: #4f46e5; padding: 28px 32px; color: #fff; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 4px 0 0; opacity: .75; font-size: 13px; }
        .body { padding: 28px 32px; }
        .field { margin-bottom: 16px; }
        .label { font-size: 11px; text-transform: uppercase; letter-spacing: .05em; color: #9ca3af; margin-bottom: 4px; }
        .value { font-size: 15px; font-weight: 600; color: #111827; }
        .badge { display: inline-block; background: #f0fdf4; color: #16a34a; border-radius: 9999px; padding: 3px 10px; font-size: 12px; font-weight: 600; }
        .footer { background: #f9fafb; padding: 16px 32px; font-size: 12px; color: #9ca3af; text-align: center; }
        .btn { display: inline-block; margin-top: 20px; background: #4f46e5; color: #fff; padding: 10px 22px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 Nouvel inscrit</h1>
            <p>Un nouvel utilisateur vient de créer son compte sur E-School237</p>
        </div>
        <div class="body">
            <div class="field">
                <div class="label">Nom complet</div>
                <div class="value">{{ $user->name }}</div>
            </div>
            <div class="field">
                <div class="label">Adresse email</div>
                <div class="value">{{ $user->email }}</div>
            </div>
            <div class="field">
                <div class="label">Inscrit le</div>
                <div class="value">{{ $user->created_at->format('d/m/Y à H:i') }}</div>
            </div>
            <div class="field">
                <div class="label">Statut</div>
                <div class="value"><span class="badge">✓ Compte actif</span></div>
            </div>
            <a href="{{ config('app.url') }}/admin/users" class="btn">
                Voir dans l'administration →
            </a>
        </div>
        <div class="footer">
            Ce mail est envoyé automatiquement par E-School237 · Ne pas répondre
        </div>
    </div>
</body>
</html>