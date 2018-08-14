<!-- 

  View Pages : Detail Polling
  
  This is the page to see the the details of the polling
  Show Options current vote result and total vote
  Show the result in Pie Chart

-->

@include('templates.header')

  <style>
    .options{
      margin: 10px 10px 10px 0px;
      padding: 10px;
      border-radius: 10px;
      color: #fff;
      background-color: #3c8dbc; 
    }
  </style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Polling
        <small>Detail</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Polling</a></li>
        <li class="active">Detail</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">{{ $poll->title }}</h3>
              <p class="pull-right">By: {{ $poll->username }}</p>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-md-6">
                <div class="row">
                  <ul>
                    <!-- If theres data of options collected -->
                    @if(count($options) > 0)
                      <?php $no = 1; $total = 0; ?>
                      <!-- Show each options data -->
                      @foreach($options->all() as $option)
                        <li class="options" id="opt<?php echo $no; ?>">{{ $option->text }}<span class="pull-right">{{ $option->voted }}<span></li>
                      <?php $no++; $total += $option->voted; ?>
                      @endforeach
                    @endif
                  </ul>
                </div>
                <div class="row">
                  <!-- Show the total number of vote on the current poll -->
                  <h4 class="pull-right" style="margin-right:10px;">Total vote: {{ $total }}</h4>
                </div>
              </div>  <!-- col-md-6 -->
              <div class="col-md-6">
                <canvas id="chart-area"/>
              </div>  <!-- col-md-6 -->
            </div>
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