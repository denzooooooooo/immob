<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class FavoriteController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the user's favorite properties.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's favorites with property details
        $favorites = Favorite::with(['property' => function ($query) {
                $query->with(['media', 'city']);
            }])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(12);

        // Get statistics
        $stats = [
            'total_favorites' => $favorites->total(),
            'for_sale_count' => Favorite::whereHas('property', function ($query) {
                $query->where('status', 'for_sale');
            })->where('user_id', $user->id)->count(),
            'for_rent_count' => Favorite::whereHas('property', function ($query) {
                $query->where('status', 'for_rent');
            })->where('user_id', $user->id)->count(),
        ];

        return view('favorites.index', compact('favorites', 'stats'));
    }

    /**
     * Toggle favorite status for a property.
     *
     * @param Request $request
     * @param Property $property
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle(Request $request, Property $property)
    {
        $user = Auth::user();
        
        $favorite = Favorite::where('user_id', $user->id)
                          ->where('property_id', $property->id)
                          ->first();

        if ($favorite) {
            $favorite->delete();
            $favorited = false;
            $message = 'Propriété retirée des favoris';
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'property_id' => $property->id
            ]);
            $favorited = true;
            $message = 'Propriété ajoutée aux favoris';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'favorited' => $favorited,
                'message' => $message
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove a property from favorites.
     *
     * @param Property $property
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Property $property)
    {
        $user = Auth::user();
        
        $favorite = Favorite::where('user_id', $user->id)
                          ->where('property_id', $property->id)
                          ->first();

        if ($favorite) {
            $favorite->delete();
            return redirect()->back()->with('success', 'Propriété retirée des favoris');
        }

        return redirect()->back()->with('error', 'Cette propriété n\'est pas dans vos favoris');
    }

    /**
     * Clear all favorites for the authenticated user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear()
    {
        $user = Auth::user();
        
        $count = Favorite::where('user_id', $user->id)->delete();
        
        return redirect()->back()->with('success', "Tous vos favoris ont été supprimés ({$count} propriétés)");
    }

    /**
     * Get favorites data for API consumption.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFavorites(Request $request)
    {
        $user = Auth::user();
        
        $query = Favorite::with(['property' => function ($q) {
                $q->with(['media', 'city']);
            }])
            ->where('user_id', $user->id);

        // Apply filters
        if ($request->filled('type')) {
            $query->whereHas('property', function ($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        if ($request->filled('city')) {
            $query->whereHas('property', function ($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('property', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort', 'created_at');
        switch ($sortBy) {
            case 'price_asc':
                $query->join('properties', 'favorites.property_id', '=', 'properties.id')
                      ->orderBy('properties.price', 'asc');
                break;
            case 'price_desc':
                $query->join('properties', 'favorites.property_id', '=', 'properties.id')
                      ->orderBy('properties.price', 'desc');
                break;
            case 'title':
                $query->join('properties', 'favorites.property_id', '=', 'properties.id')
                      ->orderBy('properties.title', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $favorites = $query->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $favorites
        ]);
    }
}
