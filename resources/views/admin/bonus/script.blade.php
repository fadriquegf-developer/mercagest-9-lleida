<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        crud.field('select').onChange(function(field) {
            crud.field('market_id').show(field.value === 'market');
            crud.field('marketgroup_id').show(field.value === 'group' || field.value === 'market');
            crud.field('stall_id').show(field.value === 'individual');
            $('#info-market-days').hide();
            if(field.value === 'group'){
                $(crud.field('type').input).find('option[value="days"]').hide();
            }else{
                $(crud.field('type').input).find('option[value="days"]').show();
            }
        }).change();

        crud.field('start_at').onChange(function(field) {
            let ends_at = crud.field('ends_at').value;
            let stall_id = crud.field('stall_id').value;
            let market_id = crud.field('market_id').value;
            let select = crud.field('select').value;
            if (ends_at && crud.field('type').value == 'days') {
                if(select == 'individual'){
                    numberMarketDaysByStall(stall_id, field.value, ends_at);
                }
                if(select == 'market'){
                    numberMarketDaysByMarket(market_id, field.value, ends_at);
                }
               
            }
        });

        crud.field('ends_at').onChange(function(field) {
            let start_at = crud.field('start_at').value;
            let stall_id = crud.field('stall_id').value;
            let market_id = crud.field('market_id').value;
            let select = crud.field('select').value;
            if (start_at && crud.field('type').value == 'days') {
                if(select == 'individual'){
                    numberMarketDaysByStall(stall_id, start_at, field.value);
                }
                if(select == 'market'){
                    numberMarketDaysByMarket(market_id, start_at, field.value);
                }
            }
        });

        crud.field('type').onChange(function(field) {
            let start_at = crud.field('start_at').value;
            let ends_at = crud.field('ends_at').value;
            let stall_id = crud.field('stall_id').value;
            let market_id = crud.field('market_id').value;
            let select = crud.field('select').value;
            if (start_at && ends_at && field.value == 'days') {
                if(select == 'individual'){
                    numberMarketDaysByStall(stall_id, start_at, ends_at);
                }
                if(select == 'market'){
                    numberMarketDaysByMarket(market_id, start_at, ends_at);
                }
            }
        });

        crud.field('stall_id').onChange(function(field) {
            let start_at = crud.field('start_at').value;
            let ends_at = crud.field('ends_at').value;
            if (start_at && ends_at && crud.field('type').value == 'days') {
                numberMarketDaysByStall(field.value, start_at, ends_at);  
               
            }
        });

        crud.field('market_id').onChange(function(field) {
            console.log('entra');
            let start_at = crud.field('start_at').value;
            let ends_at = crud.field('ends_at').value;
            if (start_at && ends_at && crud.field('type').value == 'days') {
                numberMarketDaysByMarket(field.value, start_at, ends_at);  
            }
        });

        function numberMarketDaysByStall(stall_id, start_at, end_at) {
            $.ajax({
                type: 'POST',
                url: `/admin/bonus/get-market-days-by-stall`,
                data: {
                    stall_id: stall_id,
                    start_at: start_at,
                    end_at: end_at,
                },
                success: function(result, status, xhr) {
                    $(crud.field('amount').input).attr('max', result.data.num_days);
                    $('#info-market-days span').html(result.data.num_days);
                    $('#info-market-days').show();
                    console.log(result.data.num_days);
                }
            });
        }

        function numberMarketDaysByMarket(market_id, start_at, end_at) {
            $.ajax({
                type: 'POST',
                url: `/admin/bonus/get-market-days-by-market`,
                data: {
                    market_id: market_id,
                    start_at: start_at,
                    end_at: end_at,
                },
                success: function(result, status, xhr) {
                    $(crud.field('amount').input).attr('max', result.data.num_days);
                    $('#info-market-days span').html(result.data.num_days);
                    $('#info-market-days').show();
                    console.log(result.data.num_days);
                }
            });
        }
    });
</script>
