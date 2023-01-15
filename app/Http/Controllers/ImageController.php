<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function create()
  {
    return view('image.create');
  }

  public function save(Request $request)
  {
    $validate = $this->validate($request, [
      'image_path' => ['required', 'image', 'max:500'],
      'description' => ['string']
    ]);

    $image_path = $request->file('image_path');
    $description = $request->input('description');

    //? Asignar valores nuevo objeto
    $user = Auth::user();
    $image = new Image();

    $image->user_id = $user->id;
    $image->description = $description;

    if($image_path) {
      $image_path_name = time().$image_path->getClientOriginalName();
      Storage::disk('images')->put($image_path_name, File::get($image_path));
      $image->image_path = $image_path_name;
    }

    $image->save();

    return redirect()->route('home')->with(['image_uploaded' => 'Se ha publicado de forma exitosa']);
  }

  public function getImage($filename)
  {
    $file = Storage::disk('images')->get($filename);

    return new Response($file, 200);
  }

  public function detail($id)
  {
    $image = Image::find($id);

    return view('image.detail', ['image' => $image]);
  }

  public function edit($id)
  {
    $user = Auth::user();

    $image = Image::find($id);

    if($user && $image && $image->user->id == $user->id) {

      return view('image.edit', ['image' => $image]);
    } else {
      return redirect()->route('home');
    }
  }

  public function update(Request $request)
  {
    $validate = $this->validate($request, [
      'image_id' => ['required', 'integer'],
      // 'image_path' => ['required', 'image', 'max:500'],
      'description' => ['required', 'string', 'max:255']
    ]);

    $image_id = $request->input('image_id');
    $image_path = $request->file('image_path');
    $description = $request->input('description');

    $image = Image::find($image_id);
    $image->description = $description;

    //? Subir imagen
    if($image_path) {
      $image_path_name = time().$image_path->getClientOriginalName();
      Storage::disk('images')->put($image_path_name, File::get($image_path));
      $image->image_path = $image_path_name;
    }

    //? Ejecutar consulta para volcar los cambios en la DB
    $image->update();

    return redirect()->route('image.detail', $image_id)->with(['success_edit' => 'Publicación editada exitosamente']);
  }
 
  public function delete($id)
  {
    $user = Auth::user();

    $image = Image::find($id);
    $comments = Comment::where('image_id', $id)->get();
    $likes = Like::where('image_id', $id)->get();

    //? Comprobar si soy el dueño de la publicación y si la publicación existe
    if($user && $image && $image->user->id == $user->id) {

      //? Eliminar comentarios
      if($comments && count($comments) >= 1) {
        foreach ($comments as $comment) {
          $comment->delete();
        }
      }

      //? Eliminar likes
      if($likes && count($likes) >= 1) {
        foreach ($likes as $like) {
          $like->delete();
        }
      }

      //? Eliminar ficheros de imagen del Storage
      Storage::disk('images')->delete($image->image_path);

      //? Eliminar registro de la imagen  
      $image->delete();

      return redirect()->route('home', $image->user_id)->with(['post_deleted' => 'La publicación ha sido eliminada exitosamente']);
    } else {
      return redirect()->route('home', $image->user_id)->with(['post_no_deleted' => 'No ha sido posible elimniar la publicación']);
    }
  }
}