@extends('layouts.app')

@section('title', 'Abonnement')

@section('content')

<div class="min-h-screen bg-none flex flex-col items-center justify-top py-6">

    <!-- Header -->
    <div class="text-center mb-12 text-[#03386a]">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">
            Démarrer avec un abonnement
        </h1>
        <p class="text-lg">
            Profitez des resources. Resiliez à tout moment.
        </p>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-6xl w-full text-white">
        <!-- Élèves – Classique -->
        <div class="bg-gradient-to-b from-[#0e243a] to-[#03386a] rounded-3xl p-8 shadow-2xl">
            <h2 class="text-2xl font-bold mb-2 text-center py-2">
                Abonnement Classique Élèves
            </h2>

            <p class="text-center text-gray-300 mb-4">
                Apprends mieux, progresse plus vite et réussis ton année scolaire.
            </p>

            <span class="flex justify-between">
                <p class="text-gray-300 mb-6 font-bold">Prix : 10 000 XAF</p>
                <p class="text-gray-300 mb-6 font-bold">Durée : 1 an</p>
            </span>

            <ul class="space-y-4 mb-8">
                <li class="flex items-start gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-white text-black text-sm">✓</span>
                    <span>
                        Accès complet aux cours, articles pédagogiques et supports de révision
                    </span>
                </li>
                {{-- <li class="flex items-start gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-white text-black text-sm">✓</span>
                    <span>
                        Téléchargements illimités pour étudier même hors connexion
                    </span>
                </li> --}}
                <li class="flex items-start gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-white text-black text-sm">✓</span>
                    <span>
                        Accompagnement personnalisé via WhatsApp pour poser tes questions
                    </span>
                </li>
            </ul>

            <a href="subscription/create" class="block mx-auto w-fit bg-[#ffb938] hover:bg-[#f0a928] text-black font-semibold p-4 rounded-full text-lg transition">
                Je m’abonne maintenant
            </a>
        </div>

        <!-- Élèves – Premium -->
        <div class="bg-gradient-to-b from-[#0e243a] to-[#03386a] rounded-3xl p-8 shadow-2xl border-2 border-[#ffb938]">
            <h2 class="text-2xl font-bold mb-1 text-center py-2">
                Abonnement Premium Élèves
            </h2>

            <p class="text-center text-[#ffb938] font-semibold mb-4">
                ⭐ Le plus choisi par les élèves en classes d’examen
            </p>

            <span class="flex justify-between">
                <p class="text-gray-300 mb-6 font-bold">
                    Prix : <span class="line-through">10 000 XAF</span> 9 000 XAF
                </p>
                <p class="text-gray-300 mb-6 font-bold">Durée : 1 an</p>
            </span>

            <ul class="space-y-4 mb-8">
                <li class="flex items-start gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-white text-black text-sm">✓</span>
                    <span>
                        Réduction automatique de 1 000 XAF par matière sélectionnée
                    </span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-white text-black text-sm">✓</span>
                    <span>
                        Accès complet à tous les cours, supports et sujets
                    </span>
                </li>
                {{-- <li class="flex items-start gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-white text-black text-sm">✓</span>
                    <span>
                        Téléchargements illimités pour toutes les matières
                    </span>
                </li> --}}
                <li class="flex items-start gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-white text-black text-sm">✓</span>
                    <span>
                        Suivi prioritaire et personnalisé via WhatsApp avec nos encadreurs
                    </span>
                </li>
            </ul>

            <a href="subscription/create" class="block mx-auto w-fit bg-[#ffb938] hover:bg-[#f0a928] text-black font-semibold p-4 rounded-full text-lg transition">
                Commencer mon abonnement
            </a>
        </div>

        <!-- Professeurs – Classique -->
        <div class="bg-gradient-to-b from-[#0e243a] to-[#03386a] rounded-3xl p-8 shadow-2xl">
            <h2 class="text-2xl font-bold mb-2 text-center py-2">
                Abonnement Classique Professeurs
            </h2>

            <p class="text-center text-gray-300 mb-4">
                Partage ton savoir et accompagne les élèves vers la réussite.
            </p>

            <span class="flex justify-between">
                <p class="text-gray-300 mb-6 font-bold">Prix : 15 000 XAF</p>
                <p class="text-gray-300 mb-6 font-bold">Durée : 1 an</p>
            </span>

            <ul class="space-y-4 mb-8">
                <li class="flex items-start gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-white text-black text-sm">✓</span>
                    <span>
                        Accès complet aux articles, supports pédagogiques et sujets
                    </span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-white text-black text-sm">✓</span>
                    <span>
                        Publication de tes articles, supports et sujets éducatifs
                    </span>
                </li>
                {{-- <li class="flex items-start gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-white text-black text-sm">✓</span>
                    <span>
                        Téléchargements illimités des ressources pédagogiques
                    </span>
                </li> --}}
                <li class="flex items-start gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-white text-black text-sm">✓</span>
                    <span>
                        Suivi et encadrement des élèves via WhatsApp
                    </span>
                </li>
            </ul>

            <a href="subscription/create" class="block mx-auto w-fit bg-[#ffb938] hover:bg-[#f0a928] text-black font-semibold p-4 rounded-full text-lg transition">
                Devenir enseignant partenaire
            </a>
        </div>

        <!-- Professeurs – Premium -->
        <div class="bg-gradient-to-b from-[#0e243a] to-[#03386a] rounded-3xl p-8 shadow-2xl border-2 border-[#ffb938]">
            <h2 class="text-2xl font-bold mb-1 text-center py-2">
                Abonnement Premium Professeurs
            </h2>

            <p class="text-center text-[#ffb938] font-semibold mb-4">
                ⭐ Recommandé pour les enseignants actifs
            </p>

            <span class="flex justify-between">
                <p class="text-gray-300 mb-6 font-bold">
                    Prix : <span class="line-through">15 000 XAF</span> 14 000 XAF
                </p>
                <p class="text-gray-300 mb-6 font-bold">Durée : 1 an</p>
            </span>

            <ul class="space-y-4 mb-8">
                <li class="flex items-start gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-white text-black text-sm">✓</span>
                    <span>
                        Accès complet aux articles, supports pédagogiques et sujets
                    </span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-white text-black text-sm">✓</span>
                    <span>
                        Réduction de 1 000 XAF par matière encadrée
                    </span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-white text-black text-sm">✓</span>
                    <span>
                        Publication prioritaire de tes contenus pédagogiques
                    </span>
                </li>
                {{-- <li class="flex items-start gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-white text-black text-sm">✓</span>
                    <span>
                        Téléchargements illimités des supports et sujets
                    </span>
                </li> --}}
                <li class="flex items-start gap-3">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-white text-black text-sm">✓</span>
                    <span>
                        Suivi avancé et accompagnement des élèves via WhatsApp
                    </span>
                </li>
            </ul>

            <a href="subscription/create" class="block mx-auto w-fit bg-[#ffb938] hover:bg-[#f0a928] text-black font-semibold p-4 rounded-full text-lg transition">
                Activer le plan premium
            </a>
        </div>


    </div>
@endsection