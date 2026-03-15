





15/03/2026

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
reconfigurer le list de la section sujet et support avec ce filtre
Le Suivis particulier se fera à terme sur la plateforme
Une notice avertissant que si la photo ne correspond pas à l'utilisateur, celui-ci sera supprimé