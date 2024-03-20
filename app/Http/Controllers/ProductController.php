<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImages; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    //
    public function getProducts(Request $request){
        
        $search = $request->input('search');

        $category = $request->input('category');
        
        $products = Product::with('category')->paginate(10);

        $query = Product::with('category','images');

        if ($category) {
            $query->whereHas('category', function ($query) use ($category) {
                $query->where('name', 'like', '%' . $category . '%');
            });
        }
    
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
    
        $query->orderBy('updated_at', 'desc');

        $products = $query->paginate(10);
        
        return response()->json($products);

    }

    public function getProductsCategories(Request $request){
        $products_category = ProductCategory::pluck('name')->toArray();
        return response()->json($products_category);
    }

    public function deleteProduct($id){
      
        $product = Product::find($id);
        \Log::info($product);
     
        if($product) {
            // Delete the product
            $product->delete();
    
            // Return a success response
            return response()->json(['message' => 'Product deleted successfully']);
        } else {
            // Return a not found response if the product does not exist
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    public function createProduct(Request $request){
        \Log::info($request->all());
        $totalImages = $request->input('total_images');

        $validatedData = $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'description' => 'required|string',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate each image file
        ]);
        
        $category = ProductCategory::where('name', $validatedData['category'])->first();

        // Create a new product instance
        $product = new Product();
        $product->name = $validatedData['name'];
        $product->category_id = $category->id;
        $product->description = $validatedData['description'];
        // Save the product data to the database
        $product->save();

        // Loop through the images and process them
        for ($i = 1; $i <= $totalImages; $i++) {
            // Retrieve each image by its corresponding key
            $image = $request->file("image$i");
            \Log::info("image$i");
            \Log::info($image);
            // Check if the image exists and is valid
            if ($image && $image->isValid()) {
                // Store the image in the storage directory
                $path = $image->store('products', 'public');
    
                // Associate the image path with the product
                $productImage = new ProductImages();
                $productImage->product_id = $product->id; // Assuming you have $product available
                $productImage->path = $path;
                $productImage->save();
            }
        }
    
        // Return a success response
        return response()->json(['message' => 'Product created successfully'], 200);
    }

    public function getProduct($id){

        $query = Product::with('category', 'images')->findOrFail($id);
        // Return the product
        return response()->json($query);
    }

    public function updateProduct(Request $request){

        \Log::info($request->all());

        $totalImages = $request->input('total_images');

        $product = Product::findOrFail($request->id);

        $validatedData = $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'description' => 'required|string',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate each image file
        ]);

        $category = ProductCategory::where('name', $validatedData['category'])->first();

        // $product->update($validatedData);
        

        $product->name = $validatedData['name'];
        $product->category_id = $category->id;
        $product->description = $validatedData['description'];
        // Save the product data to the database
        $product->save();

        
        // Loop through the images and process them
        for ($i = 1; $i <= $totalImages; $i++) {
            // Retrieve each image by its corresponding key
            $image = $request->file("image$i");
          
            // Check if the image exists and is valid
            if ($image && $image->isValid()) {
                
                if($i == 1){
                    $product->images()->delete();
                }
                
                // Store the image in the storage directory
               
                $path = $image->store('products', 'public');
    
                // Associate the image path with the product
                $productImage = new ProductImages();
                $productImage->product_id = $product->id; // Assuming you have $product available
                $productImage->path = $path;
                $productImage->save();
            }
        }

        return response()->json(['message' => 'Product updated successfully']);

    }
}
