<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Mail;
use App\Models\Debt;
use App\Mail\DebtReminderMail;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
*/

// Commande de base (Inspiration)
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Commande de rappel automatique SILVER FIN
 * Cette commande parcourt les dettes qui expirent dans 2 jours
 */
Artisan::command('debts:send-reminders', function () {
    $this->info('Vérification des échéances en cours...');

    // On cible les dettes 'en_attente' dont l'échéance est dans exactement 2 jours
    $targetDate = now()->addDays(2)->toDateString();
    
    $debtsToRemind = Debt::where('due_date', $targetDate)
                        ->where('status', 'en_attente')
                        ->whereNotNull('contact_email')
                        ->get();

    if ($debtsToRemind->isEmpty()) {
        $this->comment('Aucun rappel à envoyer pour le ' . $targetDate);
        return;
    }

    foreach ($debtsToRemind as $debt) {
        try {
            Mail::to($debt->contact_email)->send(new DebtReminderMail($debt));
            $this->info("Rappel envoyé à : {$debt->contact_name} ({$debt->contact_email})");
        } catch (\Exception $e) {
            $this->error("Erreur pour {$debt->contact_name} : " . $e->getMessage());
        }
    }

    $this->info('Traitement terminé.');
})->purpose('Envoyer des emails de rappel automatique pour les dettes arrivant à échéance');

/**
 * Planification (Scheduler)
 * Exécute la commande de rappel tous les jours à 08:00
 */
Schedule::command('debts:send-reminders')->dailyAt('08:00');