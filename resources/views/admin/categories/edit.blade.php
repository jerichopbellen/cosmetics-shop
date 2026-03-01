@extends('layouts.base')

@section('body')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 overflow-hidden">
                <div style="height: 4px; background-color: #ec4899;"></div>
                
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-edit me-2 text-pink"></i>Edit Category: 
                        <span class="text-pink">{{ $category->name }}</span>
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold text-muted small">Category Name</label>
                            <input type="text" name="name" id="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $category->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-pink shadow-sm fw-bold">
                                Update Category
                            </button>
                            <a href="{{ route('categories.index') }}" class="btn btn-light border">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <p class="text-center mt-4">
                <a href="{{ route('categories.index') }}" class="text-muted small text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i> Return to Category List
                </a>
            </p>
        </div>
    </div>
</div>
@endsection