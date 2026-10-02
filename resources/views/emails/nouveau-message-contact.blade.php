<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Nouveau message</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222; line-height: 1.6;">
    <h2>📩 Nouveau message reçu depuis ton portfolio</h2>

    <p><strong>Nom :</strong> {{ $contactMessage->nom }}</p>
    <p><strong>Email :</strong> {{ $contactMessage->email }}</p>
    <p><strong>Sujet :</strong> {{ $contactMessage->sujet ?: 'Sans sujet' }}</p>

    <hr>

    <p><strong>Message :</strong></p>
    <p>{{ $contactMessage->message }}</p>

    <hr>

    <p style="font-size: 12px; color: #888;">
        Tu peux répondre directement à cet e-mail, il partira à l'adresse du visiteur.
    </p>
</body>
</html>
