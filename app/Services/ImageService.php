<?php

namespace App\Services;
use Illuminate\Support\Facades\Storage;
use InterventionImage;

class ImageService{
    /**
     * 画像ファイルそのものをstorage/app/public/{任意のディレクトリ}に保存し、その画像のファイル名を保存パスも含め戻り値として返す関数。
     */
    public static function upload($imageFile,$folderName){
        $fileName = (string)date('Y-m-d H:i:s') . uniqid(rand() . '_'); //ファイル名を生成。
        $extension = $imageFile->extension(); //アップロードされたファイルから拡張子を取得。
        $fileNameToStore = $fileName . '.' . $extension; // 生成したファイル名に拡張子を追加。
        $resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080)->encode(); //アップロードされたファイルをリサイズ。
        Storage::put(
            'public/' . $folderName . '/'  . $fileNameToStore,
             $resizedImage); //　(パスも含め)第一引数のファイル名によって、第二引数のファイルを保存。


        return $fileNameToStore;
    }
}