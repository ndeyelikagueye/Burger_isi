<?php

use App\Http\Controllers\BurgersController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ProfileController;
use App\Models\Orders;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\JustTesting;



// Rediriger la page d'accueil vers la page de connexion
Route::get('/', function () {
    // Vérifier si l'utilisateur est connecté
    if (Auth::check()) {
        // Rediriger vers l'index des burgers selon le rôle
        if (Auth::user()->role === 'client') {
            return redirect()->route('burgers.index');
        } elseif (Auth::user()->role === 'gestionnaire') {
            return redirect()->route('burgers.index');
        }
    }

    // Si non connecté, rediriger vers la page de login
    return redirect()->route('burgers.index');
});

// Routes différenciées selon l'authentification
// La route /burgers peut afficher du contenu différent selon le statut
Route::get('/burgers', [BurgersController::class, 'index'])->name('burgers.index');
Route::get('/burgers/{id}', [BurgersController::class, 'show'])->name('burgers.show');

// Routes d'authentification
require __DIR__.'/auth.php';

// Middleware pour tous les utilisateurs authentifiés
Route::group(['middleware' => ['auth']], function () {
    // Dashboard avec redirection basée sur le rôle

    Route::get('/dashboard', function () {
        if (Auth::check() && Auth::user()->role === 'gestionnaire') {
            return redirect()->route('burgers.store');
        } else {
            return redirect()->route('burgers.index');
        }
    })->name('dashboard');

    // Routes du profil pour tous les utilisateurs connectés
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes pour les clients uniquement
    Route::group(['middleware' => ['role:client']], function () {
        // Routes du panier
        Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
        Route::get('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
        Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
        Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
        Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

        // Routes des commandes client
        Route::get('/orders/my_orders', [OrdersController::class, 'myOrders'])->name('orders.my_orders');
        Route::get('/orders/show/{order}', [OrdersController::class, 'show'])->name('orders.show');
        Route::post('/orders/store', [OrdersController::class, 'store'])->name('orders.store');
        Route::post('/orders/process-cart', [OrdersController::class, 'processCart'])->name('orders.processCart');
        Route::get('/orders/confirm/{order}', [OrdersController::class, 'confirm'])->name('orders.confirm');
        Route::post('/orders/validate/{order}', [OrdersController::class, 'validateOrder'])->name('orders.validate');
        Route::get('/orders/{order}/invoice', [OrdersController::class, 'generateInvoicePDF'])->name('orders.invoice.download');
// Dans routes/web.php
        Route::get('/orders/{id}/pdf', 'OrderController@downloadPdf')->name('orders.pdf');
        // Routes de paiement
        Route::post('/pay/cash', [PaymentsController::class, 'processCashPayment'])->name('pay.cash');
    });

    // Routes pour les gestionnaires uniquement
    Route::group(['middleware' => ['role:gestionnaire']], function () {
        // Gestion des burgers
        Route::get('/admin/burgers/create', [BurgersController::class, 'create'])->name('burgers.create');
        Route::post('/burgers', [BurgersController::class, 'store'])->name('burgers.store');
        Route::get('/burgers/{id}/edit', [BurgersController::class, 'edit'])->name('burgers.edit');
        Route::put('/burgers/{id}', [BurgersController::class, 'update'])->name('burgers.update');
        Route::delete('/burgers/{id}', [BurgersController::class, 'destroy'])->name('burgers.destroy');

        // Gestion des commandes
        Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
        Route::patch('/orders/{order}/update-status', [OrdersController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::delete('/orders/{order}', [OrdersController::class, 'destroy'])->name('orders.destroy');
    });
});

// Sécuriser les routes de test en les plaçant derrière un middleware personnalisé
// NOTE: Créez le middleware pour vérifier l'environnement local
Route::group(['middleware' => ['auth', 'role:gestionnaire']], function () {
    // Routes de test pour les emails (à protéger en production)
    Route::get('/send-mail', function () {
        if (app()->environment('local')) {
            Mail::to('newuser@example.com')->send(new JustTesting());
            return 'Un message a été envoyé à Mailtrap !';
        }
        abort(403, 'Cette fonctionnalité est désactivée en production.');
    });

    Route::get('/test-order-email', function () {
        if (app()->environment('local')) {
            $order = Orders::latest()->first();
            Mail::to($order->user->email)->send(new \App\Mail\OrderStatusUpdated($order));
            return 'Email envoyé !';
        }
        abort(403, 'Cette fonctionnalité est désactivée en production.');
    });

    Route::get('/test-email', function() {
        if (app()->environment('local')) {
            try {
                $user = Auth::user();
                $order = Orders::latest()->first();

                $cart = $order->items->map(function($item) {
                    return [
                        'id' => $item->burger_id,
                        'name' => $item->burger->name,
                        'price' => $item->price,
                        'quantity' => $item->quantity
                    ];
                })->toArray();

                Mail::to($user->email)->send(new \App\Mail\CartOrderConfirmation($order, $cart, $order->total_amount));
                return 'Email envoyé avec succès !';
            } catch (\Exception $e) {
                return 'Erreur : ' . $e->getMessage() . '<br>Trace : ' . $e->getTraceAsString();
            }
        }
        abort(403, 'Cette fonctionnalité est désactivée en production.');
    });

    Route::get('/debug-email', function() {
        if (app()->environment('local')) {
            try {
                $user = Auth::user();
                $order = Orders::latest()->first();

                $cart = $order->items->map(function($item) {
                    return [
                        'id' => $item->burger_id,
                        'name' => $item->burger->name,
                        'price' => $item->price,
                        'quantity' => $item->quantity
                    ];
                })->toArray();

                $result = Mail::to($user->email)->send(new \App\Mail\CartOrderConfirmation($order, $cart, $order->total_amount));

                return response()->json([
                    'status' => 'success',
                    'email' => $user->email,
                    'result' => $result
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
        abort(403, 'Cette fonctionnalité est désactivée en production.');
    });


});
// Dans routes/web.php ajoutez temporairement
Route::get('/test-mail', function() {
    $data = ['message' => 'Ceci est un test'];

    // Utiliser une vue en ligne au lieu d'un fichier
    Mail::html('<h1>Test Email</h1><p>Ceci est un test</p>', function($message) {
        $message->to('votre_email@example.com')->subject('Test Email');
    });

    return 'Email de test envoyé. Vérifiez vos logs et votre boîte mail.';
});
