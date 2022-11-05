<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $name = $request->input('name');
        $description = $request->input('description');
        $tags = $request->input('tags');
        $categories = $request->input('categories');
        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        if($id) {
            $product = Product::with(['category', 'galleries'])->find($id);
            if($product) {
                return ResponseFormatter::success(
                    $product,
                    'Data Product Berhasil Di Ambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data Product Tidak Ada',
                    404
                );
            }
        }
        $product = Product::with(['category', 'galleries']);
        if($name) {
            $product->where('name','like','%' . $name . '%');       //query search name product
        }
        if($description) {
            $product->where('des$description','like','%' . $description . '%');       //query search description product
        }
        if($tags) {
            $product->where('des$tags','like','%' . $tags . '%');       //query search tags product
        }
        if($price_from) {
            $product->where('price','>=',$price_from);      // menampilkan harga lebih dari atau minimal harga
        }
        if($price_to) {
            $product->where('price','<=',$price_to);        // menampilkan harga kurang dari atau maximal harga
        }
        if($categories) {
        /*    $product->where('categories', '=', $categories); // di singkat menjadi seperti di bawah */
            $product->where('categories', $categories);        // untuk filter data
        }
        return ResponseFormatter::success(
            $product->paginate($limit),
            'Data Product Berhasil Di Ambil'
        );
    }
}
