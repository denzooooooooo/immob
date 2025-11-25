<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FavoriteApiController extends Controller
{
    /**
     * Toggle favorite status for a property
     *
     * @param Request $request
     * @param Property $property
     * @return JsonResponse
     */
    public function toggle(Request $request, Property $property): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié'
                ], 401);
            }

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

            return response()->json([
                'success' => true,
                'favorited' => $favorited,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's favorite properties
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié'
                ], 401);
            }

            $favorites = Favorite::with(['property' => function ($query) {
                    $query->with(['media', 'city']);
                }])
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(12);

            return response()->json([
                'success' => true,
                'data' => $favorites
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk add/remove favorites
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulk(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié'
                ], 401);
            }

            $request->validate([
                'property_ids' => 'required|array',
                'property_ids.*' => 'exists:properties,id',
                'action' => 'required|in:add,remove'
            ]);

            $propertyIds = $request->property_ids;
            $action = $request->action;
            $count = 0;

            if ($action === 'add') {
                $existingFavorites = Favorite::where('user_id', $user->id)
                    ->whereIn('property_id', $propertyIds)
                    ->pluck('property_id')
                    ->toArray();

                $newFavorites = array_diff($propertyIds, $existingFavorites);
                
                foreach ($newFavorites as $propertyId) {
                    Favorite::create([
                        'user_id' => $user->id,
                        'property_id' => $propertyId
                    ]);
                    $count++;
                }

                $message = $count > 0 ? "{$count} propriétés ajoutées aux favoris" : "Ces propriétés sont déjà dans vos favoris";
            } else {
                $count = Favorite::where('user_id', $user->id)
                    ->whereIn('property_id', $propertyIds)
                    ->delete();

                $message = $count > 0 ? "{$count} propriétés retirées des favoris" : "Aucune propriété n'a été retirée";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if properties are favorited
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function check(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié'
                ], 401);
            }

            $request->validate([
                'property_ids' => 'required|array',
                'property_ids.*' => 'exists:properties,id'
            ]);

            $propertyIds = $request->property_ids;

            $favoritedIds = Favorite::where('user_id', $user->id)
                ->whereIn('property_id', $propertyIds)
                ->pluck('property_id')
                ->toArray();

            return response()->json([
                'success' => true,
                'favorited_ids' => $favoritedIds
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
