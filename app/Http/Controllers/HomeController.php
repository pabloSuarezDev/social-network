<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  
  public function index()
  {
    //? Con simplePaginate() podemos elegir el numero de elementos a mostrar en cada pagina (cambiamos de pagina asi -> ?page=2)
    //? Tambien existe paginate()

    $images = Image::orderBy('id', 'desc')->paginate(5);

    return view('home', ['images' => $images]);
  }
}