<style>
    .sidebar{
        order: 2;
        width: 330px;
        padding-left: 30px;
    }
    .blog-post{
        order: 1;
        width: calc(100% - 330px);
        display: flex;
        flex-direction: column;
    }
    .blog-post .blog-title{
        margin-bottom: 1.5rem;
    }
    .blog-post .blog-title h1 {
        font-weight: 500;
        font-size: 1.8125rem !important;
    }
    .blog-post .blog-description p {
        font-size: 1.3125rem ;
        font-weight: 700;
        margin: 0 0 1em;
        line-height: 1.619;
    }
    .blog-post .post-meta {
        display: flex;
        flex-direction: row;
        justify-content: space-between
    }
    .blog-post .blog-image .post-image{
        border-radius: 10px;
        width: 100%;
    }
    .blog-post .blog-content .h-t-products1 {
        margin-top: 0;
        background-color: #f2f4f5;
        border-radius: 15px;
        padding: 15px 0;
    }
    .blog-post .blog-content .first-product .w-set {
        background-color: white;
    }
    .blog-post .blog-content h2, .blog-post .blog-content h3
    {
        margin: 1.5em 0 0.5em 0;
        font-size: 1.625rem;
        line-height: 1.15;
    }
    .blog-post .blog-content .h2_7267fp
    {
        margin: 0em 0 0em 0;
    }
    .blog-post .blog-content h3 {
        font-size: 1.25rem;
        margin: 1em 0 1em 0;
    }
    .blog-post .blog-content p{
        font-size: 1.125em;
        line-height: 1.46;
        margin: 1em 0 1em 0;
    }
    .blog-post .blog-content ul{
        list-style: disc;
        padding-left: 3em;
    }
    .sidebar .single-post img {
        width: 48px;
        min-width: 48px;
        height: 48px;
        min-height: 48px;
        margin-bottom: 14px;
        border-radius: 6px;
    }

    .sidebar .single-post .single-post__title
    {
        font-size: 13px;
    }
    .sidebar .single-post .single-post__date
    {
        font-size: 12px;
    }
    @media only screen and (max-width: 1199px) {
        .blog-post .blog-title{
            margin-bottom: .75rem;
        }
        .blog-post .blog-title h1 {
            font-size: 1.3rem !important;
        }
        .sidebar {
            display: none;
        }
        .blog-post{
            width: 100%;
        }
        .blog-post .blog-description p {
            font-size: 1rem ;
            font-weight: 500;
            margin: 0 0 1em;
            line-height: 1.619;
        }
        .blog-post .image_container {
            background-color: white;
        }
        .blog-post .blog-data span {
            margin-top: 15px;
            /*font-size: 12px;*/
        }
        .blog-post .post-meta {
            flex-direction: column;
        }
        .blog-post .post-meta .tagged {
            width: 25%;
        }
    }


</style>

<x-layout.layout>
    <section class="slider mt-4 desktop-only set_center">
        <div class="container cont_set">
            <div class="breadcumb">
                <a href="/"><span>Strona główna</span></a>
                <div class="rounded-1"></div>
                <a href="/abc-zakupowicza"><span>ABC zakupowicza</span></a>
                <div class="rounded-1"></div>
                <a href="/abc-zakupowicza/{{$blogContent->categories->slug}}"><span>{{$blogContent->categories->name}}</span></a>
                <div class="rounded-1"></div>
                <span><b>{{$blogContent->title}}</b></span>
            </div>
        </div>
    </section>
    <div class="mt-4">
        <div class="container">
            <div class="col-12 d-flex">
                <div class="sidebar">
                    <b class="mb-4 d-flex">Poleceane w kategorii</b>
                    @foreach($postsCategory as $postCategory)
                        <div class="single-post mb-2">
                            <a href="{{$postCategory->slug}}" class="d-flex gap-4">
                                <picture><source srcset="{{asset($postCategory->image_thumbnail)}}" type="image/png"><img width="150" height="150" src="{{asset($postCategory->image_thumbnail)}}" alt="{{$postCategory->title}}" loading="lazy" decoding="async"></picture> <div class="single-post__data">
                                    <span class="single-post__title mb-2">{{$postCategory->title}}</span>
                                    <span class="single-post__date">{{monthReplace($postCategory->created_at)}}</span>
                                </div>
                            </a>
                        </div>
                    @endforeach

                </div>
                <div class="blog-post">
                    <header class="blog-title">
                        <h1 class="post-title">{{$blogContent->title}}</h1>
                    </header>
                    <div class="blog-description">
                        {!! $blogContent->excerpt !!}
                    </div>
                    <div class="author mb-4">
                        <img src="{{asset('assets/image/pro/dave.png')}}" alt="Author Image" class="person-round">
                        <span class="author-name">Jan Kowalski</span>
                    </div>
                    <div class="blog-social"></div>
                    <div class="post-meta mb-4">
                        <div  class="tagged">
                            <a href="/abc-zakupowicza/{{$blogContent->categories->slug}}">
                                <span>{{$blogContent->categories->name}} ({{$blogContent->categories->articles}})</span>
                            </a>
                        </div>
                        <div class="d-flex align-items-center blog-data">
                            <span>{{date('d.m.Y', strtotime($blogContent->created_at))}}  @if ($blogContent->created_at != $blogContent->updated_at) | aktualizacja:  {{date('d.m.Y', strtotime($blogContent->updated_at))}} @endif</span>
                        </div>
                    </div>
                    <div class="blog-image mb-4">
                        <img src="{{asset($blogContent->image_path)}}" alt="Post Image" class="post-image">
                    </div>

                    <div class="blog-content">
                        @for ($i = 0; $i < $slidersData['sliders']; $i++)
                                <?php
                                // Wyrażenie regularne dla znacznika
                                $pattern = '/{slider' . $i . '}/';
                                // Sprawdzamy, czy znacznik istnieje w treści posta
                                if (preg_match($pattern, $blogContent->body)) {
                                    // Renderujemy komponent slidera i podstawiamy go w miejsce znacznika w treści postu
                                    $replacement = view('components.promotions-slider', ['items' => $slidersData['slider'.$i]['slider'], 'place' => $place ?? null, 'title' => $slidersData['slider'.$i]['title'],'class' => 'blog-leaflet-slider'])->render();
                                    // Podmieniamy wszystkie wystąpienia znacznika w treści postu na wyrenderowany komponent slidera
                                    $blogContent->body = preg_replace($pattern, $replacement, $blogContent->body, 1);
                                }
                                ?>
                        @endfor

                        {!! $blogContent->body !!}



                    </div>

                </div>
            </div>
        </div>
    </div>
</x-layout.layout>
