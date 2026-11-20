<?php
namespace Database\Seeders;
use App\Models\{User,Category,Product};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder {
 public function run():void {
  User::updateOrCreate(['email'=>'admin@brewpos.test'],['name'=>'Administrator','password'=>Hash::make('password123'),'role'=>'admin']);
  User::updateOrCreate(['email'=>'kasir@brewpos.test'],['name'=>'Kasir Demo','password'=>Hash::make('password123'),'role'=>'cashier']);
  foreach(['Espresso Based','Manual Brew','Non Coffee','Food'] as $name){$c=Category::firstOrCreate(['name'=>$name]);}
  $items=[['Espresso Based','Espresso',18000,100],['Espresso Based','Cappuccino',25000,80],['Espresso Based','Cafe Latte',26000,80],['Espresso Based','Caramel Macchiato',30000,60],['Manual Brew','V60',28000,40],['Manual Brew','Japanese Iced Coffee',30000,40],['Non Coffee','Matcha Latte',28000,50],['Non Coffee','Chocolate',25000,50],['Food','Butter Croissant',22000,30],['Food','Cheese Toast',24000,30]];
  foreach($items as [$cat,$name,$price,$stock]) Product::updateOrCreate(['name'=>$name],['category_id'=>Category::where('name',$cat)->value('id'),'price'=>$price,'stock'=>$stock,'is_available'=>true]);
 }
}
