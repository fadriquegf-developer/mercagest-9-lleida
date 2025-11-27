<a href="javascript:void(0)" onclick="generateEntry(this)"
    data-route="{{ url($crud->route . '/' . $entry->getKey() . '/generate') }}" class="btn btn-sm btn-link"
    data-button-type="send"><i class="la la-address-card"></i> {{ trans('backpack.operations.generate.button') }}
</a>

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts')
    @if (request()->ajax())
    @endpush
@endif
<script>
    if (typeof generateEntry != 'function') {
        $("[data-button-type=send]").unbind('click');

        function generateEntry(button) {
            // ask for confirmation before deleting an item
            // e.preventDefault();
            var route = $(button).attr('data-route');

            swal({
                title: "{!! trans('backpack::base.warning') !!}",
                text: "{!! trans('backpack.operations.generate.confirm') !!}",
                icon: "info",
                buttons: ["{!! trans('backpack::crud.cancel') !!}", "{!! trans('backpack.operations.generate.button') !!}"],
            }).then((value) => {
                if (value) {
                    window.location.href = route;
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
