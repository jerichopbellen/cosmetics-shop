@extends('layouts.base')

@section('body')
<div class="container py-5 mt-4">
    <div class="row">
        {{-- Left Column: Product & Shade Info --}}
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm text-center p-4 h-100">
                <div class="mb-3 mx-auto">
                    <img src="{{ asset('storage/' . ($product->images->first()->image_path ?? 'placeholders/product.png')) }}" 
                         class="rounded border border-pink p-1 shadow-sm" 
                         style="width: 100%; max-width: 200px; aspect-ratio: 1/1; object-fit: cover;">
                </div>
                
                <h4 class="fw-bold mb-1 text-dark">{{ $product->name }}</h4>
                <p class="text-muted small text-uppercase fw-bold mb-2" style="letter-spacing: 1px;">
                    {{ $product->category->name ?? 'Cosmetics' }}
                </p>

                {{-- Displaying the specific Shade --}}
                <div class="mb-4">
                    <span class="badge bg-light text-pink border border-pink-subtle px-3 py-2 rounded-pill shadow-sm">
                        <i class="fa-solid fa-palette me-1"></i> Shade: {{ $shade->shade_name }}
                    </span>
                </div>

                <hr class="opacity-50">

                <div class="text-start mt-3">
                    <h6 class="fw-bold small text-muted text-uppercase mb-2" style="font-size: 0.7rem;">Review Guidelines</h6>
                    <ul class="text-muted ps-3 mb-0" style="font-size: 0.85rem;">
                        <li>Be specific about the texture and scent.</li>
                        <li>Mention how long the product lasted.</li>
                        <li>Upload a photo to show the real-life results!</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Right Column: Create Form --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div style="height: 5px; background-color: #ec4899;"></div>
                <div class="card-body p-4 p-md-5">
                    <h3 class="fw-bold mb-4 text-dark">Write a Review</h3>

                    <form action="{{ route('reviews.store', $product->id) }}" method="POST" enctype="multipart/form-data">                        @csrf
                        
                        {{-- Rating Section --}}
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Overall Rating</label>
                            <div class="d-flex justify-content-center py-2">
                                <div class="rating-stars d-flex flex-row-reverse fs-2">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" class="btn-check" 
                                            {{ old('rating') == $i ? 'checked' : '' }} required>
                                        <label for="star{{ $i }}" class="px-1 text-muted cursor-pointer star-label">
                                            <i class="fa-solid fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            @error('rating') <small class="text-danger d-block mt-1 text-center">{{ $message }}</small> @enderror
                        </div>

                        {{-- Comment Section --}}
                        <div class="mb-4">
                            <label for="comment" class="form-label small fw-bold text-muted text-uppercase">Your Feedback</label>
                            <textarea name="comment" id="comment" rows="5" 
                                class="form-control border-pink-focus shadow-none py-2 @error('comment') is-invalid @enderror" 
                                placeholder="What did you love about this product?" required>{{ old('comment') }}</textarea>
                            @error('comment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Image Upload Zone --}}
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Review Photo (Optional)</label>
                            
                            <input type="file" name="image" id="photo-input" class="d-none" accept="image/*" onchange="previewReviewImage(this)">
                            
                            <div class="upload-zone rounded text-center cursor-pointer border-pink-subtle" id="upload-trigger" onclick="document.getElementById('photo-input').click()">
                                
                                {{-- Default State --}}
                                <div id="upload-prompt" class="p-4">
                                    <i class="fa-solid fa-cloud-arrow-up fa-2x text-pink mb-2"></i>
                                    <h6 class="fw-bold text-dark small mb-0">Click to Upload Photo</h6>
                                    <p class="text-muted mb-0" style="font-size: 0.7rem;">JPG or PNG (Max 2MB)</p>
                                </div>
                                
                                {{-- Preview State --}}
                                <div id="preview-wrapper" class="p-2 d-none">
                                    <div class="position-relative d-inline-block">
                                        <img id="review-preview" src="#" 
                                             class="rounded border border-pink p-1 shadow-sm" 
                                             style="max-height: 250px; width: auto; object-fit: contain;">
                                        
                                        <button type="button" onclick="event.stopPropagation(); removeSelectedPhoto();" 
                                                class="position-absolute top-0 end-0 bg-danger text-white rounded-circle border-0 shadow-sm d-flex align-items-center justify-content-center" 
                                                style="width: 28px; height: 28px; transform: translate(50%, -50%);">
                                            <i class="fas fa-times" style="font-size: 0.9rem;"></i>
                                        </button>
                                    </div>
                                    <div class="mt-2 text-pink small fw-bold">Click box to change image</div>
                                </div>
                            </div>
                            @error('image') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="text-end mt-5">
                            {{-- Pass current context to the controller --}}
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="shade_id" value="{{ $shade->id }}">
                            
                            <a href="{{ route('orders.my') }}" class="btn btn-link text-muted text-decoration-none small me-3 fw-bold">Cancel</a>
                            <button type="submit" class="btn btn-pink px-5 py-2 fw-bold shadow-sm">
                                Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewReviewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('review-preview').src = e.target.result;
                document.getElementById('preview-wrapper').classList.remove('d-none');
                document.getElementById('upload-prompt').classList.add('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeSelectedPhoto() {
        const input = document.getElementById('photo-input');
        input.value = ""; 
        document.getElementById('preview-wrapper').classList.add('d-none');
        document.getElementById('upload-prompt').classList.remove('d-none');
        document.getElementById('review-preview').src = "#";
    }
</script>

<style>
    .border-pink { border-color: #ec4899 !important; }
    .text-pink { color: #ec4899 !important; }
    .btn-pink { background-color: #ec4899; color: white; border: none; transition: 0.3s; }
    .btn-pink:hover { background-color: #be185d; color: white; transform: translateY(-1px); }

    .border-pink-focus:focus {
        border-color: #ec4899;
        box-shadow: 0 0 0 0.25rem rgba(236, 72, 153, 0.1);
    }
    .border-pink-subtle { border-color: #fce7f3; }

    .upload-zone {
        background-color: #fffdfd;
        border: 2px dashed #fce7f3;
        transition: 0.2s;
    }
    .upload-zone:hover {
        background-color: #fff9fc;
        border-color: #ec4899;
    }

    .rating-stars input { display: none; }
    .star-label { transition: 0.2s; color: #dee2e6; }
    .star-label:hover, .star-label:hover ~ .star-label, .btn-check:checked ~ .star-label { 
        color: #ffc107 !important; 
    }
    .cursor-pointer { cursor: pointer; }
</style>
@endsection