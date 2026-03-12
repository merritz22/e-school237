<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MailTemplate;

class MailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MailTemplate::insert([[
            'code' => 'SUBSCRIPTION_PENDING',
            'subject' => 'Activation de votre abonnement',
            'html_content' => '
                <!DOCTYPE html>
                <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <title>Activation d\'abonnement</title>
                    <style>
                        body { font-family: Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 0; }
                        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 30px; }
                        .header svg { width: 60px; height: 60px; }
                        h2 { color: #f8c81b; font-size: 22px; margin: 20px 0; }
                        .step-box { background: #f3f4f6; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
                        ol { padding-left: 20px; }
                        .payment-method { border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; text-align: center; margin-bottom: 15px; }
                        .payment-method img { height: 50px; margin-bottom: 10px; }
                        .orange { color: #f97316; font-weight: bold; font-size: 18px; }
                        .yellow { color: #facc15; font-weight: bold; font-size: 18px; }
                        .note { font-size: 12px; color: #6b7280; text-align: center; margin-top: 15px; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header" style="text-align:center;">
                            <h2>Abonnement en attente d\'activation</h2>
                        </div>

                        <div class="step-box">
                            <h3>Étapes d’activation de l’abonnement</h3>
                            <p>Bonjour <b>{{name}}</b>,</p>
                            <ol>
                                <li>Payer le montant de <b id="to_pay">{{amount}} XAF</b>pour de la classe de <b>{{level}}</b> sur l’un des comptes ci-dessous.</li>
                                <li>Une fois votre paiement effectué, veuillez patienter pendant l’activation.</li>
                                <li>La validation de l’abonnement peut prendre jusqu’à <strong>24 heures</strong>.</li>
                            </ol>
                        </div>

                        <div class="payment-methods">
                            <div class="payment-method">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/c/c8/Orange_logo.svg" alt="Orange Money">
                                <p class="font-semibold">Orange Money</p>
                                <p>Numéro de paiement</p>
                                <p class="orange">696090236</p>
                                <p>POUOKAM NGUEGUIM</p>
                            </div>

                            <div class="payment-method">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/9/93/New-mtn-logo.jpg" alt="MTN Mobile Money">
                                <p class="font-semibold">MTN Mobile Money</p>
                                <p>Numéro de paiement</p>
                                <p class="yellow">651993749</p>
                                <p>POUOKAM NGUEGUIM</p>
                            </div>
                        </div>
                    </div>
                </body>
                </html>
            ',
        ],
        [
            'code' => 'SUBSCRIPTION_INFO_ADMIN',
            'subject' => 'Demande d\'abonnement',
            'html_content' => '
                <!DOCTYPE html>
                <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <style>
                        body { font-family: Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 0; }
                        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 30px; }
                        .header svg { width: 60px; height: 60px; }
                        h2 { color: #f8c81b; font-size: 22px; margin: 20px 0; }
                        .step-box { background: #f3f4f6; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
                        ol { padding-left: 20px; }
                        .payment-method { border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; text-align: center; margin-bottom: 15px; }
                        .payment-method img { height: 50px; margin-bottom: 10px; }
                        .orange { color: #f97316; font-weight: bold; font-size: 18px; }
                        .yellow { color: #facc15; font-weight: bold; font-size: 18px; }
                        .note { font-size: 12px; color: #6b7280; text-align: center; margin-top: 15px; }
                    </style>
                </head>
                <body>
                    <p> Bonjour Mr/Mme,</p>
                    <p> Un abonnement vient d\'être initié par <b>{{name}}</b> pour un abonnement de <b>{{amount}} XAF</b> concernant la classe de <b>{{level}}</b>.</p> 
                </body>
                </html>
            ',
        ]]);
    }
}
