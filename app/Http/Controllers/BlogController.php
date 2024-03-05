<?php

namespace App\Http\Controllers;

use App\CustomPaginator;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\CategoryArticle;
use App\Models\Leaflet;
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

       return view('main.blog.index',[
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

    public function showByCategory($category_slug, $page_number = 1)
    {
        $blog_categories = CategoryArticle::all();
        $blog_category = $blog_categories->where('slug', '=', $category_slug)->first();
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


        return view('main.blog.index',[
            'metaTitle' => $meta_title,
            'metaDescription' => $meta_description,
            'blogs' => $paginators,
            'blogCategories' => $blog_categories,
            'slug' => $category_slug
        ]);
    }

    public function show($category_slug, $blog_slug)
    {
        $blog_categories = CategoryArticle::all();
        $blog_category = $blog_categories->where('slug', '=', $category_slug)->first();
        if($blog_category === null){
            abort(404);
        }
        $posts = Blog::with('categories', 'sliders')->get();
        $posts_category = $posts->where('category_article_id','=', $blog_category->id);
        $blog_content = $posts->where('slug','=', $blog_slug)->first();
        //$blog_content = Blog::with('categories')->where('slug','=', $blog_slug)->first();

        if ($blog_content === null){
            abort(404);
        }


        $sliders_data = [];
        $sliders_data['sliders'] = count($blog_content->sliders);
        for ($i = 0; $i < count($blog_content->sliders); $i++)
        {
            $sliderName = 'slider' . $i;
            $leaflets = Leaflet::with('store')->where('end_offer_date', '>=', date('Y-m-d'))
                ->where('store_id', '=', $blog_content->sliders[$i]->store_id)->get();
            $sliders_data[$sliderName]['slider'] = $leaflets;
            $sliders_data[$sliderName]['title'] = 'Sprawdź gazetki '. $leaflets[0]->store->name_genitive;
        }


        return view('main.blog.show',[
            'blogCategories' => $blog_categories,
            'blogCategory' => $blog_category,
            'categorySlug' => $category_slug,
            'blogSlug' => $blog_slug,
            'blogContent' => $blog_content,
            'postsCategory' => $posts_category,
            'slidersData' => $sliders_data,
        ]);
    }

}
