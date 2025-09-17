<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private function castEmptyToNull(Request $request, array $fields): void
    {
        foreach ($fields as $f) {
            $val = trim((string) $request->input($f));
            if ($val === '') {
                $request->merge([$f => null]);
            }
        }
    }

    private function getValidationRules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'brand'       => 'required|string|max:255',
            'model'       => 'nullable|string|max:255',
            'capacity'    => 'nullable|integer',
            'power'       => 'nullable|integer',
            'voltage'     => 'nullable|integer',
            'color'       => 'nullable|string|max:100',
            'weight'      => 'nullable|numeric',
            'dimensions'  => 'nullable|string|max:255',
            'functions'   => 'nullable|string',
            'warranty'    => 'nullable|integer',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    private function uploadImage(Request $request, ?string $oldImage = null): ?string
    {
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($oldImage) {
                $this->deleteImage($oldImage);
            }

            $file = $request->file('image');
            $imageName = uniqid('prd_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $imageName);

            return $imageName;
        }
        return $oldImage;
    }

    private function deleteImage(?string $imageName): void
    {
        if ($imageName) {
            $path = public_path('uploads/products/' . $imageName);
            if (File::exists($path)) {
                File::delete($path);
            }
        }
    }

    public function index()
    {
        $products = Product::with('category')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // $this->castEmptyToNull($request, ['capacity','power','voltage','weight','warranty']);

        // $validated = $request->validate($this->getValidationRules());

        // $validated['image'] = $this->uploadImage($request);

        // Product::create($validated);
        $data = $request->validate($this->getValidationRules());

    $product = new Product();
    $product->name        = $data['name'];
    $product->brand       = $data['brand'];   // <- đảm bảo có brand
    $product->model       = $data['model'] ?? null;
    $product->capacity    = $data['capacity'] ?? null;
    $product->power       = $data['power'] ?? null;
    $product->voltage     = $data['voltage'] ?? null;
    $product->color       = $data['color'] ?? null;
    $product->weight      = $data['weight'] ?? null;
    $product->dimensions  = $data['dimensions'] ?? null;
    $product->functions   = $data['functions'] ?? null;
    $product->warranty    = $data['warranty'] ?? null;
    $product->price       = $data['price'];
    $product->stock       = $data['stock'];
    $product->category_id = $data['category_id'];

    // upload ảnh nếu có
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $imageName = uniqid('prd_') . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/products'), $imageName);
        $product->image = $imageName;
    }

    $product->save();

        return redirect()->route('products.index')
            ->withSuccess('Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load('category');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->castEmptyToNull($request, ['capacity','power','voltage','weight','warranty']);

        $validated = $request->validate($this->getValidationRules());

        $validated['image'] = $this->uploadImage($request, $product->image);

        $product->update($validated);

        return redirect()->route('products.index')
            ->withSuccess('Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->deleteImage($product->image);
        $product->delete();

        return redirect()->route('products.index')
            ->withSuccess('Product deleted successfully.');
    }
}
