@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Create New Product</h4>
                    <a class="btn btn-secondary" href="{{ route('admin.products') }}">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <h5 class="alert-heading">Whoops!</h5>
                        <p>There were some problems with your input.</p>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            {{-- Product Name (required) --}}
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Product Name *</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                @error('name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                                {{-- Brand (required) --}}
                                <div class="col-md-6 mb-3">
                                    <label for="brand" class="form-label">Brand *</label>
                                    <input type="text" name="brand" class="form-control" value="{{ old('brand') }}" required>
                                    @error('brand')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                            {{-- Model (nullable) --}}
                            <div class="col-md-6 mb-3">
                                <label for="model" class="form-label">Model</label>
                                <input type="text" name="model" class="form-control" value="{{ old('model') }}">
                            </div>

                            {{-- Capacity (required) --}}
                            <div class="col-md-6 mb-3">
                                <label for="capacity" class="form-label">Capacity (L) *</label>
                                <input type="text" name="capacity" class="form-control" value="{{ old('capacity') }}" required>
                                @error('capacity')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Power (required) --}}
                            <div class="col-md-6 mb-3">
                                <label for="power" class="form-label">Power (W) *</label>
                                <input type="text" name="power" class="form-control" value="{{ old('power') }}" required>
                                @error('power')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Voltage (required) --}}
                            <div class="col-md-6 mb-3">
                                <label for="voltage" class="form-label">Voltage (V) *</label>
                                <input type="text" name="voltage" class="form-control" value="{{ old('voltage') }}" required>
                                @error('voltage')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Color (nullable) --}}
                            <div class="col-md-6 mb-3">
                                <label for="color" class="form-label">Color</label>
                                <input type="text" name="color" class="form-control" value="{{ old('color') }}">
                            </div>

                            {{-- Weight (nullable) --}}
                            <div class="col-md-6 mb-3">
                                <label for="weight" class="form-label">Weight (kg)</label>
                                <input type="text" name="weight" class="form-control" value="{{ old('weight') }}">
                            </div>

                            {{-- Dimensions (nullable) --}}
                            <div class="col-md-6 mb-3">
                                <label for="dimensions" class="form-label">Dimensions (cm)</label>
                                <input type="text" name="dimensions" class="form-control" value="{{ old('dimensions') }}">
                            </div>

                            {{-- Functions (nullable) --}}
                            <div class="col-md-6 mb-3">
                                <label for="functions" class="form-label">Functions</label>
                                <textarea name="functions" class="form-control">{{ old('functions') }}</textarea>
                            </div>

                            {{-- Warranty (required) --}}
                            <div class="col-md-6 mb-3">
                                <label for="warranty" class="form-label">Warranty (months) *</label>
                                <input type="number" name="warranty" class="form-control" value="{{ old('warranty') }}" required>
                                @error('warranty')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Price (required) --}}
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price *</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
                                @error('price')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Stock (required) --}}
                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label">Stock *</label>
                                <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" required>
                                @error('stock')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Category (required) --}}
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Category *</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id')==$category->id ? 'selected':'' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Image (nullable) --}}
                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label">Product Image</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Product
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
