<section class="faq-collapse section-b-padding section_hkkx89 mt-5">
    <div class="container">
        <div class="row">
            <div class="section-title3 text-center mb-4">
                <h2 class="h2_51ko79">FAQ - Najczęściej zadawane pytania</h2>
                <span class="span_a2363d"></span>
            </div>
            <div class="col-md-9 w-set-9 div_6d8h4y" id="accordionExample">
                @foreach($items as $item)
                <div class="faq-start">
                    <a href="" data-toggle="collapse" data-target="#collapse-{{$loop->iteration}}" aria-expanded="true"
                       aria-controls="collapse-1"
                       class="collapse-title">
                        <img src="{{asset('assets/image/pro/icon.png')}}">{{$item->question}} <img
                            src="{{asset('assets/image/pro/arrow-point-to-right 1.png')}}" class="img_kkjo9t">
                    </a>
                    <div class="collapse collapse-content" id="collapse-{{$loop->iteration}}" data-parent="#accordionExample">
                        {!! $item->answer !!}
                    </div>
                </div>
                @endforeach
            </div>
            <div class="col-md-2">
                <div class="service-box box-set-bottom image_containers">
                    <div class="s-box text-center">
                        <div>
                            <img src="{{asset('assets/image/pro/ask 1.png')}}" class="img-fluid" alt="image">
                        </div>
                        <div class="service-content div_99kwqe">
              <span>
                <b>Masz pytania?</b>
              </span>
                            <span class="span_7tp37v">Napisz lub zadzwoń do nas</span>
                            <div class="all-blog text-center mt-4">
                                <a href="/contact/" class="btn btn-style1 a_q2wev">Zobacz więcej</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
