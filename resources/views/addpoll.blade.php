<!-- 

  View Pages : Add Poll
  
  This is a page to add a new polling
  This page is only available for Administrator

-->

@include('templates.header')
  
  <style>
    /* Styling for polling options using radio button */

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
        <small>Add Poll</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> My Polling</a></li>
        <li class="active">Add</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Add New Poll</h3>
            </div>
            <form action="{{ url('/insertpoll') }}" method="post">
              {{ csrf_field() }}
              <!-- /.box-header -->
              <div class="box-body">
                <!-- Alert if there's error occured -->
                @if(count($errors) > 0)
                  @foreach($errors->all() as $error)
                    <div class="alert alert-danger">
                      {{ $error }}
                    </div>
                  @endforeach
                @endif
                <!-- Form -->
                <div class="form-group">
                  <label>Poll Title</label>
                  <!-- Poll Title -->
                  <input type="text" class="form-control" name="title" placeholder="Poll Title">
                </div>
                <div class="form-group">
                  <!-- Poll Options -->
                  <label>Poll Options</label>

                  <!-- Button to add a new options -->
                  <a class="pull-right btn btn-default bg-green" href="#" id="addBtn" onclick="addOption()"><i class="fa fa-plus" style="margin-right: 5px;"></i> Add</a> 
                  <!-- Button to remove the last options -->
                  <a class="pull-right btn btn-default bg-red" style="margin-right: 5px;" href="#" id="remBtn" onclick="removeOption()"><i class="fa fa-trash" style="margin-right: 5px;"></i> Remove</a>
                </div>
                <div class="form-group" id="options-list">
                  <!-- Options -->
                  <input type="text" class="form-control" id="opt1" name="option1" style="margin-bottom: 5px;" placeholder="Option #1">
                  <input type="text" class="form-control" id="opt2" name="option2" style="margin-bottom: 5px;" placeholder="Option #2">
                  <input type="text" class="form-control" id="opt3" name="option3" style="margin-bottom: 5px;" placeholder="Option #3">
                </div><!-- /input-group -->
              </div>
              <div class="box-footer clearfix">
                <!-- Hidden value to save number of options -->
                <input type="hidden" name="number" id="number" value="3">
                <!-- Button to submit a new poll -->
                <input type="submit" class="pull-right btn btn-primary bg-green" id="vote" value="Add New Poll"/>
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
  
  <script type='text/javascript'>
    function addOption(){
      // Container <div> where dynamic content will be placed
      var container = document.getElementById("options-list");
      var number = document.getElementById("number").value;
      
      // increase the number of the options
      var newnumber = +number + 1;

      // Create an <input> element, set its type and name attributes
      var input = document.createElement("input");
      input.id = "opt" + newnumber;
      input.type = "text";
      input.name = "option" + newnumber;
      input.placeholder = "Option #" + newnumber;
      input.style = "margin-bottom: 5px;";
      input.classList.add("form-control");

      // Add the new element to the options list 
      container.appendChild(input);

      document.getElementById("number").value = newnumber;

      // if the number of the options are more or equal to 3, the remove button will be displayed
      if(document.getElementById("number").value >= 3){
        document.getElementById("remBtn").style.display = "block";
      }
    }

    function removeOption(){
      // Container <div> where dynamic content will be placed
      var container = document.getElementById("options-list");
      var number = document.getElementById("number").value;
      
      // get the last options id and then remove it from the options list
      var element = document.getElementById("opt" + number);
      element.parentNode.removeChild(element);

      // reduce the number of the options
      var newnumber = +number - 1;
      document.getElementById("number").value = newnumber;

      // if the number of the options are less than 3, the remove button will be hidden
      if(document.getElementById("number").value < 3){
        document.getElementById("remBtn").style.display = "none";
      }
    }

  </script>

@include('templates.footer')