<section class="faqs-area desktop-only section_jhcr19">
    @foreach($items as $item)
        @if($loop->odd)
            <div class="row div_db2b8n">
                <div class="col">
                    <div class="faq-box">
                        <div class="col-md-6"><img src="{{asset($item->image_path)}}"
                                           class="pd-40 img-fluid img_74a672"></div>
                        <div class="col-md-6 pd-set">
                            <div class="w-40">
                                <img src="{{asset('assets/image/pro/0'.$loop->iteration.'.png')}}"
                                     class="img_hadxdm"><span class="span_geepny">0{{$loop->iteration}}</span>
                                <h2 class="f-size h2_17fop3"><span>{{$item->header}}</span></h2>
                                {!! $item->body !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($loop->even)
            <div class="row mt-4 div_828je9">
                <div class="col">
                    <div class="faq-box">
                        <div class="col-md-6 pd-set">
                            <div class="w-40 fl-right">
                                <img src="{{asset('assets/image/pro/0'.$loop->iteration.'.png')}}"
                                     class="img_ghar88"><span class="span_tn8h4r">0{{$loop->iteration}}</span>
                                <h2 class="f-size h2_3ouk4y"><span>{{$item->header}}</span></h2>
                                {!! $item->body !!}
                                </div>
                        </div>
                        <div class="col-md-6"><img src="{{asset($item->image_path)}}"
                                                   class="pd-40 img-fluid img_7tnry5"></div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</section>
<section class="faqs-area mob-only section_bnbwfx">
    @foreach($items as $item)
        @if($loop->odd)
    <div class="row div_gl5p64">
        <div class="col">
            <div class="faq-box">
                <div class="col-md-6">
                    <div class="col-md-6"><img src="{{asset($item->image_path)}}"
                         class="pd-40 img-fluid img_44zeti">
                </div>
                <div class="col-md-6 pd-set">
                    <div class="w-40">
                        <img src="{{asset('assets/image/pro/0'.$loop->iteration.'.png')}}"
                             class="img_ghar88"><span class="span_tn8h4r">0{{$loop->iteration}}</span>
                        <h2 class="f-size h2_xboewg">
                        <span>{{$item->header}}</span>
                        </h2>
                        {!! $item->body !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
        @elseif($loop->even)

    <div class="row mt-4 div_lvm1m7">
        <div class="col">
            <div class="faq-box">
                <div class="col-md-6">
                    <img src="{{asset($item->image_path)}}"
                         class="pd-40 img-fluid img_c01pkh">
                </div>
                <div class="col-md-6 pd-set">
                    <div class="w-40 fl-right">
                        <img src="{{asset('assets/image/pro/0'.$loop->iteration.'.png')}}" class="img_ziuv6j">
                        <span class="span_xwjpdm">0{{$loop->iteration}}</span>
                        <h2 class="f-size h2_3nlq6n">
              <span>{{$item->header}}</span></h2>
                        {!! $item->body !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
        @endif
    @endforeach
</section>
