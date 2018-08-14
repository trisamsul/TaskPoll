<!-- 

  View Pages : Dashboard
  
  This is the main page to see the polling list

-->

@include('templates.header')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Polling
        <small>Dashboard</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <!-- Show the number of total polling -->
              <h3>{{ $count['polls_total'] }}</h3>

              <p>Total Polls</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <!-- Show the number of currently active polling -->
              <h3>{{ $count['polls_active'] }}</h3>

              <p>Active Polls</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
              <!-- Show the number of all registered user and administrator -->
              <h3>{{ $count['contributors'] }}</h3>

              <p>Contributors</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
              <!-- Show the number of administrator that can create the polls -->
              <h3>{{ $count['owners'] }}</h3>

              <p>Poll Owner</p>
            </div>
            <div class="icon">
              <i class="ion ion-person"></i>
            </div>
            <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
          </div>
        </div>
        <!-- ./col -->
        
      </div>
      <!-- /.row -->
      
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Polling List</h3>
              <!-- Show the button to add new poll if the current user is category 1 (Administrator) -->
              @if(session()->get('category') == 1)
                <a class="pull-right btn btn-default bg-green" style="margin-right: 5px;" href="{{ url('/addpoll') }}"><i class="fa fa-plus"></i> Add New Poll</a>
              @endif
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              @if(session('success'))
                <div class="col-mg-6 alert alert-success">
                  {{ session('success') }}
                </div>
              @endif
              @if(session('fail'))
                <div class="col-mg-6 alert alert-danger">
                  {{ session('fail') }}
                </div>
              @endif
              <!-- Table Polling List -->
              <table id="dt" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Title</th>
                    <th>Owner</th>
                    <th>Voted</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- If there's polling data collected -->
                  @if(count($polls) > 0)
                    <?php $no = 1; $i = 0?>
                    <!-- Show each data on the table -->
                    @foreach($polls->all() as $poll)
                      <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $poll->title }}</td>
                        <td>{{ $poll->username }}</td>
                        <td>
                        @if($voted[$i] == 1)
                        <!-- Give the checked symbol if the user has voted this poll -->
                          <i class="fa fa-check"></i>
                        @endif
                        </td>
                        @if($poll->status == 1)
                        <!-- If the poll status is open then show the current status as Open -->
                          <td><span class="btn bg-green"><small>OPEN</small></span></td>
                        @else
                        <!-- If the poll status is closed then show the current status as Closed -->
                          <td><span class="btn bg-red"><small>CLOSED</small></span></td>
                        @endif
                        <td>
                          @if($voted[$i] == 0)
                          <!-- If user haven't vote this poll, then show the button to vote -->
                          <a class="btn btn-default bg-blue" href="{{ url('/vote/'.$poll->id) }}"><i class="fa fa-check-square-o" style="margin-right: 5px;"></i> VOTE</a>
                          @else
                          <!-- If user already voted this poll, then show the button to see the details -->
                          <a class="btn btn-default bg-purple" href="{{ url('/detail/'.$poll->id ) }}"><i class="fa fa-pie-chart" style="margin-right: 5px;"></i> DETAILS</a>
                          @endif
                        </td>
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
                    <th>Voted</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
  
@include('templates.footer')