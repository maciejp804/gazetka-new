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
<section class="category-img1 section-t-padding section-b-padding section_q2vymy" style="padding-bottom: 20px">
    <div class="container">
        <form class="row" id="id_filter">
            <div class="col-md-4">
                <div class="header-main-area div_j18kep">
                    <div class="header-main">
                        <div class="header-element search-wrap div_xgmv7r">
                            <select name="category" class="select" required id="id_category">
                                @foreach($leafletCategories as $leaftetategory)
                                    <option value="{{$leaftetategory->category_index}}">{{$leaftetategory->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="header-main-area div_k6ou7a">
                    <div class="header-main">
                        <div class="header-element search-wrap div_kdhdcb">
                            <select name="sort" class="select" id="id_sort">
                                <option value="0">Sortuj wg: Najnowsze</option>
                                <option value="1">Sortuj wg: Nadchodzące</option>
                                <option value="2">Sortuj wg: Kończące się</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="header-main-area div_nls93k">
                    <div class="header-main">
                        <div class="header-element search-wrap div_zupb0j">
                            <input type="text" name="search" class="input_6kde06" placeholder="Wpisz nazwę sieci..." id="id_search">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
