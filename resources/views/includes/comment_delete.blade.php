@if(session('comment_deleted'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>{{ session('comment_deleted') }}</strong>
  <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@elseif(session('comment_no_deleted'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>{{ session('comment_no_deleted') }}</strong>
  <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif