<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Organization Event</title>

    <style>
    	body {
    		/*margin: 40px 10px;
    		padding: 0; */
    		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    		font-size: 14px;
    	}
      #loading {
        display: none;
        position: absolute;
        top: 10px;
        right: 10px;
      }
    	#calendar {
    		/*max-width: 900px;*/
    		margin: 0 auto;
    	}
    </style>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Full Calendar CSS-->
    <link href='fullcalendar-2.9.1/fullcalendar.css' rel='stylesheet' />
    <link href='fullcalendar-2.9.1/fullcalendar.print.css' rel='stylesheet' media='print' />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <nav class="navbar navbar-default">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Organization</a>
      </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li class="active"><a href="index.php">View All Events <span class="sr-only">(current)</span></a></li>
          <li><a href="addNewEvent.php">Create New Event</a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>

<div class="container">
  <div class="row">
    <div id='loading' class="col-md-12">loading...</div>
    <div id='calendar' class="col-md-12"></div>
  </div>
</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src='js/jquery-3.1.0.min.js'></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Full Calendar JS-->
    <script src='fullcalendar-2.9.1/lib/moment.min.js'></script>
    <script src='fullcalendar-2.9.1/lib/jquery.min.js'></script>
    <script src='fullcalendar-2.9.1/fullcalendar.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.9.0/gcal.js"></script>
    <script>
    $(document).ready(function() {

      $('#calendar').fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month,agendaWeek,agendaDay'
        },
        defaultDate: '2016-06-12',
        editable: false,
        eventLimit: true, // allow "more" link when too many events
        // events: {
        //   url: 'php/get-events.php',
        //   error: function() {
        //     $('#script-warning').show();
        //   }
        // },
        googleCalendarApiKey: '812061625827-al2k01gpd7644m71717bctp2nmm2gks1.apps.googleusercontent.com',
        events: {
                googleCalendarId: 'dlsu.edu.ph_mmbkl04i5qll6sm3t59p8sb64k@group.calendar.google.com',
                className: 'ALGOCOM Calendar'
            },
        loading: function(bool) {
          $('#loading').toggle(bool);
        },
        eventClick: function(calEvent, jsEvent, view) {
            alert('Event: ' + calEvent.title);
            alert('Event: ' + calEvent.start);
            alert('Event: ' + calEvent.end);
            // alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
            // alert('View: ' + view.name);
            // change the border color just for fun
            $(this).css('border-color', 'red');
        },


      });
    });
    </script>
  </body>
</html>
