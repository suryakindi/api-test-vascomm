<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function product(Request $request)
    {
        $take = $request->input('take', 10); 
        $skip = $request->input('skip', 0); 
        $search = $request->input('search', ''); 
        
        $validator = Validator::make($request->all(), [
            'take' => 'integer|min:1|max:100',
            'skip' => 'integer|min:0',
            'search' => 'string|max:255', 
        ]);

        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }
        
        $product = Product::when($search, function ($query) use ($search) {
            return $query->where('name_product', 'like', '%' . $search . '%');
        })
        ->skip($skip)
        ->take($take)
        ->get();
        if($product == NULL)
        {
            return response()->json([
                'message' => 'Not Found',
                'data' => $product,
            ], 404); 
        }
        return response()->json([
            'message' => 'Success Get Data Product',
            'data' => $product,
            'code'=> 200,
        ], 200); 
    }

    public function createproduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_product' => 'required|string',
            'quantity' => 'integer|min:0',
            'price' => 'integer',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }
        $product = Product::create([
            'name_product' => $request->name_product,
            'quantity' => $request->quantity,
            'price' => $request->price,
            
        ]);
        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product,
            'code'=> 201,
        ], 201); 
    }

    public function editproduct(Request $request, $id)
    {
        
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'name_product' => 'string|max:255',
            'quantity' => 'email|unique:users,email',
            'price' => 'integer|min:0',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }
        $product->name_product = $request->name_product ?? $product->name_product;
        $product->quantity = $request->quantity ?? $product->quantity;
        $product->price = $request->price ?? $product->price;
        
        $product->save();

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product,
            'code'=> 200,
        ], 200);

    }

    public function deleteproduct($id)
    {
        $product = Product::find($id);
        if($product){
            $product->delete();
            return response()->json([
                'message' => 'Success Delete Product',
                'user_id'=> $id,
                'code'=> 200,
            ], 200); 
        }else{
            return response()->json([
                'message' => 'Product Not Found',
                'code'=> 404,
            ], 404);    
        }
    }
    public function restoreproduct($id)
    {
        $product = Product::withTrashed()->where('id', $id)->first();
        if ($product) {
            $product->restore();
            return response()->json([
                'message' => 'Success Restore Product',
                'user_id' => $id,
                'code'=> 200,
            ], 200);
        }else{
            return response()->json([
                'message' => 'Product Not Found',
                'code'=> 404,
            ], 404);    
        }
      
    }
}
