<?php

namespace App\Http\Controllers;

use App\CustomPaginator;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\CategoryArticle;
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
        $base_url = url()->current();
        $base_url = preg_split('/,/', $base_url);
        $url = $base_url[0];
        if(isset($base_url[1])) {
            $page_number = $base_url[1];
        }
        $blog_categories = CategoryArticle::all();
        $blogs = Blog::with('categories')->get();


        $blogs_porady = $blogs->where('category_article_id','=', 1)->take(4);
        $blogs_recenzje = $blogs->where('category_article_id','=', 2)->take(4);
        $blogs_porownania = $blogs->where('category_article_id','=', 3)->take(4);
        $blogs_gazetki = $blogs->where('category_article_id','=', 4)->take(4);
        $blogs_przepisy = $blogs->where('category_article_id','=', 5)->take(4);
        $blogs_aktualnosic = $blogs->where('category_article_id','=', 6)->take(4);

       return view('main.blog',[
           'metaTitle' => $meta_title,
           'metaDescription' => $meta_description,
           'blogs' => $blogs_porady,
           'blogs_recenzje' => $blogs_recenzje,
           'blogs_porownania' => $blogs_porownania,
           'blogs_gazetki' => $blogs_gazetki,
           'blogs_przepisy' => $blogs_przepisy,
           'blogs_aktualnosic' => $blogs_aktualnosic,
           'blogCategories' => $blog_categories,


        ]);
    }

    public function showByCategory($slug, $page_number = 1)
    {
        $blog_categories = CategoryArticle::all();
        $blog_category = $blog_categories->where('slug', '=', $slug)->first();
        if($blog_category === null){
            abort(404);
        }
        $site = 'blog-index-'.$blog_category->id;

        $descriptions = siteValidator($site);

        if($descriptions->meta->isEmpty()){
            $meta_title = 'Uwaga przykładowy tytuł';
            $meta_description = 'Uwaga przykładowy opis';
        } else {
            $meta_title = $descriptions->meta->first()->meta_title;
            $meta_description = $descriptions->meta->first()->meta_description;
        }
        $page_descriptions = $descriptions->descriptions->where('place','=','bottom');
        $base_url = url()->current();
        $base_url = preg_split('/,/', $base_url);
        $url = $base_url[0];
        if(isset($base_url[1])) {
            $page_number = $base_url[1];
        }

        $blogs = Blog::with('categories')->where('category_article_id', '=', $blog_category->id)->paginate(8,['*'],'page',$page_number);

        $paginators = new CustomPaginator(
            $blogs->items(), // dane
            $blogs->total(), // całkowita liczba wyników
            $blogs->perPage(), // wyniki na stronie
            $blogs->currentPage(),
        );

        $paginators->withPath($url);


        return view('main.blog',[
            "metaTitle" => $meta_title,
            'metaDescription' => $meta_description,
            'blogs' => $paginators,
            'blogCategories' => $blog_categories,
            'slug' => $slug
        ]);
    }
}
