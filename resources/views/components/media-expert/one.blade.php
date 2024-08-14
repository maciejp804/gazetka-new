<div id="leafletCanvas" class="flex flex-col leaflet-page-media-expert bg-media-expert relative">
    <div>
        <img src="{{asset('assets/image/templates/mediaexpert-header-01.png')}}">
        <div class="flex relative pt-16 pl-8">
            <img class="flex max-w-72" src="@if($data!== ''){{asset('assets/image/templates/mediaexpert-1.png')}}@endif">
            <div class="absolute flex justify-center left-0 top-0 w-full">
                <h2 class="font-sans text-black-600 text-xl font-bold text-center pt-0 m-0 leading-none" id="h2_title" >@if($data!== ''){{$data['h1Tag']}}@endif</h2>
            </div>
            <div class="absolute flex flex-col right-0 w-40 leading-none gap-2 pt-1">
                @if($data!== '')
                @foreach($data['attributes'] as $item)
                    <span class="span">{{$item['name']}}: {{$item['values']}}</span>
                @endforeach
                @endif
            </div>

        </div>
        <div class="absolute bottom-16 right-6 bg-white h-24 w-52 rounded-xl flex flex-wrap justify-center content-center border-2 border-red-500">
            <span class="text-red-600 text-center font-bold text-6xl price"> @if($data!== ''){{$data['priceWhole']}}@endif</span>
            <span class="text-red-600 text-center font-bold ml-3 price"> @if($data!== ''){{$data['priceRest']}}@endif</span>
            <span class="text-xs">@if($data!== '' and $data['promoCode'] !== null)Cena z kodem: {{$data['promoCode']}}@endif</span>
        </div>
        <div class="absolute bottom-16 left-6 bg-white h-16 w-32 rounded-xl flex flex-wrap justify-center content-center border-2 border-red-500">
            <span class="text-red-600 text-center font-bold text-2xl price">KUP ONLINE</span>
        </div>
        <div class="absolute bottom-0 left-0 w-full flex flex-col">
            @if($data!== '')
                @foreach($data['prices'] as $item)
                    <span class="pl-2 pt-2 pb-2 flex flex-wrap w-full text-white bg-red-600 leading-3 ">{{$item['price']}}: {{$item['label']}}</span>
                @endforeach
            @endif

        </div>
    </div>

</div>
