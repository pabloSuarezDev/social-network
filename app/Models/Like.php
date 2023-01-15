<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
  use HasFactory;

  protected $table = 'likes';

  public function user()
  {
    //* Retorna a quien le pertenecen dichos comentarios
    return $this->belongsTo(User::class, 'user_id');
  }

  public function image()
  {
    return $this->belongsTo(Image::class, 'image_id');
  }
}
