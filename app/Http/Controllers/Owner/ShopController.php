<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function ($request, $next) {
            // dd($request->route()->parameter('shop')); //文字列
            // dd(Auth::id());
            $id=$request->route()->parameter('shop'); //urlパラメーターからshopのものを取得。
            if(!is_null($id)){ //取得したurlパラメーターが存在するかどうか確認。
                $shopsOwnerId=Shop::findOrFail($id)->owner->id; //urlパラメーターのidを持つshopが紐づくownerのidを取得。
                $shopId=(int)$shopsOwnerId;//
                $ownerId=Auth::id();
                if($shopId!==$ownerId){ // urlパラメーターのidをもつshopが紐づくownerのidと現在ログインしているユーザーのidが一致しているかどうかを確認
                    abort(404);
                }
            }

            return $next($request);
        });
    }

    public function index(){
        $ownerId=Auth::id();
        $shops=Shop::where('owner_id',$ownerId)->get();
        return view('owner.shops.index',compact('shops'));
    }

    public function edit($id){
        dd(Shop::findOrFail($id));

    }

    public function update(Request $request,$id){

    }
}