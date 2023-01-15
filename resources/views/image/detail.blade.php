@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center mb-3">
    <div class="col-md-6">
      @include('includes.uploaded_comment')
      @include('includes.comment_delete')
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <div class="left">
            @if($image->user->image)
            <img class="img-fluid me-1" src="{{ route('user.avatar', $image->user->image) }}" alt="Avatar de usuario"
              style="width: 35px; border-radius: 2rem; user-select: none;" />
            @endif
            <a class="text-decoration-none text-dark" href="#">{{ $image->user->name}}</a> |
            <span class="text-secondary">&commat;{{ $image->user->nick }}</span>
          </div>
          @if (Auth::check() && Auth::user()->id == $image->user_id)
          <div class="right">
            <a class="me-3" href="{{ route('image.edit', $image->id) }}">
              <img src="{{ asset('img/edit.png') }}" alt="Editar" style="width: 20px;" />
            </a>
            <a href="{{ route('image.delete', $image->id) }}">
              <img src="{{ asset('img/trashcan.png') }}" alt="Borrar" style="width: 20px;" />
            </a>
          </div>
          @endif
        </div>
        <img class="img-fluid" src="{{ route('image.file', $image->image_path) }}" alt="{{ $image->description }}" />
        <div class="card-body p-3">
          <div class="card-description">
            <span class="text-secondary">&commat;{{ $image->user->nick }}</span>
          </div>
          <hr class="border-top border-secondary" />
          <div class="card-interact">
            {{-- Comprobar si el usuario le dio like a la imagen --}}
            <?php $user_like = false ?>
            @foreach ($image->likes as $like)
            @if ($like->user->id == Auth::user()->id)
            <?php $user_like = true ?>
            @endif
            @endforeach

            @if ($user_like)
            <img class="img-fluid me-1 dislike" src="{{ asset('img/red-heart.png') }}" data-id="{{ $image->id }}"
              alt="like" style="width: 25px;" />
            @else
            <img class="img-fluid me-1 like" src="{{ asset('img/gray-heart.png') }}" data-id="{{ $image->id }}"
              alt="dislike" style="width: 25px;" />
            @endif
            <span class="text-secondary">{{ $image->likes->count() }}</span>
            <p class="card-title mt-1">
              <span class="text-secondary">&commat;{{ $image->user->nick }}</span>: {{ $image->description }}
            </p>
            <p>
              <span class="text-secondary" style="font-size: .75rem;">Likes {{ $image->likes->count() }}</span>
              <br />
              <span class="text-secondary" style="font-size: .75rem;">Comentarios: {{ count($image->comments)}}</span>
              <br />
              <span class="text-secondary" style="font-size: .75rem;">
                Publicado {{$image->created_at->locale('es_ES')->diffForHumans() }}
              </span>
            </p>
          </div>
          <form method="POST" action="{{ route('comment.save') }}">
            @csrf
            <input type="hidden" name="image_id" value="{{ $image->id }}" />
            <p class="d-flex align-items-center gap-1">
              <input class="form-control @error('content') is-invalid @enderror" type="text" name="content"
                placeholder="Añadir comentario..." required />
              {{-- Submit button as image --}}
              <input type="image" src="{{ asset('img/telegram.png') }}" style="width: 20px; height: 20px;" />
            </p>
            <p>
              @error('content')
              <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </p>
          </form>
          <div class="card-comments">
            <hr class="border-top border-secondary" />
            @foreach ($image->comments as $comment)
            <p class="card-description">
              <span class="text-secondary">&commat;{{ $comment->user->nick }}:</span> {{ $comment->content }}
              @if(Auth::check() && ($comment->user_id == Auth::user()->id || $image->user_id == Auth::user()->id))
              <a class="px-1" href="{{ route('comment.delete', [$comment->id, $image->user_id]) }}">
                <img class="img-fluid" src="{{ asset('img/trashcan.png') }}" alt="delete" style="width: 20px;" />
              </a>
              @endif
              <br />
              <span class="text-secondary">{{ $comment->created_at->locale('es_ES')->diffForHumans() }}</span>
            </p>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection