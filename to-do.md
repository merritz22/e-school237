Ajouter une section tests avec des épreuves préfaites pour chaque niveau, chaque épreuves ayant une durée et sous forme d'agit par compétences. Un système de note et de classement sera également mis en place pour les meilleurs. Avec des avantages ?
Réorganiser les bannières en ajoutant une section formations (plus bénéfiques pour moi...
Les formatteurs feront des formations et l'administrateur touchera un pourcentage)=> Accueil - Archives scolaire - Formations - abonnements - FAQ
Comment faire bénéficier les professeurs. Ils vont faire des intitulés de formations qui seront suivis par des éleves/indépendants avec possibilité de souscrire à une formation comme sur Udemy. Chaque professeur devrat faire des publications gratuites et d'autres payantes afin d'attirer des intéressé. (Prévoir une section vidéo-éducative ?)
Le Suivis particulier se fera à terme sur la plateforme
Une notice avertissant que si la photo ne correspond pas à l'utilisateur, celui-ci sera supprimé

11/04/2026
Mise à jour de la gestions des abonnements
Limittations sur le nombre de téléchargement autorisés pour la durrée de l'abonnements.
Valeur à défifinir dans l'administration ?. 
Elle dure pour la période de l'abonnement en cours.
3 000 => 60 téléchargements par mois (limité à 10 téléchargements par jour)
6 000 => 150 téléchargements par mois (limité à 10 téléchargements par jour)
8 000 => Téléchargement illimité (limité à 10 téléchargements par jour)
Créer un middleware qui va gérer l'autorisation de téléchargement
Mettre à jour les textes de suscription à un abonnement
Prévoir le message à afficher dans la pop-up en cas d'incapacité de téléchargement.
Rendre le numéro whatsapp obligatoire
Mise à jour BD PROD
=>...
UPDATE subscriptions s SET s.type = 'CLASSIC'
WHERE s.amount = 3000.0;

UPDATE subscriptions s SET s.type = 'PREMIUM'
WHERE s.amount = 6000.0;

UPDATE subscriptions s SET s.type = 'ADVANCED'
WHERE s.amount = 8000.0;





15/03/2026
reconfigurer le list de la section sujet et support avec ce filtre

Dans la section profile, prévoir
    Renseigner les informations suivante stockée dans user_informations:
        =>l'établissement fréuenté, 
        =>la date de naissance, 
        =>une photo de profil, 
        =>leur sexe, 
        =>coché une case: besoin de suivis particulier ?
        =>Sélectionner parmis les matières de sa classe celles ou il s'en sort le mieux,
        =>Sélectionner parmis les matières de sa classe celles ou il s'en sort le moins,
        =>la profession (Eleves, Etudiant, Professeur, Indépendant) => à stocker en base dans une table
        =>La classe actuel (qui devra être mise à jour chaque année), activé ou désactiver le filtre sur sa classe (seulement pour les abonnés) -> le bouton de filtre est désactiver si l'utilisateur n'est abonné à aucune classe, prendre en compte le fait que l'utilisateur peut être abonner à plusieurs classe.
    tant que le profile n'est pas encore compléter, il faut que l'icône du profile dans le layout app clignote, faire un fonction booléenne qui vérifie si le profile est complet ou non. Pareillement tout les champs obligatoire du profile doivent avoir une icone rouge en haut à droite pour faire comprendre à l'utilisateur qu'il doit remplit ces champs s'il sont vide. ajouter dans la table user_informations une colonne pour savoir si c'est un champs obligatoire.
    Améliorations UX recommandées

