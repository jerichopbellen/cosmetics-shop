@extends('layouts.base')

@section('body')
<div class="container py-5">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark">Create New Product</h2>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <div class="card shadow-sm border-0 mb-4 overflow-hidden">
            <div style="height: 4px; background-color: #ec4899;"></div>
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-info-circle me-2 text-pink"></i>General Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted small">Product Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Velvet Lip Cream" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-muted small">Brand</label>
                        <select name="brand_id" class="form-control" required>
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-muted small">Category</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-bold text-muted small">Description</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Enter product features..."></textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-muted small">Finish</label>
                        <input type="text" name="finish" class="form-control" placeholder="e.g. Matte, Dewy, Satin">
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
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted small">Upload Gallery Images (Select multiple)</label>
                    <input type="file" name="gallery[]" class="form-control" multiple accept="image/*" id="gallery-input">
                    <div id="gallery-preview" class="d-flex flex-wrap gap-2 mt-3"></div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4 overflow-hidden">
            <div style="height: 4px; background-color: #ec4899;"></div>
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">Product Shades</h5>
                <button type="button" id="add-shade" class="btn btn-pink btn-sm fw-bold">
                    <i class="fas fa-plus me-1"></i> Add Shade Row
                </button>
            </div>
            <div class="card-body bg-light" id="shades-wrapper">
                </div>
        </div>

        <div class="mb-5 text-end">
            <button type="submit" class="btn btn-pink btn-lg px-5 shadow fw-bold">Save Product</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // 1. Handle Main Gallery Preview with Pink borders
    $('#gallery-input').on('change', function() {
        $('#gallery-preview').empty();
        const files = this.files;
        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#gallery-preview').append(`
                    <div class="position-relative">
                        <img src="${e.target.result}" class="img-thumbnail border-pink shadow-sm" style="width:120px; height:120px; object-fit:cover;">
                    </div>
                `);
            }
            reader.readAsDataURL(files[i]);
        }
    });

    // 2. Handle Dynamic Shades
    let count = 0;
    
    function addShadeRow() {
        let html = `
            <div class="row shade-row border p-3 mb-3 bg-white rounded shadow-sm align-items-start border-pink" style="border-width: 1px !important;">
                <div class="col-md-3 mb-2">
                    <label class="small fw-bold text-pink">Shade Name</label>
                    <input type="text" name="shades[${count}][shade_name]" class="form-control border-pink" placeholder="e.g. Rose Pink" required>
                </div>
                <div class="col-md-1 mb-2">
                    <label class="small fw-bold text-pink">Color</label>
                    <input type="color" name="shades[${count}][hex_code]" class="form-control p-1 border-pink" style="height: 38px;">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small fw-bold text-pink">Price</label>
                    <input type="number" step="0.01" name="shades[${count}][price]" class="form-control border-pink" placeholder="0.00" required>
                </div>
                <div class="col-md-1 mb-2">
                    <label class="small fw-bold text-pink">Stock</label>
                    <input type="number" name="shades[${count}][stock]" class="form-control border-pink" placeholder="0" required>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small fw-bold text-pink">Shade Photo</label>
                    <div class="d-flex align-items-start gap-2">
                        <input type="file" name="shades[${count}][image]" class="form-control shade-image-input border-pink" accept="image/*">
                        <div class="shade-preview-container"></div>
                    </div>
                </div>
                <div class="col-md-1 mb-2">
                    <label class="small fw-bold text-muted">Remove</label>
                    <button type="button" class="btn btn-outline-danger remove-shade w-100"><i class="fas fa-times"></i></button>
                </div>
            </div>`;
        
        $('#shades-wrapper').append(html);
        count++;
    }

    // Add first row on load
    addShadeRow();

    $('#add-shade').click(function() {
        addShadeRow();
    });

    // Remove shade row
    $(document).on('click', '.remove-shade', function() {
        if ($('.shade-row').length > 1) {
            $(this).closest('.shade-row').remove();
        } else {
            alert('At least one shade is required.');
        }
    });

    // 3. Handle Individual Shade Image Preview (Delegated)
    $(document).on('change', '.shade-image-input', function() {
        const input = this;
        const previewContainer = $(this).siblings('.shade-preview-container');
        previewContainer.empty();

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.html(`
                    <img src="${e.target.result}" class="rounded shadow-sm border border-pink" style="width:100px; height:100px; object-fit:cover;">
                `);
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
</script>
@endpush