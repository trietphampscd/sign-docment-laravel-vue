@extends('admin.template')
@section('styles')
@endsection
@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Setting
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- /.box-header -->
      <div class="box box-info">
        <fieldset>
            <div id="legend">
                <legend class="">Change password</legend>
            </div>
            <form action="/admin/profile/change-password" method="post" enctype="multipart/form-data" class="form-horizontal">
              <div>
                {{ csrf_field() }}
                @method('POST')
                <div class="form-group row">
                    <!-- Date Time Start -->
                    <label class="control-label col-md-3" for="datetime_start">Current Password :</label>
                    <div class="controls controlsDisplay col-md-7">
                        <div>
                          <input data-validation="length"  data-validation-length="5-50" type="password" name="password_old" placeholder="" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <!-- Date Time Start -->
                    <label class="control-label col-md-3" for="datetime_start">New Password :</label>
                    <div class="controls controlsDisplay col-md-7">
                        <div>
                            <input data-validation="length"  data-validation-length="5-50" type="password" name="password_new" placeholder="" class="form-control datetimepicker">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <!-- Date Time Start -->
                    <label class="control-label col-md-3" for="datetime_start">Confirm Password:</label>
                    <div class="controls controlsDisplay col-md-7">
                        <div>
                            <input data-validation="confirm_password_df" type="password" name="password_confirm" placeholder="" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="controls col-md-10">
                        <a style="margin-left:10px;" href="/admin/dashboard" class="btn btn-info cancelBtt pull-right">Cancel</a>
                        <input class="btn btn-success pull-right" type="submit" name="btn_submit" value="Save">
                    </div>
                </div>
            </div>
            </form>
        </fieldset>
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
          var password_new  = $("input[name=password_new]").val();
          if(value.length < 5 || value.length > 50){
            this.errorMessage = "The input value must be between 5-50 characters";
            return false;
          }
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
</script>
@endsection