<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Product;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function products(){
        //今回は中間テーブルの名前についてデフォルト仕様のproduct_userテーブルという名前をつけていないので第二引数で
        //明示的にcartsテーブルという名前をつけてあげる必要がある。
        return $this->belongsToMany(Product::class,'carts')
        //デフォルトでは関連づけるカラムuser_idとproduct_idしか取得しないので以下のwithPivotメソッドにより追加で取得したいカラムを指定。
        ->withPivot(['id','quantity']);
    }

}
