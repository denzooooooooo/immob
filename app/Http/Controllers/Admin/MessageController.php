<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $query = Message::with(['sender', 'receiver', 'property']);

        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'read') {
                $query->whereNotNull('read_at');
            } elseif ($request->status === 'unread') {
                $query->whereNull('read_at');
            }
        }

        if ($request->filled('user')) {
            $userId = $request->user;
            $query->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                    ->orWhere('receiver_id', $userId);
            });
        }

        if ($request->filled('property')) {
            $query->where('property_id', $request->property);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                    ->orWhereHas('sender', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('receiver', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Tri
        $sort = $request->sort ?? 'created_at';
        $direction = $request->direction ?? 'desc';
        $query->orderBy($sort, $direction);

        $messages = $query->paginate(20)->withQueryString();

        // Statistiques
        $stats = [
            'total_messages' => Message::count(),
            'unread_messages' => Message::whereNull('read_at')->count(),
            'pending_messages' => Message::where('is_system_message', false)->whereNull('read_at')->count(),
            'reported_messages' => Message::where('is_system_message', true)->count(),
            'system_messages' => Message::where('is_system_message', true)->count(),
            'with_attachments' => Message::whereNotNull('attachment_path')->count(),
        ];

        // Données pour les filtres
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $properties = \App\Models\Property::orderBy('title')->get(['id', 'title']);
        $agents = User::where('role', 'agent')->orderBy('name')->get(['id', 'name']);

        return view('admin.messages.index', compact('messages', 'stats', 'users', 'properties', 'agents'));
    }

    public function show(Message $message)
    {
        $message->load(['sender', 'receiver', 'property']);

        // Marquer comme lu si ce n'est pas déjà fait
        if (!$message->read_at) {
            $message->markAsRead();
        }

        // Récupérer la conversation complète
        $conversation = Message::betweenUsers($message->sender_id, $message->receiver_id)
            ->where('property_id', $message->property_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.messages.show', compact('message', 'conversation'));
    }

    public function destroy(Message $message)
    {
        try {
            // Supprimer le fichier attaché s'il existe
            if ($message->attachment_path) {
                Storage::delete('public/' . $message->attachment_path);
            }

            $message->delete();

            return redirect()
                ->route('admin.messages.index')
                ->with('success', 'Message supprimé avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la suppression du message.');
        }
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:delete,mark_read,mark_unread',
            'messages' => 'required|array',
            'messages.*' => 'exists:messages,id',
        ]);

        $count = 0;

        try {
            switch ($validated['action']) {
                case 'delete':
                    foreach (Message::whereIn('id', $validated['messages'])->get() as $message) {
                        if ($message->attachment_path) {
                            Storage::delete('public/' . $message->attachment_path);
                        }
                        $message->delete();
                        $count++;
                    }
                    break;

                case 'mark_read':
                    $count = Message::whereIn('id', $validated['messages'])
                        ->whereNull('read_at')
                        ->update(['read_at' => now()]);
                    break;

                case 'mark_unread':
                    $count = Message::whereIn('id', $validated['messages'])
                        ->update(['read_at' => null]);
                    break;
            }

            return back()->with('success', "{$count} messages modifiés avec succès.");

        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de l\'action groupée.');
        }
    }

    public function markAsRead(Message $message)
    {
        if (!$message->read_at) {
            $message->update(['read_at' => now()]);
        }

        return back()->with('success', 'Message marqué comme lu.');
    }

    public function report(Message $message)
    {
        $message->update(['is_system_message' => true]);

        return back()->with('success', 'Message signalé.');
    }

    public function downloadAttachment(Message $message)
    {
        if (!$message->attachment_path) {
            abort(404);
        }

        return Storage::download('public/' . $message->attachment_path);
    }
}
