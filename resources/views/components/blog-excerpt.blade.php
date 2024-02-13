
@foreach($blogs as $blog)

    @if($loop->iteration == 1 || $loop->iteration == 5)
        <div class="col-6 mt-3x fixed-height"><img src="{{asset('assets/image/pro/mujercest.png')}}" class="w-100"></div>
        <div class="col-6 mt-3x @if($loop->iteration == 1) big-blue-user @else big-bluew big-bluew-gap @endif fixed-height">
            <span class="white-cont"><a href="/abc-zakupowicza/{{$blog->categories->slug}}" class="white-cont">{{$blog->categories->name}}</a></span>
            <span class="white-cont">{{date('d.m.Y', strtotime($blog->created_at))}}</span>
            <h2 class="mt-3 deepTittle
                    @if($loop->iteration == 5)
                    black
                    @endif
                    ">{{$blog->title}}</h2>
            <p class="whiteParagraph">{{$blog->excerpt}}</p>
            <div class="author mt-20"><img class="person-round" src="{{asset('assets/image/pro/dave.png')}}"><span class="white-cont ">Jan Kowalski</span>
            </div>
        </div>
    @else
        <div class="col-3x mt-3"><img class="imageq" src="{{asset('assets/image/pro/mercao.png')}}">
            <p class="fast-p"><a href="/abc-zakupowicza/{{$blog->categories->slug}}" >{{$blog->categories->name}}</a></p>
            <p class="fast-p">{{date('d.m.Y', strtotime($blog->created_at))}}</p>
            <h2 class="card-title">{{$blog->title}}</h2><span class="paragraphCard">{{$blog->excerpt}}</span>
            <div class="author blackness mt-20"><img class="person-round" src="{{asset('assets/image/pro/dave.png')}}"><span
                    class="white-cont ">Jan Kowalski</span></div>
        </div>
    @endif
@endforeach
