<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $agents = User::where('role', 'agent')->get();
        $cities = City::with('neighborhoods')->get();

        $properties = [];

        foreach ($properties as $propertyData) {
            $city = $cities->where('name', $propertyData['city'])->first();
            if (!$city) {
                continue; // Skip if city not found
            }
            $neighborhood = $city->neighborhoods->where('name', $propertyData['neighborhood'])->first();
            if (!$neighborhood) {
                continue; // Skip if neighborhood not found
            }

            Property::create([
                'user_id' => $agents->random()->id,
                'title' => $propertyData['title'],
                'slug' => Str::slug($propertyData['title']),
                'type' => $propertyData['type'],
                'status' => $propertyData['status'],
                'price' => $propertyData['price'],
                'currency' => 'XAF',
                'description' => $propertyData['description'],
                'bedrooms' => $propertyData['bedrooms'] ?? null,
                'bathrooms' => $propertyData['bathrooms'] ?? null,
                'surface_area' => $propertyData['surface_area'],
                'address' => 'Adresse à définir',
                'city' => $city->name,
                'neighborhood' => $neighborhood->name,
                'featured' => $propertyData['featured'],
                'published' => $propertyData['published'],
            ]);
        }
    }
}
