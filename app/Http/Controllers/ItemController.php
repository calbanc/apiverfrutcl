<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    //

    public function index(){

        $item=Item::all();

        $data=array(
            'status' =>'ok',
            'code'=>200,
            'item'=>$item
        );

        return response()->json($data,$data['code']);
    }

  



}
