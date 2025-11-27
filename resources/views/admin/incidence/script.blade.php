<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        if ($('input[name=send]').val() == '1') {
            $('select[name="contact_email_id"]').parent().removeClass('d-none');
        }

        $('input[name=send]').change(function() {
            if ($(this).val() == '1') {
                $('select[name="contact_email_id"]').parent().removeClass('d-none');
            } else {
                $('select[name="contact_email_id"]').parent().addClass('d-none');
            }
        });

        $('select[name=type]').change(function() {
            selecType($(this).val());
        });

        crud.field('title_owner_incidence').onChange(field => {
            crud.field('title').input.value = field.value;
        });

        crud.field('title_general_incidence').onChange(field => {
            crud.field('title').input.value = field.value;
        });

        crud.field('title').onChange(field => {
            crud.field('title_owner_incidence').input.value = field.value;
            crud.field('title_general_incidence').input.value = field.value;
        }).change();

        // initialize
        selecType($('select[name=type]').val());
    });

    function selecType(value) {
        switch (value) {
            case 'general_incidence':
                crud.field('title').hide();
                crud.field('title_owner_incidence').hide();
                crud.field('title_general_incidence').show().change();
                crud.field('stall_id').hide();
                crud.field('add_absence').hide();
                crud.field('can_mount_stall').show();
                crud.field('market_id').show();
                break;
            case 'owner_incidence':
                crud.field('title').hide();
                crud.field('title_owner_incidence').show().change();
                crud.field('title_general_incidence').hide();
                crud.field('market_id').hide();
                crud.field('add_absence').show();
                crud.field('stall_id').show();
                crud.field('can_mount_stall').hide();
                break;
            case 'specific_activities':
                crud.field('title').show();
                crud.field('title').input.value = '';

                crud.field('title_owner_incidence').hide();
                crud.field('title_general_incidence').hide();
                crud.field('market_id').hide();
                crud.field('add_absence').show();
                crud.field('stall_id').show();
                crud.field('can_mount_stall').hide();
                break;
        }
    }
</script>
