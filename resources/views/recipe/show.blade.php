@extends('layouts.app')

@section('content')
<section class="container">
    <div class="row">
        <div class="col-12">
        <img class="image col-md-12 img-fluid float-left"  src="{{ asset($recipe->big_image) }}">
        <i class="fas fa-heart fa-2x" style="@if(count($recipe->liked()) == 1) color:red; @else color: grey; @endif" data-recipe="{{$recipe->id}}" data-url="{{route('recipe.like', ['recipe' => $recipe])}}"></i>
            <!-- add like option if auth-->
            <div class="col-md-3"></div><!-- with units and quantity-->
            <div class="starts col-md-9" data_recipe="{{$recipe->id}}" data_href="{{ route('opinion.add', ['recipe' => $recipe])}}">

                @for($i = 0; $i < 5; $i++)
                    <i id="star{{($i+1)}}" class="@if(isset($opinion->opinion) && $opinion->opinion-- > 0)fas @else far @endif fa-star fa-2x text-warning"></i>
                    
                @endfor
            </div><div class="col-3"></div>
            <div class="ingredients col-12">
            @foreach ($recipe->products as $recipe_product)
                <p>{{ ucfirst($recipe_product->name) }}  :  {{ $recipe_product->pivot->quantity }} {{ $recipe_product->unit->unit}}</p>
            @endforeach
            </div>
            <div class="description col-12">{{ $recipe->description }}jhmghmghjmgj,mgj,tgjh,tgju,tgujh,tuj,tyu,dtyuj,u,dtyu,y,y,yu,yru,yu,yru,yu,yju,y</div>
            <div class="comments col-md-12">
            @comments(['model' => $recipe,
                        'perPage' => 10])
            </div> 
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('js/likes.js')}}" defer></script>
<script>
$(document).ready(function() {
    $('.fa-star').click(function() {
        let url = $(this)[0].parentElement.attributes['data_href'].value;
        let value = $(this).attr('id').slice(-1);
        let id = $(this)[0].parentElement.attributes['data_recipe'].value;
        bootbox.confirm({
            title: 'Dodawanie opinii',
            message: `<div class="modal-icon"><i class="fas fa-plus-circle mr-2"></i><span>Czy chcesz dodać jednorazową opinie do tego przepisu w postaci ${$(this).attr('id').slice(-1)} gwiazdek? Uwaga opinia nie będzie podlegała zmianie!</span></div>`,
            buttons: {
                confirm: {
                    label: `<i class="fa fa-check mr-1"></i> Dodaj opinię`,
                    className: 'btn-danger',
                },
                cancel: {
                    label: `<i class="fa fa-times mr-1"></i> Zamknij`,
                    className: 'btn-success',
                },
            },
            callback: function(confirm) {
                if( confirm ) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: 'stars='+ value,
                        success: function(result) {
                            bootbox.alert({
                                title: 'Dodano opinię!',
                                message: `<div class="modal-icon"><i class="fa fa-check text-success mr-2"></i><span>Dodano opinię w wymiarze ${value} gwiazdek.</span></div>`,
                                callback: function(confirm) {
                                    $(location).attr("href", `/recipe/show/${id}`);
                                },
                            });
                            for(let i = 0; i < value; i++){
                                let zmienna = "#star" + (i+1);
                                if($(zmienna).hasClass('far')) {
                                    $(zmienna).addClass('fas').removeClass('far');
                                }
                            }
                        },
                        error: function() {
                            bootbox.alert({
                                title: 'Nie można ponownie dodać opinii do tego przepisu!',
                                message: `<div class="modal-icon"><i class="fa fa-times text-danger mr-2"></i><span>Opinia przepisu została dodana wcześniej! Nie możesz cofnąć tego działania!</span></div>`,
                            });
                        },
                    });
                }
            }
        });
    })

    console.log($('form label[for=message]'));
});
</script>
@endsection