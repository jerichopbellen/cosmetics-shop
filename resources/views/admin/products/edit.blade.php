@extends('layouts.base')

@section('body')
<div class="container py-5">
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Edit Product: <span class="text-pink">{{ $product->name }}</span></h2>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <div class="card shadow-sm border-0 mb-4 overflow-hidden">
            <div style="height: 4px; background-color: #ec4899;"></div>
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-info-circle me-2 text-pink"></i>Product Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted small">Product Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-muted small">Brand</label>
                        <select name="brand_id" class="form-control" required>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-muted small">Category</label>
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-bold text-muted small">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-muted small">Finish</label>
                        <input type="text" name="finish" class="form-control" value="{{ $product->finish }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4 overflow-hidden">
            <div style="height: 4px; background-color: #ec4899;"></div>
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-images me-2 text-pink"></i>Product Gallery</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small">Upload New Photos</label>
                    <input type="file" name="gallery[]" class="form-control" multiple accept="image/*" id="gallery-input">
                    <small class="text-muted">You can select multiple files at once.</small>
                    <div id="gallery-preview" class="d-flex flex-wrap gap-2 mt-3"></div>
                </div>

                <h6 class="fw-bold text-dark mb-3">Current Gallery</h6>
                <div class="d-flex flex-wrap gap-3 p-3 bg-light rounded border justify-content-center">
                    @forelse($product->images as $img)
                        <div class="position-relative text-center border p-2 bg-white rounded shadow-sm">
                            <img src="{{ asset('storage/' . $img->image_path) }}" 
                                class="img-thumbnail mb-2 border-pink" 
                                style="width: 250px; height: 250px; object-fit:cover;">
                            
                            <div class="form-check d-flex justify-content-center">
                                <input class="form-check-input me-2" type="checkbox" name="remove_images[]" value="{{ $img->id }}" id="img_{{ $img->id }}">
                                <label class="form-check-label text-danger small fw-bold" for="img_{{ $img->id }}">
                                    Remove
                                </label>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No gallery images uploaded yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4 overflow-hidden">
            <div style="height: 4px; background-color: #ec4899;"></div>
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">Manage Shades</h5>
                <button type="button" id="add-shade" class="btn btn-pink btn-sm fw-bold">
                    <i class="fas fa-plus me-1"></i> Add New Shade
                </button>
            </div>
            <div class="card-body bg-light" id="shades-wrapper">
                @foreach($product->shades as $index => $shade)
                <div class="row shade-row border p-3 mb-3 bg-white rounded shadow-sm align-items-start">
                    <input type="hidden" name="shades[{{ $index }}][id]" value="{{ $shade->id }}">
                    
                    <div class="col-md-3 mb-2">
                        <label class="small fw-bold text-muted">Shade Name</label>
                        <input type="text" name="shades[{{ $index }}][shade_name]" class="form-control" value="{{ $shade->shade_name }}" required>
                    </div>
                    <div class="col-md-1 mb-2">
                        <label class="small fw-bold text-muted">Color</label>
                        <input type="color" name="shades[{{ $index }}][hex_code]" class="form-control p-1" value="{{ $shade->hex_code }}" style="height: 38px;">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="small fw-bold text-muted">Price</label>
                        <input type="number" step="0.01" name="shades[{{ $index }}][price]" class="form-control" value="{{ $shade->price }}" required>
                    </div>
                    <div class="col-md-1 mb-2">
                        <label class="small fw-bold text-muted">Stock</label>
                        <input type="number" name="shades[{{ $index }}][stock]" class="form-control" value="{{ $shade->stock }}" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="small fw-bold text-muted">Update Photo</label>
                        <div class="d-flex align-items-start gap-2">
                            <input type="file" name="shades[{{ $index }}][image]" class="form-control shade-image-input" accept="image/*">
                            <div class="shade-preview-container">
                                @if($shade->image_path)
                                    <img src="{{ asset('storage/' . $shade->image_path) }}" class="rounded shadow-sm border border-pink" style="height: 100px; width:100px; object-fit:cover;">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 mb-2 text-start">
                        <label class="small fw-bold text-muted">Remove</label>
                        <button type="button" class="btn btn-outline-danger remove-shade w-100"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mb-5 d-flex gap-2">
            <button type="submit" class="btn btn-pink btn-lg px-5 shadow fw-bold">Update Product</button>
            <a href="{{ route('products.index') }}" class="btn btn-light btn-lg px-5 border">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // 1. Gallery Preview
    $('#gallery-input').on('change', function() {
        $('#gallery-preview').empty();
        const files = this.files;
        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#gallery-preview').append(`
                    <div class="position-relative">
                        <img src="${e.target.result}" class="img-thumbnail border-pink" style="width:120px; height:120px; object-fit:cover;">
                    </div>
                `);
            }
            reader.readAsDataURL(files[i]);
        }
    });

    let count = {{ $product->shades->count() }};

    // 3. Add New Shade Row - Styled with Pink
    $('#add-shade').click(function() {
        let html = `
            <div class="row shade-row border p-3 mb-3 bg-white rounded shadow-sm align-items-start border-pink" style="border-width: 2px !important;">
                <input type="hidden" name="shades[${count}][id]" value="0">
                <div class="col-md-3 mb-2">
                    <label class="small fw-bold text-pink">New Shade Name</label>
                    <input type="text" name="shades[${count}][shade_name]" class="form-control border-pink" required>
                </div>
                <div class="col-md-1 mb-2">
                    <label class="small fw-bold text-pink">Color</label>
                    <input type="color" name="shades[${count}][hex_code]" class="form-control p-1 border-pink" style="height: 38px;">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small fw-bold text-pink">Price</label>
                    <input type="number" step="0.01" name="shades[${count}][price]" class="form-control border-pink" required>
                </div>
                <div class="col-md-1 mb-2">
                    <label class="small fw-bold text-pink">Stock</label>
                    <input type="number" name="shades[${count}][stock]" class="form-control border-pink" required>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small fw-bold text-pink">Shade Photo</label>
                    <div class="d-flex align-items-start gap-2">
                        <input type="file" name="shades[${count}][image]" class="form-control shade-image-input border-pink" accept="image/*">
                        <div class="shade-preview-container"></div>
                    </div>
                </div>
                <div class="col-md-1 mb-2 text-end">
                    <label class="small fw-bold text-muted">Remove</label>
                    <button type="button" class="btn btn-danger remove-shade w-100"><i class="fas fa-times"></i></button>
                </div>
            </div>`;
        
        $('#shades-wrapper').append(html);
        count++;
    });

    $(document).on('click', '.remove-shade', function() {
        if(confirm('Note: This shade will be removed permanently once you click Update. Continue?')) {
            $(this).closest('.shade-row').fadeOut(300, function() { $(this).remove(); });
        }
    });

    $(document).on('change', '.shade-image-input', function() {
        const input = this;
        const previewContainer = $(this).siblings('.shade-preview-container');
        const existingImg = $(this).closest('.d-flex').find('img.rounded');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (existingImg.length) {
                    existingImg.attr('src', e.target.result);
                } else {
                    previewContainer.html(`
                        <img src="${e.target.result}" class="rounded shadow-sm border border-pink" style="height: 100px; width:100px; object-fit:cover;">
                    `);
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
</script>
@endpush