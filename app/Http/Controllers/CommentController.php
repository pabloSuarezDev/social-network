<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function save(Request $request)
  {
    $validate = $this->validate($request, [
      'image_id' => ['required', 'integer'],
      'content' => ['required', 'string']
    ]);

    $user = Auth::user();

    $user_id = $user->id;
    $image_id = $request->input('image_id');
    $content = $request->input('content');

    $comment = new Comment();

    $comment->user_id = $user_id;
    $comment->image_id = $image_id;
    $comment->content = $content;

    $comment->save();

    return redirect()->route('image.detail', $image_id)->with(['comment_uploaded' => 'Comentario publicado exitosamente']);
  }

  public function delete($id, $img_userID)
  {
    //? Conseguir datos del usuario identificado
    $user = Auth::user();
  
    //? Conseguir datos de la publicación
    $image_user_id = Image::find($img_userID);

    //? Conseguir objeto del comentario
    $comment = Comment::find($id);

    //? Comprobar si soy el dueño del comentario o de la publicación
    if($user && ($comment->user_id == $user->id || $image_user_id->id == $user->id)) {
      $comment->delete();

      return redirect()->route('image.detail', $image_user_id->id)->with(['comment_deleted' => 'Comentario eliminado exitosamente']);
    } else {
      return redirect()->route('image.detail', $image_user_id->id)->with(['comment_no_deleted' => 'No ha sido posible elimniar el comentario']);
    }
  }
}