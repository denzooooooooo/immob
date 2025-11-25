<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Message::where(function($q) use ($user) {
            $q->where('sender_id', $user->id)
              ->orWhere('receiver_id', $user->id);
        })->with(['sender', 'receiver', 'property']);
        
        // Filtres
        if ($request->filled('property')) {
            $query->where('property_id', $request->property);
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->whereNull('read_at')
                      ->where('receiver_id', $user->id);
            } elseif ($request->status === 'read') {
                $query->whereNotNull('read_at')
                      ->where('receiver_id', $user->id);
            }
        }
        
        if ($request->filled('type')) {
            if ($request->type === 'sent') {
                $query->where('sender_id', $user->id);
            } elseif ($request->type === 'received') {
                $query->where('receiver_id', $user->id);
            }
        }
        
        // Tri
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $messages = $query->paginate(20);
        
        // Statistiques
        $stats = [
            'total' => Message::where('receiver_id', $user->id)->count(),
            'unread' => Message::where('receiver_id', $user->id)->whereNull('read_at')->count(),
            'sent' => Message::where('sender_id', $user->id)->count(),
            'properties_with_messages' => Message::where(function($q) use ($user) {
                $q->where('sender_id', $user->id)
                  ->orWhere('receiver_id', $user->id);
            })->distinct('property_id')->count(),
        ];
        
        // Propriétés de l'agent pour le filtre
        $properties = Property::where('user_id', $user->id)
            ->select('id', 'title')
            ->orderBy('title')
            ->get();
        
        return view('agent.messages.index', compact('messages', 'stats', 'properties'));
    }

    public function show(Message $message)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est impliqué dans la conversation
        if ($message->sender_id !== $user->id && $message->receiver_id !== $user->id) {
            abort(403);
        }
        
        // Marquer comme lu si c'est un message reçu
        if ($message->receiver_id === $user->id && !$message->read_at) {
            $message->markAsRead();
        }
        
        // Charger la conversation complète
        $conversation = Message::where(function($q) use ($message) {
            $q->where([
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'property_id' => $message->property_id
            ])->orWhere([
                'sender_id' => $message->receiver_id,
                'receiver_id' => $message->sender_id,
                'property_id' => $message->property_id
            ]);
        })->orderBy('created_at', 'asc')->get();
        
        return view('agent.messages.show', compact('message', 'conversation'));
    }

    public function reply(Request $request, Message $message)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est impliqué dans la conversation
        if ($message->sender_id !== $user->id && $message->receiver_id !== $user->id) {
            abort(403);
        }
        
        $validated = $request->validate([
            'content' => 'required|string|max:5000'
        ]);
        
        // Créer la réponse
        $reply = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $message->sender_id === $user->id 
                ? $message->receiver_id 
                : $message->sender_id,
            'property_id' => $message->property_id,
            'content' => $validated['content'],
            'parent_id' => $message->id
        ]);
        
        return redirect()->route('agent.messages.show', $reply)
            ->with('success', 'Réponse envoyée avec succès !');
    }

    public function markAsRead(Message $message)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est le destinataire
        if ($message->receiver_id !== $user->id) {
            abort(403);
        }
        
        $message->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'Message marqué comme lu'
        ]);
    }
}
