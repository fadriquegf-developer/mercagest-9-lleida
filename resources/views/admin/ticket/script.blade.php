<script>
    document.addEventListener("DOMContentLoaded", function (event) {
        let val = $('select[name=type]').val();
        setSelectsByTypeValue(val);

        $(document).on('change', 'select[name=type]', function () {
            let val = $(this).val();
            setSelectsByTypeValue(val);
        });
    });

    async function setSelectsByTypeValue(type) {
        $('select[name="market_groups\[\]"]').val([]).trigger('change').parent().addClass('d-none');
        $('select[name="markets\[\]"]').val([]).trigger('change').parent().addClass('d-none');
        $('select[name="stalls\[\]"]').val([]).trigger('change').parent().addClass('d-none');

        if (type == 'market-group' || type == 'stall-group') {
            $('select[name="market_groups\[\]"]').parent().removeClass('d-none');
        }

        if (type == 'market') {
            $('select[name="markets\[\]"]').parent().removeClass('d-none');
        }

        if (type == 'stall') {
            await getStallsByMarket();
            $('select[name="stalls\[\]"]').parent().removeClass('d-none');
        }
    }

    function getStallsByMarket() {
        $.ajax(
            {
                type: 'POST',
                url: `/admin/stall/get-by-market`,
                success: function (result, status, xhr) {
                    let html = ``;
                    $.each(result, function (k, v) {
                        html = `${html}<option value="${k}">${v}</option>`
                    });
                    $('select[name="stalls\[\]"]').html(html).trigger('change');
                }
            });
    }
</script>
