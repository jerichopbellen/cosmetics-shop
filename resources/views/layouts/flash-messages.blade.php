@if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <strong>{{ $message }}</strong>

    </div>
@endif


@if ($message = Session::get('error'))
    <div class="alert alert-danger alert-dismissible alert-block">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <strong>{{ $message }}</strong>
    </div>
@endif


@if ($message = Session::get('warning'))
    <div class="alert alert-warning alert-block">   
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>        
    <strong>{{ $message }}</strong>
    </div>
@endif


@if ($message = Session::get('info'))
    <div class="alert alert-info alert-block">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <strong>{{ $message }}</strong>
    </div>
@endif
@if ($errors->any())

    <div class="alert  alert-block alert-danger">
        <button type="button" class="close" data-bs-dismiss="alert"></button>
        @foreach ($errors->all() as $message)
            {{ $message }}
        @endforeach
    </div>
@endif

@if (session('verification_link'))
    <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
        <i class="fas fa-envelope-open-text me-2"></i> 
        {!! session('verification_link') !!}
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- {{dd($errors)}} --}}