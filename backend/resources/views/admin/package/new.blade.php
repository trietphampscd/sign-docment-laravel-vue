@extends('admin.template')
@section('styles')
<!-- date-range-picker -->
<script src="{{asset('assets/adminlte/bower_components/moment/min/moment.min.js')}}"></script>
<script src="{{asset('assets/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<!-- bootstrap datepicker -->
<script src="{{asset('assets/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<!-- bootstrap color picker -->
<script src="{{asset('assets/adminlte/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>
<!-- bootstrap time picker -->
<script src="{{asset('assets/adminlte/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
@endsection
@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Package
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- /.box-header -->
      <div class="box box-info">
        <div class="box-body">
            <fieldset>
                <div id="legend">
                    <legend class="">Thêm mới</legend>
                </div>

            </fieldset>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box-body -->
    </section>
    <!-- /.content -->
  </div>
@endsection
@section('scripts')

@endsection