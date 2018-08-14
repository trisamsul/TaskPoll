@include('templates.header')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        My Polling
        <small>Polling List</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> My Polling</a></li>
        <li class="active">List</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Polling List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              @if(session('add'))
                <div class="col-mg-6 alert alert-success">
                  {{ session('add') }} <a href="{{ url('/vote/'.session('poll_id')) }}" style="margin-left: 5px;"> <b>Vote Now!</b></a>
                </div>
              @endif
              @if(session('success'))
                <div class="col-mg-6 alert alert-success">
                  {{ session('success') }}
                </div>
              @endif
              <table id="dt" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($polls) > 0)
                    <?php $no = 1; ?>
                    @foreach($polls->all() as $poll)
                      <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $poll->title }}</td>
                        @if($poll->status == 1)
                          <td><span class="btn bg-green"><small>OPEN</small></span></td>
                        @else
                          <td><span class="btn bg-red"><small>CLOSED</small></span></td>
                        @endif
                        <td>
                          @if($poll->status == 1)
                          <a class="btn btn-default bg-red" href="{{ url('/closepoll/'.$poll->id) }}"><i class="fa fa-close" style="margin-right: 5px;"></i> <small>CLOSE</small></a>
                          @else
                          <a class="btn btn-default bg-green" href="{{ url('/openpoll/'.$poll->id) }}"><i class="fa fa-check" style="margin-right: 5px;"></i> <small>OPEN</small></a>
                          @endif
                          <a class="btn btn-default bg-purple" href="{{ url('/detail/'.$poll->id ) }}"><i class="fa fa-pie-chart" style="margin-right: 5px;"></i> DETAILS</a>
                        </td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
                <tfoot>
                  <tr>
                    <th>No.</th>
                    <th>Title</th>
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