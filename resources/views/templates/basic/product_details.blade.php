@extends($activeTemplate .'layouts.master')

@section('content')

<!-- Product Single Section Starts Here -->
<div class="category-section oh argo-details">
    <div class="container">
        <div class="row product-details-wrapper argo-details-content">
            <div class="col-lg-5 variant-images">
                @if(@$product->offer['activeOffer'])
                                                        @if(@$product->offer['activeOffer']['discount_type'] == 2)
                                                            <span class="text-white bg-danger tag-discount"> -{{@$product->offer['activeOffer']['amount']}}% </span>
                                                        @else 
                                                            <span class="text-white bg-danger tag-discount"> -{{@$product->offer['activeOffer']['amount']}}$ </span>
                                                        @endif
                                                    @endif
                <div class="sync1 owl-carousel owl-theme">
                    @if($images->count() == 0)
                    <div class="thumbs">
                        <img class="zoom_img"
                            src="{{ getImage(imagePath()['product']['path'].'/'.@$product->main_image, imagePath()['product']['size']) }}"
                            alt="@lang('products-details')">
                    </div>
                    @else
                    @foreach ($images as $item)

                    <div class="thumbs">
                        <img class="zoom_img"
                            src="{{ getImage(imagePath()['product']['path'].'/'.@$item->image, imagePath()['product']['size']) }}"
                            alt="@lang('products-details')">
                    </div>
                    @endforeach
                    @endif
                </div>

                <div class="sync2 owl-carousel owl-theme mt-2">
                    @if($images->count() > 1)
                    @foreach ($images as $item)
                    <div class="thumbs">
                        <img src="{{ getImage(imagePath()['product']['path'].'/thumb_'.@$item->image, imagePath()['product']['size']) }}"
                            alt="@lang('products-details')">
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>


            <div class="col-lg-7">
                <div class="product-details-content product-details">
                    <h4 class="title">{{__($product->name)}}</h4>
                    <p>MARCA: {{ $product->brand ? $product->brand->name : 'No definida'}}</p>
                    <p>CÓDIGO: {{$product->internal_code}}</p>
                    <p>CÓDIGO OEM: {{$product->oem_code}}</p>
                    <div class="row my-3">
                        <div class="col-3">

                            <div class="price" style="font-size:24px">
                                @php
                                    $rate = session()->get('rate');
                                    $moneda = session()->get('moneda');
                                @endphp
                                @if ($moneda == 'Dolares' || $moneda == '')
                                    @if ($discount > 0)
                                        {{ $general->cur_sym }}{{ getAmount($product->precioBaseIva - $discount, 2) }}
                                        <del>{{ getAmount($product->precioBaseIva, 2) }}</del>
                                        @if ($product->prime_price > 0)
                                            <br>
                                            Premium:
                                            {{ $general->cur_sym }}{{ getAmount($product->precioPrimeIva ?? $product->prime_price, 2) }}
                                        @endif
                                    @else
                                        {{ $general->cur_sym }}{{ getAmount($product->precioBaseIva, 2) }}
                                        @if (isset($item) && $product->prime_price > 0 && $product->precioBaseIva !== $product->precioPrimeIva)
                                            <br>
                                            Premium:
                                            {{ $general->cur_sym }}{{ getAmount($product->precioPrimeIva ?? $product->prime_price, 2) }}
                                        @endif
                                    @endif
                                @else
                                    @if ($discount > 0)
                                        {{ $moneda == 'Euros' ? '€. ' : 'Bs. ' }}{{ getAmount($product->precioBaseIva - $discount * $rate, 2) }}
                                        <del>{{ getAmount($product->precioBaseIva * $rate, 2) }}</del>
                                        @if ($product->prime_price > 0)
                                            <br>
                                            Premium:
                                            {{ $moneda == 'Euros' ? '€. ' : 'Bs. ' }}{{ getAmount($product->precioPrimeIva ?? $product->prime_price * $rate, 2) }}
                                        @endif
                                    @else
                                        {{ $moneda == 'Euros' ? '€. ' : 'Bs. ' }}{{ getAmount($product->precioBaseIva * $rate, 2) }}
                                        @if ($product->prime_price > 0)
                                            <br>
                                            Premium:
                                            {{ $moneda == 'Euros' ? '€. ' : 'Bs. ' }}{{ getAmount($product->precioPrimeIva ?? $product->prime_price * $rate, 2) }}
                                        @endif
                                    @endif
                                @endif
                            </div>
                            <p style="font-size:12px">{{ $product->iva==1 ? 'IVA incluido' : 'Exento'}}</p>
                        </div>
                        <div class="col-5 d-flex align-items-center">
                            <div class="container">                                
                                <div class="row">
                                    <div class="col-12 my-3">   
                                        <!-- 
                                        <div class="ratings-area justify-content-between">
                                            <div class="ratings">
                                                @php echo __(display_avg_rating($product->reviews)) @endphp
                                            </div>
                                            <span class="ml-2 mr-auto">({{__($product->reviews->count())}})</span>
                                        </div> -->
                                        @if($product->show_in_frontend && $product->track_inventory)
                                        @php $quantity = $product->stocks->sum('quantity'); @endphp
                                            <div class="badge badge--{{$quantity>0?'success':'danger'}} stock-status d-block">Existencias (<span
                                                class="stock-qty">{{$quantity}}</span>)
                                            </div>
                                        @endif 
                                    </div>                       
                                </div>                       
                                <div class="row">                           
                                    <div class="col-6">
                                        <select onchange="QuantityValue(this.value,'{{ $product->id }}')" type="number"
                                            id="quantity{{ $product->id }}" name="quantity" step="1" min="1"
                                            class="integer-validation quantity{{ $product->id }} form-control">
                                            @if($quantity > 0)
                                                @for ($i = 1; $i < $quantity+1; $i++)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <div class="price-btn">
                                            <div class="add-cart">
                                                <button type="submit" class="cmn-btn cart-add-btn"
                                                    data-id="{{ $product->id }}">Agregar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="xdes-argo">
                        <div class="description-item">
                            @if($product->description)
                            <p>
                                @lang($product->description)
                            </p>

                            @else
                            <div class="alert cl-title alert--base" role="alert">
                                No Hay Descripción Para Este Producto
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    





                    <p>
                        @php echo __($product->summary) @endphp
                    </p>

                    @forelse ($attributes as $attr)

                    @php $attr_data = getProuductAttributes($product->id, $attr->product_attribute_id); @endphp
                    @if($attr->productAttribute->type==1)
                    <div class="product-size-area attr-area">
                        <span class="caption">{{ __($attr->productAttribute->name_for_user) }}</span>
                        @foreach ($attr_data as $data)
                        <div class="product-single-size attribute-btn" data-type="1" data-discount={{$discount}}
                            data-ti="{{$product->track_inventory}}" data-attr_count="{{$attributes->count()}}"
                            data-id="{{$data->id}}" data-product_id="{{$product->id}}"
                            data-price="{{$data->extra_price}}" data-base_price="{{ $product->precioBaseIva}}">
                            {{$data->value}}</div>
                        @endforeach
                    </div>
                    @endif
                    @if($attr->productAttribute->type==2)
                    <div class="product-color-area attr-area">
                        <span class="caption">{{__($attr->productAttribute->name_for_user)}}</span>
                        @foreach ($attr_data as $data)
                        <div class="product-single-color attribute-btn" data-type="2"
                            data-ti="{{$product->track_inventory}}" data-discount={{$discount}}
                            data-attr_count="{{$attributes->count()}}" data-id="{{$data->id}}"
                            data-product_id="{{$product->id}}" data-bg="{{$data->value}}"
                            data-price="{{$data->extra_price}}" data-base_price="{{ $product->precioBaseIva}}"></div>
                        @endforeach
                    </div>

                    @endif
                    @if($attr->productAttribute->type==3)
                    <div class="product-color-area attr-area">
                        <span class="caption">{{__($attr->productAttribute->name_for_user)}}</span>
                        @foreach ($attr_data as $data)
                        <div class="product-single-color attribute-btn bg_img" data-type="3"
                            data-ti="{{$product->track_inventory}}" data-discount={{$discount}}
                            data-attr_count="{{$attributes->count()}}" data-id="{{$data->id}}"
                            data-product_id="{{$product->id}}" data-price="{{$data->extra_price}}"
                            data-base_price="{{ $product->precioBaseIva}}"
                            data-background="{{ getImage(imagePath()['attribute']['path'].'/'. @$data->value) }}">
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @endforeach

                    <div class="cart-and-coupon mt-3">

                        <div class="attr-data">
                        </div>

                        <div class="cart-plus-minus quantity ">
                            <!-- <div class="cart-decrease qtybutton dec">
                                <i class="las la-minus"></i>
                            </div> 

                            <select onchange="QuantityValue(this.value,'{{ $product->id }}')" type="number"
                                id="quantity{{ $product->id }}" name="quantity" step="1" min="1"
                                class="integer-validation quantity{{ $product->id }} form-control">
                                @if($quantity > 0)
                                @for ($i = 1; $i < $quantity+1; $i++) <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                    @endif
                            </select>-->

                            <!--                             <div class="cart-increase qtybutton inc">
                                <i class="las la-plus"></i>
                            </div> -->
                        </div>

                    </div>

                    <div>
                        <p class="c-link">

                            @lang('Categories'):

                            @foreach ($product->categories as $category)
                            <a
                                href="{{ route('products.category', ['id'=>$category->id, 'slug'=>slug($category->name)]) }}">{{
                                __($category->name) }}</a>
                            @if(!$loop->last)
                            /
                            @endif
                            @endforeach
                        </p>
                        {{--
                        <!--         <p>
                            <b>@lang('Model'):</b> {{ __($product->model) }}
                        </p>
                        <p>
                            <b>@lang('Brand'):</b> {{ __($product->brand->name) }}
                        </p>

                        <p>
                            <b>@lang('SKU'):</b> <span class="product-sku">{{$product->sku??__('Not Available')}}</span>
                        </p>

                        <p class="product-share">
                            <b>@lang('Share'):</b>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" title="@lang('Facebook')">

                                <i class="fab fa-facebook"></i>
                            </a>

                            <a href="http://pinterest.com/pin/create/button/?url={{urlencode(url()->current()) }}&description={{ __($product->name) }}&media={{ getImage('assets/images/product/'. @$product->main_image) }}" title="@lang('Pinterest')">

                                <i class="fab fa-pinterest-p"></i>
                            </a>

                            <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{urlencode(url()->current()) }}&amp;title=my share text&amp;summary=dit is de linkedin summary" title="@lang('Linkedin')">

                                <i class="fab fa-linkedin"></i>
                            </a>

                            <a href="https://twitter.com/intent/tweet?text={{ __($product->name) }}%0A{{ url()->current() }}" title="@lang('Twitter')">

                                <i class="fab fa-twitter"></i>
                            </a>
                        </p>
                        @php
                            $wCk = checkWishList($product->id);
                        @endphp
                        <p class="product-details-wishlist">
                            <b>@lang('Add To Wishlist'): </b>
                            <a href="javascript:void(0)" title="@lang('Add To Wishlist')" class="add-to-wish-list {{$wCk?'active':''}}" data-id="{{$product->id}}"><span class="wish-icon"></span></a>
                        </p> -->
                        --}}
                        @if($product->meta_keywords)
                        <p>
                            <b>
                                @lang('Tags'):
                            </b>
                            @foreach ($product->meta_keywords as $tag)
                            <a href="">{{ __($tag) }}</a>@if(!$loop->last),@endif
                            @endforeach
                        </p>
                        @endif
                        
                    </div>

                </div>
            </div>
        </div>
        <div class="row" style="background-color:#fff; border-radius:15px">
            @if(isset($product->extra_descriptions))
                <div class=" col-md-8 col-12 py-3">
                    <div class="container">
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach ($product->extra_descriptions as $key => $extra)
                                
                            <li class="nav-item">
                                <a class="nav-link @if ($key===1)
                                    active
                                @endif" data-toggle="tab" href="#tab-{{$key}}">{!! $extra['key'] !!}</a>
                            </li>

                                
                            @endforeach
                        </ul>

                        <div class="tab-content">
                        @foreach ($product->extra_descriptions as $key => $extra )
                            <div id="tab-{{$key}}" class="container tab-pane @if ($key === 1)
                                active
                            @endif">
                                <p>{!! $extra['value'] !!}</p>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            @endif
            @if(isset($product->specification))
                <div class="col-md-4 col-12 py-3">
                    @foreach ($product->specification as $key => $features)
                        <div class="accordion" id="features">
                            <div class="card">
                                <div class="card-header">

                                    <a class="collapsed d-block card-link" data-toggle="collapse" href="#feature-{{$key}}">
                                        {{ $features['name'] }}
                                    </a>
                                </div>
                                <div id="feature-{{$key}}" class="collapse" data-parent="#features">
                                    <div class="card-body p-2">
                                        {!! $features['value'] !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
<!-- Product Single Section Ends Here -->

<!-- Product Single Section Starts Here -->



<div class="shop-category-container">
    <div class="shop-category-products">      
        <div class="container-fluid">
            <h3 class="carrusel-titulo">
                <span> @lang('Related Products') </span>
                
            </h3>
        </div>
        <div class="shop-category-products">
            <div class="product-slider-2 owl-carousel owl-theme">
                @foreach ($related_products as $item)
                    @php $quantity = $item['stocks']->count() > 0 ? $item['stocks'][0]->quantity : 0 @endphp

                    @php
                        if($item->offer && $item->offer->activeOffer){
                            $discount = calculateDiscount($item->offer->activeOffer->amount, $item->offer->activeOffer->discount_type, $item->base_price);
                        }else $discount = 0;
                        $wCk = checkWishList($item->id);
                        $cCk = checkCompareList($item->id);
                    @endphp
                     <div class="item" style="padding-bottom: 10px;">
                        <div class="item-prod" id="app-{{$item->id}}" style="margin:0px !important;">
                            <div class="item-bord">                                
                                <a href="{{route('product.detail', ['id'=>$item->id, 'slug'=>slug($item->name)])}}">
                                    <div class="item-img">
                                        @if (isset($item->offer))
                                            @if ($item->offer['activeOffer'])
                                                @if ($item->offer['activeOffer']['discount_type'] == 2)
                                                    <span class="text-white bg-danger tag-discount discount-products"> -{{$item->offer['activeOffer']['amount']}}% </span>
                                                @else 
                                                    <span class="text-white bg-danger tag-discount discount-products"> -{{$item->offer['activeOffer']['amount']}}$ </span>
                                                @endif
                                            @endif
                                        @endif
                                        <img src="{{ getImage(imagePath()['product']['path'].'/thumb_'.@$item->main_image, imagePath()['product']['size']) }}" alt="@lang('flash')" class="img-prin img-fluid">
                                    </div>
                                </a> 
                                <div class="item-descp">
                                        
                                        <span class="screenReaderOnlyText"></span>                                                    
                                        <h3 class="item-nomb">                                                         
                                            <a href="{{route('product.detail', ['id'=>$item->id, 'slug'=>slug($item->name)])}}" class="mr-2 mb-2" style="font-size:13px">{{ __($item->name) }}</a>
                                            
                                        </h3>
                                        <p><span class="item-disp stock-argo">({{ $item['stocks']->count() > 0 ? $item['stocks'][0]->quantity : '0' }} @lang('product avaliable') )</span></p>
                                        <p style="font-size:12px" class="producto-brand">
                                            <span data-automation-id="brand">Marca:</span> 
                                            <span style="color:black">{{ $item->brand ? $item->brand->name : 'No definida'}}</span>
                                        </p>
                                        <p style="font-size:12px" class="producto-cod_int">
                                            <span data-automation-id="cod_int">Codigo:</span>
                                            <span style="color:black">{{ $item->internal_code }}</span>
                                        </p>
                                        {{-- <p class="producto-categ">
                                            @if(isset($item['categories']) && ($item['categories']->count() > 0 ) ) 
                                                @foreach($item['categories'] as $category)
                                                <a href="{{ route('products.category', ['id'=>$category->id, 'slug'=>slug($category->name)]) }}">{{ __($category->name) }}</a>
                                                    @if(!$loop->last)
                                                    /
                                                    @endif                                 
                                                @endforeach
                                            @endif
                                        </p> --}}
                                        <p class="producto-categ">
                                            <span style="font-size:10px" data-automation-id="price-per-unit">{{ $item->iva == 1 ? 'IVA Incluido' : 'Exento'}}</span>
                                        </p>
                                </div> 
                                <div style="display: none;" class="item-prod-argo badgeProduct{{$item->id}}"></div>
                                <div class="item-final">
                                    <div class="prec-area">
                                        <span class="prec-vent">
                                            @php
                                                $rate = session()->get('rate');
                                                $moneda = session()->get('moneda');
                                            @endphp
                                            <span>
                                            @if($moneda=='Dolares' || $moneda == '')
                                                @if($discount > 0)
                                                {{ $general->cur_sym }}{{ getAmount($item->precioBaseIva - $discount, 2)}}
                                                <del>{{ getAmount($item->precioBaseIva, 2) }}</del>
                                                    @if($item->prime_price > 0)
                                                        <br>
                                                        Premium: {{ $general->cur_sym }}{{ getAmount($item->precioPrimeIva??$item->prime_price, 2) }}
                                                    @endif 
                                                @else
                                                {{ $general->cur_sym }}{{ getAmount($item->precioBaseIva, 2) }}
                                                    @if($item->prime_price > 0)
                                                        <br>
                                                        Premium: {{ $general->cur_sym }}{{ getAmount($item->precioPrimeIva??$item->prime_price, 2) }}
                                                    @endif 
                                                @endif
                                            @else 
                                                @if($discount > 0)
                                                {{ $moneda == 'Euros' ? '€. ' : 'Bs. ' }}{{ getAmount($item->precioBaseIva - $discount * $rate, 2) }}
                                                <del>{{ getAmount($item->precioBaseIva * $rate, 2) }}</del>
                                                    @if($item->prime_price > 0)
                                                        <br>
                                                        Premium: {{ $moneda == 'Euros' ? '€. ' : 'Bs. ' }}{{ getAmount($item->precioPrimeIva??$item->prime_price * $rate, 2) }}
                                                    @endif 
                                                @else
                                                {{ $moneda == 'Euros' ? '€. ' : 'Bs. ' }}{{ getAmount($item->precioBaseIva * $rate, 2) }}
                                                    @if($item->prime_price > 0)
                                                        <br>
                                                        Premium: {{ $moneda == 'Euros' ? '€. ' : 'Bs. ' }}{{ getAmount($item->precioPrimeIva??$item->prime_price * $rate, 2) }}
                                                    @endif 
                                                @endif
                                            @endif
                                            </span>
                                        </span>
                                    </div> 
                                    <div class="btn-area">
                                                    
                                        <button @click="isShow = true" type="submit" class="cmn-btn-argo-item cart-add-btn showProduct{{ $item['id'] }}" data-id="{{ $item['id'] }}">@lang('Agregar')</button>
                                        
                                        <div class="cart-plus-minus quantity">
                                            {{--<div class="cart-decrease qtybutton dec">
                                                <i class="las la-minus"></i>
                                            </div>
                                            <select style="display: none;width: 80px;height: 40px;" 
                                            onchange="QuantityValue(this.value,'{{ $item->id }}')" 
                                            type="number" id="quantity{{ $item['id'] }}" name="quantity" step="1" min="1" class="custom-select integer-validation quantity{{ $item['id'] }} form-control">
                                                @if($quantity > 0)
                                                @for ($i = 1; $i < $quantity+1; $i++)
                                                <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                                @endif
                                            </select>--}}
                                            {{--<div class="cart-increase qtybutton inc">
                                                <i class="las la-plus"></i>
                                            </div>--}}
                                        </div> 

                                        <form style="display: none;"  novalidate="" name="formSelect" class="ng-pristine ng-valid ng-touched quantity{{ $item['id'] }}">
                                            <span class="ng-star-inserted" style="">
                                            <i class="fas fa-check"></i>&nbsp;Agregado</span><!---->
                                            @if(!$item->usa_gramaje)
                                                            <select onchange="QuantityValue(this.value,'{{ $item->id }}')" formcontrolname="cantidad" class="custom-select" style="" id="quantity{{ $item['id'] }}" name="quantity">
                                                            @if($quantity > 0)
                                                            @for ($i = 1; $i < $quantity+1; $i++)
                                                            <option value="{{$i}}">{{$i}}</option>
                                                            @endfor
                                                            @endif
                                                            </select>
                                                        @else        
                                                            <div class="container-fluid">
                                                                <div class="row justify-content-center">
                                                                    <div class="col-3" style="padding:0">
                                                                        
                                                                        <button 
                                                                        class="cmn-btn-argo-item btn btn-sm" 
                                                                        style="margin:5px auto;padding:.375rem 0"
                                                                        onclick="addAndSubstract('{{ $item->id }}','-'); return false;"> <i class="fa fa-minus"></i> </button>
                                                                        
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <input 
                                                                            class="form-control my-1 gramaje" 
                                                                            value="1" 
                                                                            type="number" 
                                                                            readonly 
                                                                            onblur="QuantityValue(this.value, '{{ $item->id }}')" 
                                                                            formcontrolname="cantidad"
                                                                            id="quantity{{ $item['id'] }}" 
                                                                            name="quantity"
                                                                            style="text-align:right">
                                                                    </div>
                                                                    <div class="col-3" style="padding:0">
                                                                        <button 
                                                                            class="cmn-btn-argo-item btn btn-sm" 
                                                                            style="margin:5px auto;padding:.375rem 0"
                                                                            onclick="addAndSubstract('{{ $item->id }}','+'); return false;"> <i class="fa fa-plus"></i> </button>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        @endif
                                        </form>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @push('vue')
                        <script>
                            var app3 = new Vue({
                                el: '#app-{{$item->id}}',
                                data: {
                                    BackTheme: null,
                                    bagde: 1,
                                    isHidden: true,
                                    isShow: false
                                }
                            });
                        </script>
                    @endpush
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    'use strict';
    (function($){
        var pid = '{{ $product->id }}';
        load_data(pid);
        function load_data(pid, url="{{ route('product_review.load_more') }}") {
            $.ajax({
                url: url,
                method: "GET",
                data: { pid: pid },
                success: function (data) {
                    $('#load_more_button').remove();
                    $('.review-area').append(data);
                }
            });
        }
        $(document).on('click', '#load_more_button', function () {
            var id  = $(this).data('id');
            var url = $(this).data('url');
            $('#load_more_button').html(`<b>{{ __('Loading') }} <i class="fa fa-spinner fa-spin"></i> </b>`);
            load_data(pid, url);
        });

    })(jQuery)

</script>
@endpush

@push('breadcrumb-plugins')
<li><a href="{{route('home')}}">@lang('Home')</a></li>
@if( (isset($category_url) && !is_null($category_url)) && !is_null($category_name))
    <li><a href="{{ $category_url }}">{{ $category_name }}</a></li>
@endif
@endpush


@push('meta-tags')
@include('partials.seo', ['seo_contents'=>@$seo_contents])
@endpush