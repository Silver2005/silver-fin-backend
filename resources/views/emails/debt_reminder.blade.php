<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; line-height: 1.6; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; border: 1px solid #eee; border-radius: 20px; overflow: hidden; }
        .header { background-color: #2563eb; color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; }
        .amount-box { background-color: #f8fafc; border-radius: 15px; padding: 20px; text-align: center; margin: 20px 0; border: 1px solid #e2e8f0; }
        .amount { font-size: 28px; font-weight: bold; color: #1e293b; }
        .footer { background-color: #f1f5f9; color: #64748b; padding: 20px; text-align: center; font-size: 12px; }
        .btn { display: inline-block; padding: 12px 25px; background-color: #2563eb; color: white; text-decoration: none; border-radius: 10px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin:0;">SILVER FIN</h2>
            <p style="margin:0; opacity:0.8;">Gestion intelligente de vos engagements</p>
        </div>
        <div class="content">
            <p>Bonjour <strong>{{ $debt->contact_name }}</strong>,</p>
            <p>Ceci est un rappel automatique concernant votre engagement financier enregistré sur notre plateforme.</p>
            
            <div class="amount-box">
                <p style="margin:0; font-size:12px; text-transform:uppercase; color:#64748b;">Montant dû</p>
                <div class="amount">{{ number_format($debt->amount, 0, ',', ' ') }} FCFA</div>
                <p style="margin:10px 0 0 0; font-size:14px; color:#ef4444;">Échéance : {{ $debt->due_date->format('d/m/Y') }}</p>
            </div>

            @if($debt->notes)
                <p style="color: #64748b; font-style: italic;">Note : "{{ $debt->notes }}"</p>
            @endif

            <p>Nous vous remercions de bien vouloir prendre les dispositions nécessaires pour le règlement de cette somme avant la date indiquée.</p>
            
            <p>Cordialement,<br><strong>L'équipe SILVER FIN</strong></p>
        </div>
        <div class="footer">
            Cet email a été envoyé automatiquement. Merci de ne pas y répondre directement.
        </div>
    </div>
</body>
</html>