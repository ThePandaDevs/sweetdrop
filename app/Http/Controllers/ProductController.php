<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    private $response = ["success" => false, "message" => 'Your product have not been found', "data" => []];

    public function index()
    {
        $products = Product::query()->where('is_active', true)->get();
        return [
            'success' => true,
            'message' => 'List of products',
            'data' => $products
        ];
    }


    public function store(ProductRequest $request)
    {
        $product = new Product([
            'sku' => $this->generateSku(),
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $request->image,
        ]);
        $product->save();
        return [
            'success' => true,
            'message' => 'Your product have been stored',
            'data' => $product
        ];
    }

    private function generateSku()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString . '-' . date('Y');
    }


    public function show($id)
    {
        $product = Product::query()->where('id', $id)->where('is_active', true)->first();
        if ($product) return ["success" => true, "message" => 'Your product have been found', "data" => $product];
        return $this->response;
    }


    public function update(ProductRequest $request)
    {
        $product = Product::query()->where('id', $request->id)->where('is_active', true)->first();
        if ($product) {
            $product->sku = $request->sku;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->image = $request->image;
            $product->save();
            return [
                'success' => true,
                'message' => 'Your product have been updated',
                'data' => $product
            ];
        }
        return $this->response;
    }


    public function destroy($id)
    {
        $product = Product::query()->where('id', $id)->where('is_active', true)->first();
        if ($product) {
            $product->is_active = false;
            $product->save();
            return [
                'success' => true,
                'message' => 'Your product have been deleted',
                'data' => $product
            ];
        }
        return $this->response;
    }
}
