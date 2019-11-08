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
        Users
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- /.box-header -->
      <div class="box box-info">
        <div class="box-body">
            <fieldset>
                <div id="legend">
                    <legend class="">Edit</legend>
                </div>
                <form id="form-record" action="/admin/jobseekers/{{$jobseeker->id}}" method="post" enctype="multipart/form-data" class="form-horizontal">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="form-group row">
                        <!-- Date Time Start -->
                        <label class="control-label col-md-3 required " for="datetime_start">Họ Đệm: </label>
                        <div class="controls controlsDisplay col-md-7">
                            <div>
                                <input value="{{$jobseeker['user']->first_name}}" data-validation="length"  data-validation-length="1-100" type="text" name="first_name" placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <!-- Date Time Start -->
                        <label class="control-label col-md-3 required " for="datetime_start">Tên người tìm việc:</label>
                        <div class="controls controlsDisplay col-md-7">
                            <div>
                                <input value="{{$jobseeker['user']->last_name}}" data-validation="length"  data-validation-length="1-100" type="text" name="last_name" placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                      <label class="control-label col-md-3">Giới tính: </label>
                      <div class="controls controlsDisplay col-md-7">
                        <select class="form-control" name="gender">                        
                          <option value="1" @if($jobseeker->gender == 1) selected @endif>Nam</option>
                          <option value="0" @if($jobseeker->gender == 0) selected @endif>Nữ</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                        <!-- Date Time Start -->
                        <label class="control-label col-md-3" for="datetime_start">Ngày sinh :</label>
                        <div class="controls controlsDisplay col-md-7">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                                </div>
                                <input value="{{$jobseeker->date_of_birth}}" name="date_of_birth" type="text" class="form-control pull-right" id="datepicker">
                            </div>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <!-- Date Time Start -->
                        <label class="control-label col-md-3 required " for="datetime_start">Email:</label>
                        <div class="controls controlsDisplay col-md-7">
                            <div>
                                <input value="{{$jobseeker->email}}" data-validation="email" type="text" name="email" placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <!-- Date Time Start -->
                        <label class="control-label col-md-3 required " for="datetime_start">Mật khẩu:</label>
                        <div class="controls controlsDisplay col-md-7">
                            <div>
                                <input type="password" name="password" placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>  
                     
                    <div class="form-group row">
                        <!-- Date Time Start -->
                        <label class="control-label col-md-3 required " for="datetime_start">Xác nhận mật khẩu:</label>
                        <div class="controls controlsDisplay col-md-7">
                            <div>
                              <input data-validation="confirm_password_df" type="password" name="password_confirm" placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>                                      

                    <div class="form-group">
                        <!-- Avata -->
                        <label class="control-label col-md-3" for="avata">Hình đại diện</label>
                        <div class="controls col-md-7">
                          <img class="img-responsive pad" src="{{$jobseeker->avatar}}" alt="Photo" >
                          <input type="hidden" value="{{$jobseeker->avatar}}" name="avatar_old">
                          <input type="file" id="avata" name="avatar" placeholder="" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <!-- Date Time Start -->
                        <label class="control-label col-md-3" for="datetime_start">Đại chỉ :</label>
                        <div class="controls controlsDisplay col-md-7">
                          <textarea name="address" class="form-control" rows="3" placeholder="Enter ...">{{$jobseeker->address}}</textarea>                        
                        </div>
                    </div>    

                    <div class="form-group row">
                        <!-- Date Time Start -->
                        <label class="control-label col-md-3" for="datetime_start">Số điện thoại:</label>
                        <div class="controls controlsDisplay col-md-7">
                            <div>
                                <input value="{{$jobseeker->phone}}" data-validation="custom" data-validation-regexp="^0(\d{9})$" type="text"  name="phone" placeholder="" class="form-control datetimepicker">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                      <label class="control-label col-md-3">Trạng thái: </label>
                      <div class="controls controlsDisplay col-md-7">
                        <select class="form-control" name="status">                        
                          <option value="1" @if($jobseeker->status == 1) selected @endif>Active</option>
                          <option value="0" @if($jobseeker->status == 0) selected @endif>Inactive</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="controls col-md-10">
                          <a style="margin-left:10px;" href="/admin/jobseekers" class="btn btn-info cancelBtt pull-right">Cancel</a>
                          <input class="btn btn-success pull-right" type="submit" name="btn_submit" value="Add">
                      </div>
                    </div>           
                </form>
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
<script>
  $( document ).ready( function () {
    $.formUtils.addValidator({
      name : 'confirm_password_df',
      validatorFunction : function(value, $el, config, language, $form) {
          var password_new  = $("input[name=password]").val();
          if(value === password_new){
            return true;
          }else{
            this.errorMessage = "Input values could not be confirmed";
            return false;
          }
      },
      errorMessage : 'Confirm password does not match !',
      errorMessageKey: 'badEvenNumber'
    });
    $.validate({
    });
  });
      //Date picker
$('#datepicker').datepicker({
    autoclose: true
})
</script>
@endsection