<?php

/**
 * ⚠️ Ceci n'est PAS un fichier à copier tel quel.
 *
 * Dans ton fichier config/mail.php existant, ajoute simplement cette clé
 * quelque part dans le tableau retourné (par exemple juste après 'from') :
 *
 * 'to_address' => env('MAIL_TO_ADDRESS'),
 *
 * Cela permet au ContactController de lire l'adresse qui doit recevoir
 * les messages du formulaire de contact, configurée depuis le .env.
 */
