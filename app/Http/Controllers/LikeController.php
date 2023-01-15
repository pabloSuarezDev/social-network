<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $user = Auth::user();

    $likes = Like::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(5);

    return view('like.index', ['likes' => $likes]);
  }

  public function like($image_id)
  {
    $user = Auth::user();

    //? Condición para comprobar si ya existe el like, para no duplicarlo
    $isset_like = Like::where('user_id', $user->id)
                        ->where('image_id', $image_id)
                        ->count();

    if($isset_like == 0) {
      $like = new Like();
      $like->user_id = $user->id;
      $like->image_id = (int)$image_id;
      
      //? Guardar
      $like->save();

      return response()->json(['like' => $like]);
    } else {
      return response()->json(['like_exist' => 'Ya le has dado like']);
    }
  }

  public function dislike($image_id)
  {
    $user = Auth::user();

    //? Condición para comprobar si ya existe el like, para no duplicarlo
    $dislike = Like::where('user_id', $user->id)
                        ->where('image_id', $image_id)
                        ->first();

    if($dislike) {
      //? Eliminar like
      $dislike->delete();
      return response()->json([
        'dislike' => $dislike,
        'like_deleted' => 'Has dado dislike'
      ]);
    } else {
      return response()->json(['like_does_not_exist' => 'No has dado like previamente']);
    }
  }
}
