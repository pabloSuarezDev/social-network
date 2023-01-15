@if(Auth::user()->image)
  <img class="img-fluid me-1" src="{{ route('user.avatar', Auth::user()->image) }}" alt="Avatar de usuario" style="width: 35px; border-radius: 2rem; user-select: none;" />
@endif