@php
    $textBtn = $entry->unsubscribe_date ? __('backpack.persons.restore') : __('backpack.persons.unsubscribe');
    $iconBtn = $entry->unsubscribe_date ? 'la-folder-plus' : 'la-folder-minus';
@endphp

<a href="javascript:void(0)" onclick="toggleSubscribeEntry(this)"
    data-route="{{ url($crud->route . '/' . $entry->getKey() . '/toggle-subscribe') }}" class="btn btn-sm btn-link"
    data-button-type="toggle-subscribe" data-name="{{ $entry->name }}" data-text="{{ strtolower($textBtn) }}"><i class="la {{ $iconBtn }}"></i>
    {{ $textBtn }} {{ __('backpack.persons.single') }}
</a>

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts')
    @if (request()->ajax())
    @endpush
@endif
<script>
    if (typeof sendEntry != 'function') {
        $("[data-button-type=toggle-subscribe]").unbind('click');

        function toggleSubscribeEntry(button) {
            // ask for confirmation before deleting an item
            // e.preventDefault();
            var route = $(button).attr('data-route');
            var name = $(button).attr('data-name');
            var text = $(button).attr('data-text');

            swal({
                title: "{!! trans('backpack::base.warning') !!}",
                text: "{!! trans('backpack.persons.unsubscribe_question') !!}".replace(':text', text).replace(':name', name),
                icon: "info",
                buttons: ["{!! trans('backpack::crud.cancel') !!}", "{!! trans('backpack::crud.save') !!}"],
            }).then((value) => {
                if (value) {
                    $.ajax({
                        url: route,
                        type: 'GET',
                        success: function(result) {
                            if (result.message) {
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
                                    text: "{!! '<strong>' . trans('backpack.operations.send.confirmation_title') . '</strong><br>' !!}" + result.message
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
