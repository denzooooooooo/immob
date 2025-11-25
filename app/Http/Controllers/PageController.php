<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $stats = [
            'total_properties' => Property::where('published', true)->count(),
            'total_cities' => City::count(),
            'total_agents' => User::where('role', 'agent')->where('status', 'active')->count(),
            'total_users' => User::where('status', 'active')->count(),
        ];

        return view('pages.about', compact('stats'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Sauvegarder le message en base de données
        \App\Models\Message::create([
            'sender_id' => 1, // Utiliser un utilisateur système ou admin existant
            'receiver_id' => 1, // Admin principal
            'property_id' => null,
            'content' => "Nouveau message de contact:\n\nNom: {$request->name}\nEmail: {$request->email}\nSujet: {$request->subject}\n\nMessage:\n{$request->message}",
            'type' => 'text', // Utiliser 'text' au lieu de 'contact'
            'is_system_message' => true, // Marquer comme message système
        ]);

        // Ici vous pouvez aussi envoyer un email si nécessaire
        // Mail::to('admin@carrepremiumimmo.ci')->send(new ContactMessage($request->all()));

        return back()->with('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');
    }

    public function terms()
    {
        return view('pages.terms');
    }
}
