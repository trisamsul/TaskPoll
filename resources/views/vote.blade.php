@include('templates.header')

  <style>
    .funkyradio div {
      clear: both;
      overflow: hidden;
    }

    .funkyradio label {
      width: 100%;
      border-radius: 3px;
      border: 1px solid #D1D3D4;
      font-weight: normal;
    }

    .funkyradio input[type="radio"]:empty,
    .funkyradio input[type="checkbox"]:empty {
      display: none;
    }

    .funkyradio input[type="radio"]:empty ~ label,
    .funkyradio input[type="checkbox"]:empty ~ label {
      position: relative;
      line-height: 2.5em;
      text-indent: 3.25em;
      margin-top: 1em;
      cursor: pointer;
      -webkit-user-select: none;
         -moz-user-select: none;
          -ms-user-select: none;
              user-select: none;
    }

    .funkyradio input[type="radio"]:empty ~ label:before,
    .funkyradio input[type="checkbox"]:empty ~ label:before {
      position: absolute;
      display: block;
      top: 0;
      bottom: 0;
      left: 0;
      content: '';
      width: 2.5em;
      background: #D1D3D4;
      border-radius: 3px 0 0 3px;
    }

    .funkyradio input[type="radio"]:hover:not(:checked) ~ label,
    .funkyradio input[type="checkbox"]:hover:not(:checked) ~ label {
      color: #888;
    }

    .funkyradio input[type="radio"]:hover:not(:checked) ~ label:before,
    .funkyradio input[type="checkbox"]:hover:not(:checked) ~ label:before {
      content: '\2714';
      text-indent: .9em;
      color: #C2C2C2;
    }

    .funkyradio input[type="radio"]:checked ~ label,
    .funkyradio input[type="checkbox"]:checked ~ label {
      color: #777;
    }

    .funkyradio input[type="radio"]:checked ~ label:before,
    .funkyradio input[type="checkbox"]:checked ~ label:before {
      content: '\2714';
      text-indent: .9em;
      color: #333;
      background-color: #ccc;
    }

    .funkyradio input[type="radio"]:focus ~ label:before,
    .funkyradio input[type="checkbox"]:focus ~ label:before {
      box-shadow: 0 0 0 3px #999;
    }

    .funkyradio-default input[type="radio"]:checked ~ label:before,
    .funkyradio-default input[type="checkbox"]:checked ~ label:before {
      color: #333;
      background-color: #ccc;
    }

    .funkyradio-primary input[type="radio"]:checked ~ label:before,
    .funkyradio-primary input[type="checkbox"]:checked ~ label:before {
      color: #fff;
      background-color: #337ab7;
    }
  </style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Polling
        <small>Vote</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Polling</a></li>
        <li class="active">Vote</li>
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
            <form action="{{ url('/insertvote') }}" method="post">
              {{ csrf_field() }}
              <!-- /.box-header -->
              <div class="box-body">
                <div class="funkyradio">
                  @if(count($options) > 0)
                    <?php $no = 1; ?>
                    @foreach($options->all() as $option)
                      <div class="funkyradio-primary">
                          <input type="radio" name="option_id" id="radio{{$no}}" value="{{ $option->id }}"/>
                          <label for="radio{{$no}}">{{ $option->text }}</label><br/>
                      </div>
                    <?php $no++; ?>
                    @endforeach
                  @endif
                </div>
              </div>
              <div class="box-footer clearfix">
                <input type="hidden" name="poll_id" value="{{ $poll->id }}">
                <input type="hidden" name="user_id" value="{{ session()->get('id') }}">
                <button type="submit" class="pull-right btn btn-default bg-green" id="vote" >Submit</button>
              </div>
            </form>
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