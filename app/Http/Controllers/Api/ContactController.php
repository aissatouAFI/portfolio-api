<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\NouveauMessageContact;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * POST /api/contact (public)
     * Enregistre le message en base ET envoie un mail à l'adresse configurée dans .env (MAIL_TO_ADDRESS).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'sujet' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $contactMessage = ContactMessage::create($validator->validated());

        try {
            Mail::to(config('mail.to_address'))
                ->send(new NouveauMessageContact($contactMessage));
        } catch (\Throwable $e) {
            // Le message reste enregistré en base même si l'envoi mail échoue.
            report($e);
        }

        return response()->json([
            'message' => 'Ton message a bien été envoyé, merci !',
        ], 201);
    }

    /** GET /api/admin/contact-messages (admin) */
    public function index()
    {
        return response()->json(ContactMessage::latest()->get());
    }

    /** PATCH /api/admin/contact-messages/{id}/lu (admin) - marquer comme lu */
    public function marquerLu(ContactMessage $contactMessage)
    {
        $contactMessage->update(['lu' => true]);
        return response()->json($contactMessage);
    }

    /** DELETE /api/admin/contact-messages/{id} (admin) */
    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();
        return response()->json(['message' => 'Message supprimé']);
    }
}
