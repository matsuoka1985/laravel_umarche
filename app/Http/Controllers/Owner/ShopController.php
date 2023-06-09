<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use App\Models\Owner;
use Illuminate\Support\Facades\Storage;
use InterventionImage;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;

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
        // phpinfo();
        $ownerId=Auth::id();
        $shops=Shop::where('owner_id',$ownerId)->get();
        return view('owner.shops.index',compact('shops'));
    }

    public function edit($id){
        // dd(Shop::findOrFail($id));
        $shop=Shop::findOrFail($id);
        return view('owner.shops.edit',compact('shop'));
    }

    public function update(UploadImageRequest $request,$id){
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'information' => ['required', 'string',  'max:1000' ],
            'is_selling' => ['required'],
        ]);

        $imageFile=$request->image;
        if(!is_null($imageFile) && $imageFile->isValid() ){
            $fileNameToStore=ImageService::upload($imageFile,'shops');
        }

        $shop=Shop::findOrFail($id);
        $shop->name=$request->name;
        $shop->information=$request->information;
        $shop->is_selling=$request->is_selling;
        if(!is_null($imageFile)&& $imageFile->isValid()){
            $shop->filename=$fileNameToStore;
        }
        $shop->save();


        return redirect()->route('owner.shops.index')->with(['message'=>'店舗情報を更新しました。','status'=>'info']);
    }
}