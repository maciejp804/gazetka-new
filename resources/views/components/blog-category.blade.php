<div class="col-12 d-flex space-between">
    <div class="tagged @if(empty($slug)) active @endif ">
        <a href="/abc-zakupowicza/"><span>Wszystkie</span></a></div>
    @foreach($items as $item)
        <div class="tagged @if(isset($slug)) @if ($item->slug === $slug) active @endif @endif">
            <a href="/abc-zakupowicza/{{$item->slug}}" ><span>{{$item->name}} ({{$item->articles}})</span></a></div>
    @endforeach
</div>
