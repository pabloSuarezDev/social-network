@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Publicar fotos</div>
        <div class="card-body">
          <form  method="POST" action="{{ route('image.save') }}" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
              <label for="image_path" class="col-md-4 col-form-label text-md-end">{{ __('Foto') }}</label>
              <div class="col-md-6">
                <input id="image_path" type="file" class="form-control @error('image_path') is-invalid @enderror" name="image_path"
                   autocomplete="image_path" autofocus>
                @error('image_path')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6 offset-md-4">
                <textarea id="description" name="description" cols="15" rows="3" class="form-control @error('description') is-invalid @enderror"
                  placeholder="Descripción de la foto..." autocomplete="description" autofocus></textarea>
              </div>
            </div>
            <div class="row mb-0">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                  {{ __('Publicar foto') }}
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection