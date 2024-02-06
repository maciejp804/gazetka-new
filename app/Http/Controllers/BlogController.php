<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $site = 'blog-index';
        $descriptions = siteValidator($site);


        if($descriptions->meta->isEmpty()){
            $meta_title = 'Uwaga przykładowy tytuł';
            $meta_description = 'Uwaga przykładowy opis';
        } else {
            $meta_title = $descriptions->meta->first()->meta_title;
            $meta_description = $descriptions->meta->first()->meta_description;
        }
        $page_descriptions = $descriptions->descriptions->where('place','=','bottom');

        return view('main.blog',[
            "metaTitle" => $meta_title,
            'metaDescription' => $meta_description,
        ]);
    }
}
