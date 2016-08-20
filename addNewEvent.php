<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Organization Event</title>

    <style>
    	body, th, td{
    		/*margin: 40px 10px;
    		padding: 0; */
    		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    		font-size: 16px;
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

      .glyphicon:before {
       visibility: visible;
      }
      .glyphicon.glyphicon-star-empty:checked:before {
         content: "\e006";
      }
      input[type=checkbox].glyphicon{
          visibility: hidden;

      }
      div > .best{
        background-color: green;
        color: #fff;
      }
      .card-header{
        text-align: center;
      }
      .card-body{
        padding-left: 10px;
      }
      .participants{
        font-size: 16px;
      }
    </style>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
    <link href="css/bootstrap.min.css" rel="stylesheet">

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
          <li><a href="index.php">View All Events</a></li>
          <li class="active"><a href="addNewEvent.php">Create New Event  <span class="sr-only">(current)</span></a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>

<div class="container">
  <div class="row">
    <div class="well">
      <h4>Add new event</h4>
      <form>
        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-1 col-form-label">Name:</label>
          <div class="col-sm-5">
            <input type="text" class="form-control" id="inputEmail3" maxlength="100" placeholder="name">
          </div>
          <label for="inputPassword3" class="col-sm-1 col-form-label">Location: </label>
          <div class="col-sm-5">
            <input type="text" class="form-control" id="inputPassword3" maxlength="100" placeholder="location">
          </div>
        </div>
        <div class="form-group row">
          <label for="inputEmail3" class="col-sm-1 col-form-label">Time (mins):</label>
          <div class="col-sm-5">
            <input type="number" class="form-control" id="inputEmail3" min="10" step="1" value="10">
          </div>
          <label for="inputEmail3" class="col-sm-1 col-form-label">Deadline:</label>
          <div class="col-xs-5">
            <input class="form-control" type="datetime-local" id="example-datetime-local-input">
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword3" class="col-sm-1 col-form-label">Description:</label>
          <div class="col-sm-11">
            <textarea class="form-control" id="inputPassword3" row="5" maxlength="140"> </textarea>
          </div>
        </div>
        <fieldset class="form-group row">
          <legend class="col-form-legend col-sm-12">People</legend>
          <table class="col-form-legend col-sm-offset-1 col-sm-11">
            <thead>
              <th>Invite</th>
              <th>Priority?</th>
              <th>Name</th>
            </thead>
            <tbody>
              <tr>
                <td><input class="form-check-input glyphicon glyphicon-star-empty" type="checkbox"></td>
                <td><input class="form-check-input" type="checkbox"></td>
                <td>Regina Claire Balajadia</td>
              </tr>
              <tr>
                <td><input class="form-check-input glyphicon glyphicon-star-empty" type="checkbox"></td>
                <td><input class="form-check-input" type="checkbox"></td>
                <td>Rafael Lozano</td>
              </tr>
              <tr>
                <td><input class="form-check-input glyphicon glyphicon-star-empty" type="checkbox"></td>
                <td><input class="form-check-input" type="checkbox"></td>
                <td>John Martin Lucas</td>
              </tr>
            </tbody>
          </table>
        </fieldset>
        <div class="form-group row">
          <div class="offset-sm-2 col-sm-10">
            <button type="submit" class="btn btn-primary">Suggest Event Time</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="container">
  <div class="row">
      <div class="col-sm-4 col-lg-4 col-md-4">
          <div class="thumbnail">
            <div class="card-header best">
              <h3>Timeslot</h3>
            </div>
              <div class="card-body">
                  <h4><strong>Date:</strong> August 17, 2016</h4>
                  <h4><strong>Time:</strong> 15:30 - 18:30</h4>
                  <h4><strong>Available:</strong></h4>
                  <ul>
                    <li class="participants">Regina Claire Balajadia</li>
                    <li class="participants">John Martin Lucas</li>
                  </ul>
                  <h4><strong>Not Available: </strong></h4>
                  <ul>
                    <li class="participants">Rafael Lozano</li>
                  </ul>
              </div>
              <div class="bookEvent">
                  <a href="#" class="btn btn-primary btn-md btn-block">
                     Book Event
                  </a>
              </div>
          </div>
      </div>
      <div class="col-sm-4 col-lg-4 col-md-4">
          <div class="thumbnail">
            <div class="card-header">
              <h3>Timeslot</h3>
            </div>
              <div class="card-body">
                  <h4><strong>Date:</strong> August 18, 2016</h4>
                  <h4><strong>Time:</strong> 11:00 - 12:30</h4>
                  <h4><strong>Available:</strong></h4>
                  <ul>
                    <li class="participants">John Martin Lucas</li>
                  </ul>
                  <h4><strong>Not Available: </strong></h4>
                  <ul>
                    <li class="participants">Regina Claire Balajadia</li>
                    <li class="participants">Rafael Lozano</li>
                  </ul>
              </div>
              <div class="bookEvent">
                  <a href="#" class="btn btn-primary btn-md btn-block">
                     Book Event
                  </a>
              </div>
          </div>
      </div>
      <div class="col-sm-4 col-lg-4 col-md-4">
          <div class="thumbnail">
            <div class="card-header">
              <h3>Timeslot</h3>
            </div>
              <div class="card-body">
                  <h4><strong>Date:</strong> August 20, 2016</h4>
                  <h4><strong>Time:</strong> 12:30 - 14:30</h4>
                  <h4><strong>Available:</strong></h4>
                  <ul>
                    <li class="participants">Regina Claire Balajadia</li>
                  </ul>
                  <h4><strong>Not Available: </strong></h4>
                  <ul>
                    <li class="participants">Rafael Lozano</li>
                    <li class="participants">John Martin Lucas</li>
                  </ul>
              </div>
              <div class="bookEvent">
                  <a href="#" class="btn btn-primary btn-md btn-block">
                     Book Event
                  </a>
              </div>
          </div>
      </div>
  </div>
</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src='js/jquery-3.1.0.min.js'></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
