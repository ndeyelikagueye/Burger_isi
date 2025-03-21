<?php

namespace App\Http\Controllers;
use App\Mail\OrderConfirmationMail;
use App\Notifications\OrderConfirmation;
use App\Notifications\OrderStatusUpdate;
use App\Services\OrderPdfGenerator;
use Illuminate\Support\Facades\Log;
use App\Models\Burgers;
use App\Models\Order_items;
use App\Models\Orders;
use App\Models\Payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrdersController extends Controller
{


    /**
     * Affiche toutes les commandes (admin)
     */
    public function index()
    {
        // Récupérer toutes les commandes avec relations
        $orders = Orders::with(['items.burger', 'payment', 'user'])
            ->latest()
            ->paginate(10);
        // Récupérer tous les burgers pour le graphique des stocks
        $burgers = Burgers::all();

        // Statistiques des commandes par jour (30 derniers jours)
        $dailyOrdersLabels = [];
        $dailyOrdersData = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = Orders::whereDate('created_at', $date)->count();

            $dailyOrdersLabels[] = now()->subDays($i)->format('d/m');
            $dailyOrdersData[] = $count;
        }

        // Statistiques des commandes par mois (12 derniers mois)
        $monthlyOrdersLabels = [];
        $monthlyOrdersData = [];
        $monthsData = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthName = $month->format('M Y');
            $startOfMonth = $month->startOfMonth()->format('Y-m-d');
            $endOfMonth = $month->endOfMonth()->format('Y-m-d');

            $count = Orders::whereDate('created_at', '>=', $startOfMonth)
                ->whereDate('created_at', '<=', $endOfMonth)
                ->count();

            $monthlyOrdersLabels[] = $monthName;
            $monthlyOrdersData[] = $count;
            $monthsData[$monthName] = $count;
        }

        // Calcul des résumés statistiques
        $todayOrdersCount = Orders::whereDate('created_at', now()->format('Y-m-d'))->count();
        $weekOrdersCount = Orders::whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d'), now()->endOfWeek()->format('Y-m-d')])->count();
        $monthOrdersCount = Orders::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $yearOrdersCount = Orders::whereYear('created_at', now()->year)->count();

        // Moyenne quotidienne sur les 30 derniers jours
        $avgDailyOrders = round(array_sum($dailyOrdersData) / 30, 1);

        // Moyenne mensuelle sur l'année
        $avgMonthlyOrders = round(array_sum($monthlyOrdersData) / 12, 1);

        // Mois le plus actif et le moins actif
        $busiestMonth = !empty($monthsData) ? array_keys($monthsData, max($monthsData))[0] : 'N/A';
        $slowestMonth = !empty($monthsData) ? array_keys($monthsData, min(array_filter($monthsData)))[0] : 'N/A';

        return view('orders.index', compact(
            'orders',
            'burgers',
            'dailyOrdersLabels',
            'dailyOrdersData',
            'monthlyOrdersLabels',
            'monthlyOrdersData',
            'todayOrdersCount',
            'weekOrdersCount',
            'monthOrdersCount',
            'yearOrdersCount',
            'avgDailyOrders',
            'avgMonthlyOrders',
            'busiestMonth',
            'slowestMonth'
        ));
    }

    /**
     * Affiche les commandes de l'utilisateur connecté
     */
    public function myOrders()
    {
        // Récupérer l'ID du client connecté
        $userId = Auth::id();

        // Récupérer les commandes de l'utilisateur
        $orders = Orders::with(['items.burger', 'payment'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);

        return view('orders.my_orders', compact('orders'));
    }

    /**
     * Affiche le formulaire de création de commande
     */
    public function create()
    {
        $burgers = Burgers::where('is_archived', false)
            ->where('stock', '>', 0)
            ->get();

        return view('orders.create', compact('burgers'));
    }

    /**
     * Affiche les détails d'une commande
     */
    public function show(Orders $order)
    {
        // Vérification que l'utilisateur est propriétaire de la commande ou admin
        if ($order->user_id !== Auth::id() /* && !Auth::user()->isAdmin() */) {
            return redirect()->route('orders.my_orders')->with('error', 'Vous n\'êtes pas autorisé à voir cette commande');
        }

        // Charger les relations nécessaires
        $order->load(['user', 'items.burger', 'payment']);

        return view('orders.show', compact('order'));
    }

    /**
     * Enregistre une nouvelle commande
     */
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'burger_id' => 'required|integer|exists:burgers,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string', // Gardez la validation
        ]);

        // Récupérer le burger depuis la base de données
        $burger = Burgers::findOrFail($request->burger_id);
        $burgerPrice = $burger->price;

        // Calculer le montant total
        $totalAmount = $burgerPrice * $request->quantity;

        // Créer la commande (sans payment_method)
        $order = Orders::create([
            'user_id' => Auth::id(),
            'status' => Orders::STATUS_EN_ATTENTE,
            'total_amount' => $totalAmount,
            'payment_date' => null,
        ]);

        // Créer l'élément de commande (burger)
        Order_Items::create([
            'order_id' => $order->id,
            'burger_id' => $request->burger_id,
            'quantity' => $request->quantity,
            'price' => $burgerPrice,
        ]);

        // Stockez la méthode de paiement dans la session pour l'utiliser plus tard
        session()->put('payment_method', $request->payment_method);

        // Rediriger vers la page de confirmation de commande
        return redirect()->route('orders.confirm', $order->id)
            ->with('success', 'Commande créée avec succès !');
    }

    /**
     * Valide une commande et enregistre le paiement
     */
    public function validateOrder(Request $request, $orderId)
    {
        // Récupérer la commande
        $order = Orders::findOrFail($orderId);

        // Vérifier que l'utilisateur est propriétaire de la commande
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.my_orders')->with('error', 'Vous n\'êtes pas autorisé à valider cette commande');
        }

        // Mettre à jour le statut de la commande
        $order->status = Orders::STATUS_PAYEE;
        $order->payment_date = now();
        $order->save();

        // Créer un paiement si nécessaire et s'il n'existe pas déjà
        if (!$order->payment) {
            Payments::create([
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'payment_method' => Payments::PAYMENT_METHOD_CASH, // Utilisez TOUJOURS 'especes', pas 'cash'
                'payment_date' => now(),
            ]);
        }

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Commande validée avec succès !');
    }

    /**
     * Traitement de commande à partir du panier
     */
    public function processCart(Request $request)
    {
        $user = Auth::user();

        Log::info('Utilisateur connecté', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role
        ]);

        // Validation des données
        $request->validate([
            'payment_method' => 'required|in:carte,mobile_money,especes',
        ]);

        // Récupérer le panier
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Votre panier est vide.');
        }

        // Calculer le montant total
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // Assurez-vous que la méthode de paiement soit une valeur autorisée
        $paymentMethod = $request->payment_method === 'cash' ? 'especes' : $request->payment_method;

        // Créer la commande
        $order = Orders::create([
            'user_id' => Auth::id(),
            'status' => Orders::STATUS_EN_ATTENTE,
            'total_amount' => $totalAmount,
            'payment_method' => $paymentMethod,
            'payment_date' => null,
        ]);

        // Créer les éléments de commande
        foreach ($cart as $item) {
            Order_items::create([
                'order_id' => $order->id,
                'burger_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Mise à jour du stock
            $burger = Burgers::find($item['id']);
            if ($burger) {
                $burger->stock -= $item['quantity'];
                $burger->save();
            }
        }

        // Logs détaillés
        Log::info('Début du processus de commande', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'payment_method' => $request->payment_method
        ]);

        // Dans votre controller, modifiez la partie d'envoi de notification
        try {
            Log::info('Tentative d\'envoi d\'email', ['email' => $user->email]);
            // Dans votre controller
            $order = Orders::with('items')->find($order->id);
            $user->notify(new OrderConfirmation($order));

            Log::info('Email envoyé avec succès');
        } catch (\Exception $e) {
            Log::error('Échec d\'envoi d\'email', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        // Stockez la méthode de paiement dans la session pour l'utiliser plus tard
        session()->put('payment_method', $paymentMethod);

        // Vider le panier
        session()->forget('cart');

        // Rediriger vers la page de confirmation de commande
        return redirect()->route('orders.confirm', $order->id)
            ->with('success', 'Votre commande a été enregistrée avec succès !');
    }

    /**
     * Affiche la page de confirmation de commande
     */
    public function confirm($orderId)
    {
        $order = Orders::with(['items.burger', 'user'])->findOrFail($orderId);

        // Vérifier que l'utilisateur est propriétaire de la commande
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.my_orders')->with('error', 'Vous n\'êtes pas autorisé à voir cette commande');
        }

        // Débogage complet
        Log::channel('stderr')->info('Informations de débogage', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'user_name' => Auth::user()->name,
            'order_id' => $order->id
        ]);

        return view('orders.confirm', compact('order'));
    }

    /**
     * Mise à jour du statut d'une commande
     */
    public function updateStatus(Request $request, $orderId)
    {
        // Validation du statut
        $request->validate([
            'status' => 'required|in:en_attente,en_preparation,prete,payee',
        ]);

        // Récupérer la commande
        $order = Orders::findOrFail($orderId);

        // Sauvegarder l'ancien statut pour vérifier s'il a changé
        $oldStatus = $order->status;

        // Vérifier si le statut a vraiment changé
        if ($oldStatus !== $request->status) {
            // Mettre à jour le statut de la commande
            $order->status = $request->status;

            // Si le statut est "payée", mettre à jour la date de paiement
            if ($request->status === Orders::STATUS_PAYEE && !$order->payment_date) {
                $order->payment_date = now();

                // Créer un paiement si nécessaire et s'il n'existe pas déjà
                if (!$order->payment) {
                    // Utilisez TOUJOURS 'especes' comme méthode de paiement, pas 'cash'
                    $paymentMethod = $request->input('payment_method', 'especes');

                    Payments::create([
                        'order_id' => $order->id,
                        'amount' => $order->total_amount,
                        'payment_method' => Payments::PAYMENT_METHOD_CASH, // Utilisez la variable locale
                        'payment_date' => now(),
                    ]);
                }
            }

            $order->save();

            // Récupérer l'utilisateur associé à la commande
            $user = $order->user;

            try {
                // Envoyer une notification par email
                if ($user) {
                    $user->notify(new OrderStatusUpdate($order, $oldStatus));
                    Log::info('Notification de changement de statut envoyée', [
                        'order_id' => $order->id,
                        'old_status' => $oldStatus,
                        'new_status' => $order->status,
                        'user_email' => $user->email
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de la notification de changement de statut', [
                    'message' => $e->getMessage(),
                    'order_id' => $order->id
                ]);
            }

            return redirect()->back()->with('success', 'Le statut de la commande a été mis à jour avec succès.');
        }

        return redirect()->back()->with('info', 'Aucun changement de statut détecté.');
    }

    public function downloadPdf($id, OrderPdfGenerator $pdfGenerator)
    {
        $order = Orders::with(['items.burger', 'user'])->findOrFail($id);

        // Vérifier que l'utilisateur actuel a le droit de voir cette commande
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'gestionnaire' && Auth::user()->id !== $order->user_id) {
            return redirect()->route('home')->with('error', 'Vous n\'êtes pas autorisé à accéder à cette commande.');
        }

        try {
            return $pdfGenerator->generatePdf($order);
        } catch (\Exception $e) {
            return back()->with('error', 'Impossible de générer le PDF. Veuillez réessayer plus tard.');
        }
    }
    /**
     * Supprime une commande
     */
    public function destroy($orderId)
    {
        // Vérification du rôle admin (à implémenter selon votre logique)
        // if (!Auth::user()->isAdmin()) {
        //     return redirect()->route('orders.my_orders')->with('error', 'Accès non autorisé');
        // }

        // Récupérer la commande
        $order = Orders::with('items')->findOrFail($orderId);

        // Restaurer le stock pour chaque item de la commande
        foreach ($order->items as $item) {
            $burger = Burgers::find($item->burger_id);
            if ($burger) {
                $burger->stock += $item->quantity;
                $burger->save();
            }
        }

        // Supprimer la commande et ses relations (items, payment) grâce aux contraintes de clé étrangère
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'La commande a été supprimée avec succès.');
    }
}

