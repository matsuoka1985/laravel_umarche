<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;
use Illuminate\Support\Facades\DB; //クエリビルダ
use Carbon\Carbon;

class OwnersController extends Controller
{
    public function __contruct(){

        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date_now=Carbon::now();
        $date_parse=Carbon::parse(now());
        echo $date_now->year;
        echo '<br>';
        echo $date_parse;
        $e_all=Owner::all(); // 型はIlluminate\Database\Eloquent\Collection
        $q_get=DB::table('owners')->select('name','created_at')->get(); // 型はIlluminate\Support\Collection
        // $q_first=DB::table('owners')->select('name')->first(); // 型はphpのスタンダードクラス
        // $c_test=collect([
        //     'name'=>'てすと'
        // ]); // 型はIlluminate\Support\Collection
        // dd($e_all,$q_get,$q_first,$c_test);
        return view('admin.owners.index',compact('e_all','q_get'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}