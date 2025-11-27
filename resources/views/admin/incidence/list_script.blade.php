<script type="text/javascript">
    var events = {!! json_encode($crud->eventsCalendar) !!};
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: events,
            eventMouseEnter: function (event, jsEvent, view) {
                $(event.el).append('<div id=\"' + event.event.id + '\" class=\"hover-end\">' + event.event.title + '</div>');
            },
            eventMouseLeave: function(event, jsEvent, view) {
                $('#'+event.event.id).remove();
            },
            eventClick: function (info) {
                if (info.event.id) {
                    location.href = `/admin/incidences/${info.event.id}/edit`;
                }
            }
        });
        calendar.render();
    });
</script>

<style>
    .hover-end {
        padding: 0;
        margin: 0;
        text-align: center;
        position: absolute;
        bottom: 0;
        top: -20px;
        width: 100%;
        opacity: .8;
        font-size: 15px;
    }
</style>
