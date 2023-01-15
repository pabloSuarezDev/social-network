@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      @include('includes.uploaded_message')
      @include('includes.post_delete')
      <div class="card">
        <div class="card-header mb-4">{{ __('Publicaciones') }}</div>
        @foreach ($images as $image)
          @include('includes.image', ['image' => $image])
        @endforeach
        <div>{{ $images->links('pagination::bootstrap-4') }}</div>
      </div>
    </div>
  </div>
</div>
@endsection