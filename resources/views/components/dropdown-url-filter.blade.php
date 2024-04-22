<style>

.a-hover:hover{
    background: #007abb;
    color: white;
}
.text-gray-400 {
    color: #787878;
}
.border-gray-300
{
    border: 1px solid #d9d9d9;
}
.rounded-md{
    border-radius: 15px;
}
.rounded-top-md{
    border-top-right-radius: 15px;
    border-top-left-radius: 15px;
}
.rounded-bottom-md{
    border-bottom-right-radius: 15px;
    border-bottom-left-radius: 15px;
}
.flex-column:hover{
    flex-direction: column;
}
#id_filter_css .header-main-area .header-main {
    padding: 0;
}
.new {
    background: #90dbff;
    color: #ffffff;
}
@media only screen and (max-width: 767px){
#id_filter .col-md-6 {
    width: 100%;
    margin-top: 10px;
}
#id_filter_css .col-md-8 {
    flex-wrap: wrap;
} }
</style>

<section class="category-img1 section-t-padding section-b-padding section_q2vymy" style="padding-bottom: 20px">
    <div class="container">
        <div class="row" id="id_filter_css">

                <div class="col-md-4">
                    <div class="header-main-area div_j18kep">
                        <div class="header-main">
                            <div class="header-element div_xgmv7r position-relative w-100 " x-data="{ show: false}" @click.away="show = false">
                                <button
                                    @click = "show = ! show"
                                    class="bg-light px-3 py-3 rounded-pill d-flex justify-content-between align-items-baseline w-100 text-gray-400 ">

                                    Kategoria: {{ $category->name }}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" fill="currentColor" class="bi bi-chevron-down d-inline-flex" viewBox="0 0 16 16" id="IconChangeColor"> <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" id="mainIconPathAttribute" stroke-width="2" stroke="#000000"></path> </svg>
                                </button>
                                <div x-show="show" class="position-absolute bg-white rounded-md w-100 lh-lg mt-1 border-gray-300" style="display: none; z-index: 1000">
                                    @foreach($categories as $item)
                                        <a class="a-hover d-flex text-lg-left px-3 text-gray-400
                                          @if($loop->first == true)
                                            rounded-top-md
                                         @endif
                                         @if($loop->last == true)
                                            rounded-bottom-md
                                         @endif
                                         @if($item->name === $category->name)
                                         new
                                         @endif
                                        " href="/{{$categoryPath.'-'.$item->slug.','.$item->category_index}}/{{$placeSlug}}">{{$item->name}}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <form class="col-md-8 d-flex justify-content-around align-items-baseline" id="id_filter">

            <input type="hidden" value="{{$route}}" name="route">
            <input type="hidden" value="{{$category->category_index}}" name="category">
            <div class="col-md-6">
                <div class="header-main-area div_k6ou7a">
                    <div class="header-main">
                        <div class="header-element search-wrap div_kdhdcb">
                            <select name="sort" class="select" id="id_sort">
                                @if($route == 'leafletLocal' or $route == 'leaflet')
                                <option value="0">Sortuj wg: Najnowsze</option>
                                <option value="1">Sortuj wg: Nadchodzące</option>
                                <option value="2">Sortuj wg: Kończące się</option>
                                @endif
                                @if($route == 'chainLocal' or $route == 'chain')
                                        <option value="3">Sortuj wg: A - Z</option>
                                        <option value="4">Sortuj wg: Z - A</option>
                                        <option value="5">Sortuj wg: Popularne</option>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
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

    </div>
</section>
