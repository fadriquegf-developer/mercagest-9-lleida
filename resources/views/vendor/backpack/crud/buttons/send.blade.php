@if ($crud->hasAccess('send') && $entry->is_stall())
    <a href="javascript:void(0)" onclick="sendEntry(this)"
        data-route="{{ url($crud->route . '/' . $entry->getKey() . '/send') }}" class="btn btn-sm btn-link"
        data-button-type="send"><i class="la la-paper-plane"></i> {{ trans('backpack.btn_send') }}
    </a>
@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts')
    @if (request()->ajax())
    @endpush
@endif
<script>
    if (typeof sendEntry != 'function') {
        $("[data-button-type=send]").unbind('click');

        function sendEntry(button) {
            // ask for confirmation before deleting an item
            // e.preventDefault();
            var route = $(button).attr('data-route');

            swal({
                title: "{!! trans('backpack::base.warning') !!}",
                text: "{!! trans('backpack.operations.send.confirm') !!}",
                icon: "info",
                buttons: ["{!! trans('backpack::crud.cancel') !!}", "{!! trans('backpack.operations.send.button') !!}"],
            }).then((value) => {
                if (value) {
                    $.ajax({
                        url: route,
                        type: 'POST',
                        success: function(result) {
                            if (result == 1) {
                                // Redraw the table
                                if (typeof crud != 'undefined' && typeof crud.table !=
                                    'undefined') {
                                    // Move to previous page in case of deleting the only item in table
                                    if (crud.table.rows().count() === 1) {
                                        crud.table.page("previous");
                                    }

                                    crud.table.draw(false);
                                }

                                // Show a success notification bubble
                                new Noty({
                                    type: "success",
                                    text: "{!! '<strong>' .
                                        trans('backpack.operations.send.confirmation_title') .
                                        '</strong><br>' .
                                        trans('backpack.operations.send.confirmation_message') !!}"
                                }).show();

                                // Hide the modal, if any
                                $('.modal').modal('hide');
                            } else {
                                // if the result is an array, it means 
                                // we have notification bubbles to show
                                if (result instanceof Object) {
                                    // trigger one or more bubble notifications 
                                    Object.entries(result).forEach(function(entry, index) {
                                        var type = entry[0];
                                        entry[1].forEach(function(message, i) {
                                            new Noty({
                                                type: type,
                                                text: message
                                            }).show();
                                        });
                                    });
                                } else { // Show an error alert
                                    swal({
                                        title: "{!! trans('backpack.operations.send.confirmation_not_title') !!}",
                                        text: "{!! trans('backpack.operations.send.confirmation_not_message') !!}",
                                        icon: "error",
                                        timer: 4000,
                                        buttons: false,
                                    });
                                }
                            }
                        },
                        error: function(result) {
                            // Show an alert with the result
                            swal({
                                title: "{!! trans('backpack.operations.send.confirmation_not_title') !!}",
                                text: "{!! trans('backpack.operations.send.confirmation_not_message') !!}",
                                icon: "error",
                                timer: 4000,
                                buttons: false,
                            });
                        }
                    });
                }
            });

        }
    }

    // make it so that the function above is run after each DataTable draw event
    // crud.addFunctionToDataTablesDrawEventQueue('sendEntry');
</script>
@if (!request()->ajax())
@endpush
@endif
