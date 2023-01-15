@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">{{ __('Verifcar dirección de correo electrónico') }}</div>
        <div class="card-body">
          @if (session('resent'))
          <div class="alert alert-success" role="alert">
            {{ __('Se ha reenviado un enlace de verificación a su correo electrónico.') }}
          </div>
          @endif
          {{ __('Antes de continuar, compruebe si ha recibido un enlace de verificación en su correo electrónico.') }}
          {{ __('Si no ha recibido el correo electrónico') }},
          <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('Haga clic aquí para solicitar otro')
              }}</button>.
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection