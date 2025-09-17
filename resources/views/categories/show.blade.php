@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Category Details</h4>
                    <div>
                        <!-- <a class="btn btn-primary btn-sm" href="{{ route('admin.categories', $category->id) }}">
                            <i class="fas fa-edit"></i> Edit -->
                        </a>
                        <a class="btn btn-secondary btn-sm" href="{{ route('categories.index') }}">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th width="150px" class="text-muted">ID:</th>
                                        <td>{{ $category->id }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Name:</th>
                                        <td>{{ $category->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Created:</th>
                                        <td>{{ $category->created_at->format('F d, Y \a\t g:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Last Updated:</th>
                                        <td>{{ $category->updated_at->format('F d, Y \a\t g:i A') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection