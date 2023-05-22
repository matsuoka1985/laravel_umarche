<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:owners');
        //ルーティングに記述したミドルウェアは、単にownerでログインしているかどうかを判定するだけのためのもの。以下のミドルウェアは、urlパラメーターに渡ってきたidを持つデータが現在ログインしているユーザーと紐付くものかどうかを判定する。もし紐づいていなければそのデータについて閲覧も編集も削除も許されないので弾くようにするのが以下のミドルウェアの記述。
        $this->middleware(function ($request, $next) {

            $id = $request->route()->parameter('image'); // urlパラメーターを取得
            if (!is_null($id)) { //urlパラメーターが取得できたら
                $imagesOwnerId = Image::findOrFail($id)->owner->id; //そのurlパラメーターに対応するデータを取得しさらにそのデータのユーザーidカラムのデータを取得。
                $imageId = (int)$imagesOwnerId;
                if ($imageId !== Auth::id()) { //上で取得したidと現在ログイン中のユーザーのidが一致しているかどうか検証。
                    abort(404);
                }
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Image::where('owner_id', Auth::id())
        ->orderBy('updated_at','desc')
        ->paginate(20);
        return view('owner.images.index', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('owner.images.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadImageRequest $request)
    {
        // dd($request);
        $imageFiles=$request->file('files');
        if(!is_null($imageFiles)){
            foreach ($imageFiles as $imageFile) {
                $fileNameToStore = ImageService::upload($imageFile, 'products');
                Image::create([
                    'owner_id'=>Auth::id(),
                    'filename'=>$fileNameToStore
                ]);
            }
        }
        return redirect()->route('owner.images.index')->with(['message' => '画像登録を実施しました。', 'status' => 'info']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $image=Image::findOrFail($id);
        return view('owner.images.edit',compact('image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'=>'string|max:50'
        ]);

        $image=Image::findOrFail($id);
        $image->title=$request->title;
        $image->save();

        return redirect()
        ->route('owner.images.index')
        ->with(['message'=>'画像情報を更新しました','status'=>'info']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image=Image::findOrFail($id);

        //当該画像と紐づくProduct群を取得。
        $imageInProducts= Product::where('image1',$image->id)
        ->orWhere('image2',$image->id)
        ->orWhere('image3',$image->id)
        ->orWhere('image4',$image->id)
        ->get();

        if($imageInProducts){
            $imageInProducts->each(function($product)use($image) {
                if($product->image1===$image->id){
                    $product->image1=null;
                    $product->save();
                }
                if($product->image2===$image->id){
                    $product->image1=null;
                    $product->save();
                }
                if($product->image3===$image->id){
                    $product->image1=null;
                    $product->save();
                }
                if($product->image4===$image->id){
                    $product->image1=null;
                    $product->save();
                }
            });
        }

        $filePath= 'public/products/' . $image->filename;

        if(Storage::exists($filePath)){
            Storage::delete($filePath);
        }

        Image::findOrFail($id)->delete();

        return redirect()
        ->route('owner.images.index')
        ->with([
            'message' => '画像を削除しました',
            'status' => 'alert'
        ]);
    }
}
