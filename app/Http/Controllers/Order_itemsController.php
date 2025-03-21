<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class Order_itemsController extends Controller
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Clé étrangère vers orders
            $table->foreignId('burger_id')->constrained()->onDelete('cascade'); // Clé étrangère vers burgers
            $table->integer('quantity'); // Quantité de burgers
            $table->decimal('price', 8, 2); // Prix unitaire du burger
            $table->timestamps(); // Pour les timestamps created_at et updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
