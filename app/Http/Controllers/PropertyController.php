<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\City;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::where('published', true)
            ->with(['media', 'cityModel']);

        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Handle sorting
        $sort = request('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'featured':
                $query->orderBy('featured', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $properties = $query->orderBy('featured', 'desc')
            ->paginate(12);

        $cities = City::orderBy('name')->get();

        return view('properties.index', compact('properties', 'cities'));
    }

    public function show(Property $property)
    {
        if (!$property->published) {
            abort(404);
        }

        // Incrémenter les vues
        $property->incrementViews();

        // Propriétés similaires
        $similarProperties = Property::where('published', true)
            ->where('id', '!=', $property->id)
            ->where(function($query) use ($property) {
                $query->where('city', $property->city)
                      ->orWhere('type', $property->type);
            })
            ->with(['media'])
            ->limit(4)
            ->get();

        return view('properties.show', compact('property', 'similarProperties'));
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }
}
