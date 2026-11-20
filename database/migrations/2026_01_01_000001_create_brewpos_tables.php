<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {public function up():void{
 Schema::create('categories',function(Blueprint $t){$t->id();$t->string('name')->unique();$t->text('description')->nullable();$t->timestamps();});
 Schema::create('products',function(Blueprint $t){$t->id();$t->foreignId('category_id')->nullable()->constrained()->nullOnDelete();$t->string('name');$t->string('sku')->nullable()->unique();$t->text('description')->nullable();$t->unsignedInteger('price');$t->unsignedInteger('stock')->default(0);$t->boolean('is_available')->default(true);$t->timestamps();});
 Schema::create('transactions',function(Blueprint $t){$t->id();$t->string('invoice_number')->unique();$t->foreignId('user_id')->constrained();$t->unsignedBigInteger('subtotal');$t->unsignedBigInteger('total');$t->unsignedBigInteger('paid_amount');$t->unsignedBigInteger('change_amount');$t->string('payment_method')->default('cash');$t->timestamps();});
 Schema::create('transaction_details',function(Blueprint $t){$t->id();$t->foreignId('transaction_id')->constrained()->cascadeOnDelete();$t->foreignId('product_id')->nullable()->constrained()->nullOnDelete();$t->string('product_name');$t->unsignedInteger('price');$t->unsignedInteger('quantity');$t->unsignedBigInteger('subtotal');$t->timestamps();});
}public function down():void{Schema::dropIfExists('transaction_details');Schema::dropIfExists('transactions');Schema::dropIfExists('products');Schema::dropIfExists('categories');}};
