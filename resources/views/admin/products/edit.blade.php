@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Edit Product</h4>
                    <a class="btn btn-secondary" href="{{ route('admin.products') }}">
                        Back
                    </a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Product Name *</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="brand" class="form-label">Brand *</label>
                                <input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="model" class="form-label">Model</label>
                                <input type="text" name="model" class="form-control" value="{{ old('model', $product->model) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="capacity" class="form-label">Capacity (L) *</label>
                                <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $product->capacity) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="power" class="form-label">Power (W) *</label>
                                <input type="number" name="power" class="form-control" value="{{ old('power', $product->power) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="voltage" class="form-label">Voltage (V)</label>
                                <input type="number" name="voltage" class="form-control" value="{{ old('voltage', $product->voltage ?? 220) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="color" class="form-label">Color</label>
                                <input type="text" name="color" class="form-control" value="{{ old('color', $product->color) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="weight" class="form-label">Weight (kg)</label>
                                <input type="number" step="0.01" name="weight" class="form-control" value="{{ old('weight', $product->weight) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="dimensions" class="form-label">Dimensions (cm)</label>
                                <input type="text" name="dimensions" class="form-control" value="{{ old('dimensions', $product->dimensions) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="functions" class="form-label">Functions</label>
                                <textarea name="functions" class="form-control">{{ old('functions', $product->functions) }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="warranty" class="form-label">Warranty (months)</label>
                                <input type="number" name="warranty" class="form-control" value="{{ old('warranty', $product->warranty ?? 12) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price *</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label">Stock *</label>
                                <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Category *</label>
                                <select name="category_id" class="form-select" required>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id)==$category->id ? 'selected':'' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label">Product Image</label>
                                <input type="file" name="image" class="form-control">
                                @if($product->image)
                                <small class="text-muted">Current: {{ $product->image }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Update Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection