<div id="leafletCanvas" class="flex flex-col leaflet-page-rtveuroagd bg-rtveuroagd relative">
    <div class="w-full h-full flex flex-col bg-gray-700">
        <div class="flex h-fit">
            <img src="{{asset('assets/image/templates/rtveuroagd-header.png')}}">
        </div>
        <div class="flex-col flex h-full m-1 border-4 border-yellow-300 rounded-xl bg-white">
            <div class="flex relative pt-16 pl-36">
                <img class="flex max-w-72" src="@if($image !== ''){{asset($image)}}@endif">
                <div class="absolute flex justify-center left-0 top-0 w-full">
                    <h2 class="font-sans text-black-600 text-xl font-bold text-center pt-0 m-0 leading-none" id="h2_title" >@if($data!== ''){{$data['h1Tag']}}@endif</h2>
                </div>
                <div class="absolute flex flex-col left-2 w-40 leading-none gap-2 pt-1">
                    @if($data!== '')
                        @foreach(array_slice($data['attributes'], 0, 9) as $item)
                            <span class="span">{{$item['name']}}: {{$item['values']}}</span>
                        @endforeach
                    @endif
                </div>

            </div>
            <div class="absolute bottom-14 left-6 bg-yellow-400 h-24 w-52 rounded-xl flex flex-wrap justify-center content-center border-2 border-red-500">
                <span class="text-red-600 text-center text-5xl font-bold price-rtveuroagd">@if($data!== ''){{$data['priceWhole']}}@endif</span>
                <span class="text-red-600 text-center ml-3 price-rtveuroagd">@if($data!== ''){{$data['priceRest']}}@endif</span>
                <span class="text-xs price-rtveuroagd">@if($data!== '' and $data['promoCode'] !== null)Kod do oferty: {{$data['promoCode']}}@endif</span>
            </div>
            <div class="absolute bottom-14 right-6 bg-yellow-400 h-16 w-40 rounded-xl flex flex-wrap justify-center content-center border-2 border-red-500">
                <span class="text-red-600 text-center font-bold text-2xl price-rtveuroagd">KUP ONLINE</span>
            </div>
            <div class="absolute bottom-0 left-0 w-full flex flex-col">
                @if($data!== '')
                    @if($data['prices'] !== null)
                    @foreach($data['prices'] as $item)
                        <span class="pl-2 pt-2 pb-2 flex flex-wrap w-full text-gray-700 leading-3 ">{{$item['price']}}: {{$item['label']}}</span>
                    @endforeach
                    @endif
                @endif

            </div>
        </div>
    </div>
</div>
