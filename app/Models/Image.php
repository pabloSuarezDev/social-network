<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
  use HasFactory;

  //? Nombre de la tabla que va a modificar este modelo
  protected $table = 'images';

  //? Relación de uno a muchos -> porque una imagen puede tener muchos comentarios
  public function comments()
  {
    //* Cuantos comentarios tiene la imagen -> App\Comment en nuestro modelo de comentarios
    return $this->hasMany(Comment::class)->orderby('id', 'desc');
  }

  //? Relación de uno a muchos -> porque una imagen puede tener muchos likes
  public function likes()
  {
    //* Cuantos likes tiene la imagen -> App\Like en nuestro modelo de likes
    //! Devolverá un array con los registros de la base de datos cuyo image_id (table likes) corresponda con el id de la table images
    return $this->hasMany(Like::class);
  }

  //? Relación de muchos a uno -> porque muchas imagenes solo pueden tener un creador/usuario
  public function user()
  {
    //* Retorna a quien le pertenecen dichas imagenes
    return $this->belongsTo(User::class, 'user_id');
  }
}
