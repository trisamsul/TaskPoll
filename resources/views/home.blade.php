<!-- 

  View Pages : Home
  
  This is the first landing page
  Show the table that contain the polling list

-->

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Polling System</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('bower_components/admin-lte/dist/css/AdminLTE.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('bower_components/admin-lte/plugins/iCheck/square/blue.css') }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page bg-blue">
  <div class="login-box">
    <div class="login-logo">
      <a href="{{ url('/') }}" style="color: #fff;"><b>Polling</b>System</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
      <table id="dt" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>No.</th>
            <th>Title</th>
            <th>Owner</th>
          </tr>
        </thead>
        <tbody>
          <!-- If there's collected vote data -->
          @if(count($polls) > 0)
            <?php $no = 1; $i = 0?>
            <!-- Show each of the polls data -->
            @foreach($polls->all() as $poll)
              <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $poll->title }}</td>
                <td>{{ $poll->username }}</td>
              </tr>
              <?php $i++; ?>
            @endforeach
          @endif
        </tbody>
        <tfoot>
          <tr>
            <th>No.</th>
            <th>Title</th>
            <th>Owner</th>
          </tr>
        </tfoot>
      </table>
      <center><p>You have to sign in to vote. <a href="{{ url('/signin') }}">Sign In</a></p></center>
    </div>
    <!-- /.login-box-body -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery 3 -->
  <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
  <!-- iCheck -->
  <script src="{{ asset('bower_components/admin-lte/plugins/iCheck/icheck.min.js') }}"></script>

</body>
</html>
