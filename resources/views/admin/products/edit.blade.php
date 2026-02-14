@extends('layouts.base')

@section('body')
<div class="container py-5">
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Edit Product: {{ $product->name }}</h2>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Back to List</a>
        </div>

        <!-- Product Details -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Product Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Product Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Brand</label>
                        <select name="brand_id" class="form-control" required>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Category</label>
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Finish</label>
                        <input type="text" name="finish" class="form-control" value="{{ $product->finish }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Gallery -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-images me-2"></i>Product Gallery</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <label class="form-label fw-bold">Upload New Photos</label>
                    <input type="file" name="gallery[]" class="form-control" multiple accept="image/*" id="gallery-input">
                    <small class="text-muted">You can select multiple files at once.</small>
                    <div id="gallery-preview" class="d-flex flex-wrap gap-2 mt-3"></div>
                </div>

                <h6>Current Gallery</h6>
                <div class="d-flex flex-wrap gap-3 p-3 bg-light rounded border justify-content-center">
                    @forelse($product->images as $img)
                        <div class="position-relative text-center border p-2 bg-white rounded shadow-sm">
                            <img src="{{ asset('storage/' . $img->image_path) }}" 
                                class="img-thumbnail mb-2" 
                                style="width: 250px; height: 250px; object-fit:cover;">
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remove_images[]" value="{{ $img->id }}" id="img_{{ $img->id }}">
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

        <!-- Shades -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Manage Shades</h5>
                <button type="button" id="add-shade" class="btn btn-light btn-sm fw-bold">+ Add New Shade</button>
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
                                    <img src="{{ asset('storage/' . $shade->image_path) }}" class="rounded shadow-sm border" style="height: 100px; width:100px; object-fit:cover;">
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

        <!-- Actions -->
        <div class="mb-5 d-flex gap-2">
            <button type="submit" class="btn btn-warning btn-lg px-5 shadow text-dark fw-bold">Update Product</button>
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
                        <img src="${e.target.result}" class="img-thumbnail" style="width:120px; height:120px; object-fit:cover;">
                    </div>
                `);
            }
            reader.readAsDataURL(files[i]);
        }
    });

    // 2. Shade Counter
    let count = {{ $product->shades->count() }};

    // 3. Add New Shade Row
    $('#add-shade').click(function() {
        let html = `
            <div class="row shade-row border p-3 mb-3 bg-white rounded shadow-sm align-items-start border-primary">
                <input type="hidden" name="shades[${count}][id]" value="0">
                <div class="col-md-3 mb-2">
                    <label class="small fw-bold text-primary">New Shade Name</label>
                    <input type="text" name="shades[${count}][shade_name]" class="form-control border-primary" required>
                </div>
                <div class="col-md-1 mb-2">
                    <label class="small fw-bold text-primary">Color</label>
                    <input type="color" name="shades[${count}][hex_code]" class="form-control p-1 border-primary" style="height: 38px;">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small fw-bold text-primary">Price</label>
                    <input type="number" step="0.01" name="shades[${count}][price]" class="form-control border-primary" required>
                </div>
                <div class="col-md-1 mb-2">
                    <label class="small fw-bold text-primary">Stock</label>
                    <input type="number" name="shades[${count}][stock]" class="form-control border-primary" required>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small fw-bold text-primary">Shade Photo</label>
                    <div class="d-flex align-items-start gap-2">
                        <input type="file" name="shades[${count}][image]" class="form-control shade-image-input border-primary" accept="image/*">
                        <div class="shade-preview-container"></div>
                    </div>
                </div>
                <div class="col-md-1 mb-2 text-end">
                    <button type="button" class="btn btn-danger remove-shade w-100"><i class="fas fa-times"></i></button>
                </div>
            </div>`;
        
        $('#shades-wrapper').append(html);
        count++;
    });

    // 4. Remove Shade Row
    $(document).on('click', '.remove-shade', function() {
        if(confirm('Note: This shade will be removed permanently once you click Update. Continue?')) {
            $(this).closest('.shade-row').fadeOut(300, function() { $(this).remove(); });
        }
    });

    // Shade Image Preview (replace existing image)
    $(document).on('change', '.shade-image-input', function() {
        const input = this;
        const previewContainer = $(this).siblings('.shade-preview-container');
        const existingImg = $(this).closest('.d-flex').find('img.img-thumbnail');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (existingImg.length) {
                    // Replace current image src
                    existingImg.attr('src', e.target.result);
                } else {
                    // Otherwise show new preview
                    previewContainer.html(`
                        <img src="${e.target.result}" class="rounded shadow-sm border" style="height: 100px; width:100px; object-fit:cover;">
                    `);
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
</script>
@endpush