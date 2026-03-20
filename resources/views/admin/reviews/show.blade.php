@extends('layouts.base')

@section('body')
<div class="container py-5 mt-4">
    {{-- Breadcrumb Navigation --}}
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('reviews.index') }}" class="btn btn-sm btn-outline-secondary border-0 text-muted ps-0">
                <i class="fas fa-arrow-left me-1"></i> Back to Review Management
            </a>
            <h2 class="fw-bold mb-0 text-dark mt-2">
                <i class="fas fa-star me-2 text-pink"></i>Review Details
            </h2>
        </div>
    </div>

    <div class="row g-4">
        {{-- Main Review Card --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 overflow-hidden h-100">
                <div style="height: 4px; background-color: #ec4899;"></div>
                
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h4 class="fw-bold text-dark mb-1">{{ $review->product->name }}</h4>
                            <span class="badge bg-light text-pink border border-pink-soft fw-normal px-3 py-2">
                                <i class="fas fa-palette me-1"></i> Shade: {{ $review->shade->shade_name ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="bg-light rounded-pill px-3 py-2 border">
                            <span class="text-warning small me-2">
                                @for($i=1; $i<=5; $i++)
                                    <i class="{{ $i <= $review->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                                @endfor
                            </span>
                            <span class="fw-bold text-dark">{{ $review->rating }}.0</span>
                        </div>
                    </div>

                    <div class="bg-light p-4 rounded-3 mb-4 position-relative">
                        <i class="fa-solid fa-quote-left text-pink-soft position-absolute" style="top: 10px; left: 10px; opacity: 0.3; font-size: 2rem;"></i>
                        <p class="text-dark fs-5 mb-0 px-3" style="line-height: 1.6; font-style: italic;">
                            "{{ $review->comment }}"
                        </p>
                    </div>

                    @if($review->photo_path)
                        <div class="mt-4">
                            <h6 class="text-muted small text-uppercase fw-bold mb-3">
                                <i class="fa-solid fa-camera me-1 text-pink"></i> Customer Upload
                            </h6>
                            <div class="text-center bg-dark rounded-3 overflow-hidden shadow-sm">
                                <img src="{{ asset('storage/' . $review->photo_path) }}" 
                                     class="img-fluid" 
                                     style="max-height: 500px; width: auto; object-fit: contain;"
                                     alt="Review Image">
                            </div>
                        </div>
                    @else
                        <div class="p-5 bg-light rounded-3 text-center border-dashed border-pink-soft">
                            <i class="fas fa-image fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No customer photo was provided for this review.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar Info --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-dark">Metadata</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="mb-4">
                        <label class="text-muted small text-uppercase fw-bold mb-3 d-block">Customer</label>
                        <div class="d-flex align-items-center p-3 rounded-3 bg-light border border-pink-soft shadow-sm-hover transition-all">
                            {{-- Image Logic --}}
                            @if($review->user->image_path)
                                <img src="{{ asset('storage/' . $review->user->image_path) }}" 
                                     class="rounded-circle me-3 border border-white shadow-sm" 
                                     style="width: 50px; height: 50px; object-fit: cover; min-width: 50px;">
                            @else
                                <div class="bg-pink text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3 shadow-sm" style="width: 50px; height: 50px; min-width: 50px;">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="overflow-hidden">
                                {{-- Clickable Name --}}
                                <a href="{{ route('admin.users.show', $review->user->id) }}" class="fw-bold text-dark text-truncate d-block text-decoration-none hover-pink">
                                    {{ $review->user->name }} <i class="fas fa-external-link-alt ms-1" style="font-size: 0.7rem;"></i>
                                </a>
                                <div class="text-muted small text-truncate">{{ $review->user->email }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small text-uppercase fw-bold mb-2 d-block">Timeline</label>
                        <div class="ps-2">
                            <div class="small text-dark mb-1">
                                <i class="fa-regular fa-calendar-check me-2 text-pink"></i>
                                <strong>Date:</strong> {{ $review->created_at->format('M d, Y') }}
                            </div>
                            <div class="small text-dark">
                                <i class="fa-regular fa-clock me-2 text-pink"></i>
                                <strong>Time:</strong> {{ $review->created_at->format('h:i A') }}
                            </div>
                        </div>
                    </div>

                    <hr class="text-muted opacity-25 my-4">

                    {{-- Actions --}}
                    <div class="d-grid gap-2">
                        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Delete this customer review permanently?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100 py-2 shadow-sm">
                                <i class="fas fa-trash-alt me-2"></i>Delete Review
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-pink-soft { border-color: #fce7f3 !important; }
    .text-pink-soft { color: #f9a8d4; }
    .border-dashed { border: 2px dashed #dee2e6; }
    .hover-pink:hover { color: #ec4899 !important; transition: color 0.2s ease-in-out; }
    .transition-all { transition: all 0.3s ease; }
</style>
@endsection