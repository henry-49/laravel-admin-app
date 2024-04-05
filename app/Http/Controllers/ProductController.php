<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    //
     //return all products
     public function index()
     {
         return ProductResource::collection(Product::paginate());
     }

     public function store(Request $request)
     {
         $product = Product::create($request->only('title', 'description', 'image', 'price'));

         // load will display the permission when created
         return response(new ProductResource($product), Response::HTTP_CREATED);
     }

     public function show($id)
     {
         // show users with their repective products
         return new ProductResource(Product::find($id));
     }

     public function update(Request $request, $id)
     {
         $product = Product::find($id);
         // update the name of the requested field
         $product->update($request->only('title', 'description', 'image', 'price'));

         // sync we see the current value of the permissions and update them accordingly
         // sync will detach the old products and attach the new products
        //  $product->permissions()->sync($request->input('permissions'));

         return \response(new ProductResource($product), Response::HTTP_ACCEPTED);
     }

     public function destroy($id)
     {
         Product::destroy($id);

         return \response(null, Response::HTTP_NO_CONTENT);
     }
}
