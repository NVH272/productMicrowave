@extends('layouts.app')

@section('content')
<h2 class="section-title mb-4">Danh sách danh mục</h2>
<table class="table table-striped table-hover shadow-sm">
    <thead class="table-primary">
        <tr>
            <th>#</th>
            <th>Tên danh mục</th>
            <th>Ngày tạo</th>
            <th>Cập nhật</th>
            <th class="text-center">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $index => $category)
        <tr>
            <td><span class="badge bg-secondary">#{{ $index + 1 }}</span></td>
            <td class="fw-bold">{{ $category->name }}</td>
            <td><i class="bi bi-calendar"></i> {{ $category->created_at->format('d/m/Y H:i') }}</td>
            <td><i class="bi bi-clock-history"></i> {{ $category->updated_at->format('d/m/Y H:i') }}</td>
            <td class="text-center">
                <a href="{{ route('categories.show', $category->id) }}"
                    class="btn btn-outline-info btn-sm">
                    <i class="bi bi-eye"></i> Xem
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection