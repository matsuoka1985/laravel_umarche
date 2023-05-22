<?php

namespace App\Services;
use App\Models\Product;
use App\Models\Cart;

class CartService{
    public static function getItemsInCart($items){
        $products=[];

        // dd($items); [cartに入っている商品のid,cartの持ち主のid、その商品の数量]
        foreach ($items as $item) {
            $p=Product::findOrFail($item->product_id);  //　商品idからProductのレコード復元
            $owner=$p->shop->owner->select('name','email')->first()->toArray(); //Productレコードからのリレーションによってownerインスタンスを取得し、そのnameカラムとemailカラムを取得。それが['名前’,'メアド']が入った配列となる。
            $values=array_values($owner);
            $keys=['ownerName','email'];
            $ownerInfo=array_combine($keys,$values);
            // dd($ownerInfo);
            $product=Product::where('id',$item->product_id)->select('id','name','price')->get()->toArray();
            // dd($product);
            $quantity=Cart::where('product_id',$item->product_id)->select('quantity')->get()->toArray();
            // dd($quantity);
            $result=array_merge($product[0],$ownerInfo,$quantity[0]);
            // dd($result);
            array_push($products,$result);
        }
        dd($products);
        return $products;
    }
}
