@include('crud::fields.inc.wrapper_start')
<div class='alert alert-info'><i class='las la-info-circle'></i> <b>Titular actual:</b>
    {{ $entry->titular ?? '-' }}
</div>
@include('crud::fields.inc.wrapper_end')
