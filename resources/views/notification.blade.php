<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content notify_content">
            <div class="modal-header">
                <h5 class="modal-title notify-title" id="exampleModalLabel">{{__('Powiadomienia')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body notify_body">
                <div class="container-fluid p-0">
                    <div class="row m-0">
                        @if(isset($notifications))
                        @forelse($notifications as $notification)
                        
                                @switch($notification->type)
                                    @case('App\Notifications\AddProductProposition')
                                    <div class="alert alert-success notification-alert col-12" role="alert">
                                    <span>
                                        {{'Dodano produkt do proponoanych o nazwie'}}
                                        <span class="font-weight-bold">{{ $notification->data['product_name'] }}</span>.
                                    @break

                                    @case('App\Notifications\EditRecipeNotification')
                                    <div class="alert alert-warning notification-alert col-12" role="alert">
                                    <span>
                                        {{'Dokonano edycji przepisu o nazwie '}}
                                        <span class="font-weight-bold">{{ $notification->data['recipe_name'] }}</span>.
                                        @break

                                    @case('App\Notifications\NewRecipeAdd')
                                    <div class="alert alert-success notification-alert col-12" role="alert">
                                    <span>
                                        {{'Dodano nowy przepis: '}}
                                        <span class="font-weight-bold">{{$notification->data['recipe_name']}}</span>.
                                    @break

                                    @case('App\Notifications\ProductPropositionAccept')
                                    <div class="alert alert-success notification-alert col-12" role="alert">
                                    <span>
                                        {{'Produkt o nazwie: '}}
                                        <span class="font-weight-bold">{{ $notification->data['product_name'] }}</span>
                                        {{'został zaakceptowany'}}.
                                    @break

                                    @case('App\Notifications\ProductPropositionReject')
                                    <div class="alert alert-danger notification-alert col-12" role="alert">
                                    <span>
                                        {{'Propozycja produktu o nazwie: '}}
                                        <span class="font-weight-bold">{{ $notification->data['product_name'] }}</span>,
                                        {{'została odrzucona'}}.
                                    @break

                                    @case('App\Notifications\RecipeEmptyNextDay')
                                    <div class="alert alert-warning notification-alert col-12" role="alert">
                                    <span>
                                        {{'Jeszcze nie zaplanowałeś(aś) przepisów, które wykonasz w kolejnym dniu, przejdź do kalendarza i zrób to teraz!'}}.
                                    @break

                                @endswitch


                            </span>
                            <button href="{{route('markNotification')}}" type="button" class="close"
                                data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @empty
                        {{('Brak nowych powiadomień')}}
                        @endforelse
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"
                    data-dismiss="modal">{{__('Zamknij')}}</button>
                <button href="{{route('markNotificationAll')}}" type="button"
                    class="allClose btn btn-dark">{{('Oznacz wszystkie jako przeczytane')}}</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('.notify-title').html('Powiadomienia');
    let buttons = $(".notify_body button.close");
    $(".notify_body button.close").click(function () {
        $("#notifications").html(($("#notifications").html().replace("(", "").replace(")", "") -1));
        if ($("#notifications").html() === "(0)") {
            $(".notifications").addClass('nothing-to-display');
            $(".notifications-bell").removeClass('d-inline');
            $("#notificationValue").html('Brak nowych powiadomień');

        }
    });

    $(".notify_content button.allClose").click(function () {
        buttons.parent().remove();
        $("#notifications").html("(0)");
        $(".notifications").addClass('nothing-to-display');
        $(".notifications-bell").removeClass('d-inline');
    });

</script>