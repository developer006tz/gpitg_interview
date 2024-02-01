<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        DB::enableQueryLog();
        $products = ProductResource::collection(Product::query()->paginate(10));
       
        
        return response()->json(['status' => 200, 'products' => $products, 'query' => DB::getQueryLog()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255', 'unique:products'],
        ];

        $validator = validator()->make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'errors' => $validator->errors()->toArray()]);
        }

        try {
            Product::create($request->all());
            return response()->json(['status' => 200, 'message' => 'Product added successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage() . ',' . $e->getLine()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //show product
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['status' => 404, 'message' => 'Product not found']);
        }
        return response()->json(['status' => 200, 'product' => new ProductResource($product)]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        
        //update product
        $rules = [
            'name' => ['required', 'string', 'max:255', 'unique:products,name,' . $product->id],
        ];

        $validator = validator()->make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'errors' => $validator->errors()->toArray()]);
        }

        try {
            $product->update($request->all());
            return response()->json(['status' => 200, 'message' => 'Product updated successfully', 'product' => new ProductResource($product)]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage() . ',' . $e->getLine()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //delete product
        $product = Product::find($id);
        try {
            if($product){
                $product->delete();
                return response()->json(['status' => 200, 'message' => 'Product deleted successfully']);
            }else{
                throw new \Exception('Product not found',500);
            }
            
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage() . ',' . $e->getLine()]);
        }
    }
}
