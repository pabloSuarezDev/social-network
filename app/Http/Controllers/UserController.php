<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $users = User::orderBy('id', 'desc')->paginate(5);

    return view('user.index', ['users' => $users]);
  }

  public function search(Request $request)
  {
    $nick = $request->input('nick');

    
    if(!empty($nick)) {
      $users = User::where('nick', 'LIKE', '%'.$nick.'%')->orderBy('id', 'desc')->paginate(5);

      return view('user.index', [
        'users' => $users,
        'nick' => $nick
      ]);
    } else { 
      return redirect()->route('people')->with(['no_parameter' => 'Introduzca un Nick para la busqueda']);
    }
  }

  public function config()
  {
    return view('user.config');
  }

  public function update(Request $request)
  {
    //? Con Rule::unique('users','nick')->ignore($id, 'id') creamos una exepcion -> 
    //? porque si en nick coincide con el que ya tenia asignado ese id se lo pueda 
    //? volver a asignar, es decir dejarlo como estaba

    //? Conseguir el usuario identificado
    $user = Auth::user();
    $id = $user->id;

    //? Validar los inputs del formulario de actualizacion de datos de usuario
    $validate = $this->validate($request, [
      'name' => ['required', 'string', 'max:255'],
      'surname' => ['required', 'string', 'max:255'],
      'nick' => ['required', 'string', 'max:255', Rule::unique('users','nick')->ignore($id, 'id')],
      'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id, 'id')],
      'image_path' => ['image', 'max:500']
    ]);

    //? Guardar los datos del formulario
    $name = $request->input('name');
    $surname = $request->input('surname');
    $nick = $request->input('nick');
    $email = $request->input('email');

    //? Asignar los nuevos valores al usuario (objeto user)
    $user->name = $name;
    $user->surname = $surname;
    $user->nick = $nick;
    $user->email = $email;

    //? Subir la foto de perfil
    $image_path = $request->file('image_path');
    // $user->image = $image_path;
    if($image_path) {
      //? Poner nombre unico a la imagen
      $image_path_name = time().$image_path->getClientOriginalName();

      //? Guardar en la carpeta users de la carpeta Storage (storage/app/users), primero pasamos en nombre y luego el archivo
      Storage::disk('users')->put($image_path_name, File::get($image_path));

      //? Setteamos el nombre de la imagen en el objeto
      $user->image = $image_path_name;
    }

    //? Ejecutar consulta para volcar los cambios en la DB
    //! Visual marca error pero el método es así y funciona 
    $user->update();

    return redirect()->route('config')->with (['success_message' => 'Datos acutalizados correctamente']);
  }

  public function getAvatar($filename)
  {
    $file = Storage::disk('users')->get($filename);

    return new Response($file, 200);
  }

  public function profile($id)
  {
    $user = User::find($id);

    $images = Image::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(5);

    return view('user.profile', [
      'user' => $user,
      'images' => $images
    ]);
  }
}