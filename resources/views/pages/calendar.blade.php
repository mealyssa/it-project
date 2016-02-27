 @extends('layouts.master')

<script>

showCalendar();
function showCalendar(){
    setTimeout(loadCalendar, 1000);
}

function loadCalendar() {

    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    $('#calendar').fullCalendar({
        editable: true,
        events: [
        {
            title: 'All Day Event',
            start: new Date(y, m, 1)
        },
        {
            title: 'Long Event',
            start: new Date(y, m, d-5),
            end: new Date(y, m, d-2)
        },
        {
            id: 999,
            title: 'Repeating Event',
            start: new Date(y, m, d-3, 16, 0),
            allDay: false
        },
        {
            id: 999,
            title: 'Repeating Event',
            start: new Date(y, m, d+4, 16, 0),
            allDay: false
        },
        {
            title: 'Meeting',
            start: new Date(y, m, d, 10, 30),
            allDay: false
        },
        {
            title: 'Lunch',
            start: new Date(y, m, d, 12, 0),
            end: new Date(y, m, d, 14, 0),
            allDay: false
        },
        {
            title: 'Birthday Party',
            start: new Date(y, m, d+1, 19, 0),
            end: new Date(y, m, d+1, 22, 30),
            allDay: false
        },
        {
            title: 'Click for Google',
            start: new Date(y, m, 28),
            end: new Date(y, m, 29),
            url: 'http://google.com/'
        }
        ]
    });

}
</script>

@section('contents')
	<div class="container">
    <div class="row">
      <div class="col-md-9 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <div data-role="page">
                    <div data-role="header" id="calendarBanner">

                    </div>
                    <div data-role="content">
                        <div id='calendar' style="width:100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
    
<script>
    $(document).ready(function(){
        $('.container > ul').find('#calendarTab').addClass('active');
        
    });
</script>

@stop