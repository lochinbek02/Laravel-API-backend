<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;

class MainController extends Controller
{
    //
    public function index($id){
     
        return Users::find($id);
    }
}
