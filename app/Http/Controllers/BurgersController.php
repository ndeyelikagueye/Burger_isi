<?php

namespace App\Http\Controllers;

use App\Models\Burgers;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BurgersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Burgers::query();

        // Filtrage par nom
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filtrage par prix
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Récupérer les commandes de l'utilisateur connecté
        $orders = Orders::where('user_id', Auth::id())->get();

        // Récupérer les burgers (si nécessaire)
        $burgers = Burgers::where('is_archived', false)->get();

        $burgers = $query->where('is_archived', false)->get(); // Récupérer les burgers non archivés

        // Passer les variables à la vue
        return view('burgers.index', compact('burgers', 'orders'));





    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('burgers.create'); // Retourner la vue pour créer un burger
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
        ]);

        try {
            $burger = new Burgers(); // Assurez-vous que le modèle est au singulier
            $burger->name = $request->name;
            $burger->price = $request->price;

            // Vérifiez si une image a été téléchargée
            if ($request->hasFile('image')) {
                $burger->image = $request->file('image')->store('images', 'public'); // Assurez-vous d'avoir configuré le stockage
            }

            $burger->description = $request->description;
            $burger->stock = $request->stock;
            $burger->save();

            return redirect()->route('burgers.index')->with('success', 'Burger created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create burger: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $burger = Burgers::findOrFail($id); // Trouver le burger par ID
        return view('burgers.show', compact('burger')); // Retourner la vue avec le burger
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $burger = Burgers::findOrFail($id); // Trouver le burger par ID
        return view('burgers.edit', compact('burger')); // Retourner la vue pour éditer le burger
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
        ]);

        $burger = Burgers::findOrFail($id); // Trouver le burger par ID
        $burger->name = $request->name;
        $burger->price = $request->price;

        if ($request->hasFile('image')) {
            $burger->image = $request->file('image')->store('images', 'public'); // Mettre à jour l'image
        }

        $burger->description = $request->description;
        $burger->stock = $request->stock;
        $burger->save();

        return redirect()->route('burgers.index')->with('success', 'Burger updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $burger = Burgers::findOrFail($id); // Trouver le burger par ID
        $burger->delete(); // Supprimer le burger

        return redirect()->route('burgers.index')->with('success', 'Burger deleted successfully.');
    }
}
