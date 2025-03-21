<?php

namespace App\Http\Controllers;

use App\Models\Burgers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Constructeur
     */

    /**
     * Ajoute un produit au panier
     */
    public function add(Request $request, $id)
    {
        $burger = Burgers::findOrFail($id);

        // Vérifier si le burger est en stock
        if ($burger->stock <= 0) {
            return back()->with('error', 'Ce burger n\'est plus en stock.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            // Vérifier si le stock est suffisant pour ajouter un autre burger
            if ($cart[$id]['quantity'] + 1 > $burger->stock) {
                return back()->with('error', 'Stock insuffisant pour ajouter plus de ce burger.');
            }
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'id' => $burger->id,
                'name' => $burger->name,
                'quantity' => 1,
                'price' => $burger->price,
                'image' => $burger->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Burger ajouté au panier !');
    }

    /**
     * Affiche le panier
     */
    public function view()
    {
        return view('cart.index');
    }

    /**
     * Supprime un produit du panier
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produit retiré du panier.');
    }

    /**
     * Met à jour la quantité d'un produit dans le panier
     */
    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');
            $burger = Burgers::findOrFail($request->id);

            // Vérifier si le stock est suffisant
            if ($request->quantity > $burger->stock) {
                return back()->with('error', 'Stock insuffisant. Quantité ajustée au maximum disponible.');
            }

            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Panier mis à jour.');
    }

    /**
     * Vide le panier
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Panier vidé avec succès.');
    }

    /**
     * Traite le checkout du panier
     */
    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour passer une commande');
        }
        return view('cart.checkout');
    }
}
