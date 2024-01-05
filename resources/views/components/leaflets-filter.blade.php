<section class="szukane1 category-img1 section-t-padding section-b-padding section_pbrcnh">
    <div class="container">
        <div class="section-title3">
            <h2 class="desktop-only h2_yhnkej">
                <span>Przeglądaj gazetki i katalogi</span>
            </h2>
            <h2 class="mob-only">Przeglądaj gazetki i katalogi
                <div class="border_color"></div>
            </h2>
        </div>
    </div>
</section>
<x-dropdown-filter :categories="$categories"/>

<div id="promotions-box">

    <x-promotions-box-filter :leaflets="$leaflets"/>

    <div class="all-blog text-center mt-4">
        <a href="/promotions/" class="btn btn-style1 a_homzsp">Pokaż więcej</a>
    </div>
</div>
