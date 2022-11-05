<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ProductGallery extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded =['id'];


    public function getUrlAttribute($url)
    {
        // docmuentasi Eloquent: Mutator & Casting -> Defining A Accesor
        return config('app.url') . Storage::url($url);
    }
}
