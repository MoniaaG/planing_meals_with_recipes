<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content notify_content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title notify-title text-white" id="exampleModalLabel">{{__('Powiadomienia')}}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
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
                                        @lang('home.added_product_to_proposition')
                                        <span class="font-weight-bold">{{ $notification->data['product_name'] }}</span>.
                                    @break

                                    @case('App\Notifications\EditRecipeNotification')
                                    <div class="alert alert-warning notification-alert col-12" role="alert">
                                    <span>
                                        @lang('home.edited_recipe')
                                        <span class="font-weight-bold">{{ $notification->data['recipe_name'] }}</span>.
                                        @break

                                    @case('App\Notifications\NewRecipeAdd')
                                    <div class="alert alert-success notification-alert col-12" role="alert">
                                    <span>
                                        @lang('home.added_newest_recipe')
                                        <span class="font-weight-bold">{{$notification->data['recipe_name']}}</span>.
                                    @break

                                    @case('App\Notifications\ProductPropositionAccept')
                                    <div class="alert alert-success notification-alert col-12" role="alert">
                                    <span>
                                        @lang('home.product_name_notify')
                                        <span class="font-weight-bold">{{ $notification->data['product_name'] }}</span>
                                        @lang('home.accepted').
                                    @break

                                    @case('App\Notifications\ProductPropositionReject')
                                    <div class="alert alert-danger notification-alert col-12" role="alert">
                                    <span>
                                        @lang('home.product_proposition')
                                        <span class="font-weight-bold">{{ $notification->data['product_name'] }}</span>,
                                        @lang('home.rejected').
                                    @break

                                    @case('App\Notifications\RecipeEmptyNextDay')
                                    <div class="alert alert-warning notification-alert col-12" role="alert">
                                    <span>
                                        @lang('home.not_plan')
                                    @break

                                @endswitch


                            </span>
                            <button href="{{route('markNotification')}}" type="button" class="close"
                                data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @empty
                        @lang('home.no_notifications')
                        @endforelse
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"
                    data-dismiss="modal">@lang('home.close')</button>
                <button href="{{route('markNotificationAll')}}" type="button"
                    class="allClose btn btn-dark">@lang('home.assign_as_readed')</button>
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