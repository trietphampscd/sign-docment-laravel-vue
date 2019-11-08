@extends('admin.template')
@section('styles')
<!-- date-range-picker -->
@endsection
@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Static pages
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
    $("select#experience_select").change(function(){
        var value_selected = $(this).children("option:selected").val();
        if(value_selected == 1){
            $('.experience_select').show();
        }else{
            $('.experience_select').hide();
        }
    });

    $("select#salary_select").change(function(){
        var value_selected = $(this).children("option:selected").val();
        if(value_selected == 1){
            $('.salary_select').show();
        }else{
            $('.salary_select').hide();
        }
    });
    $.formUtils.addValidator({
      name : 'confirm_password_df',
      validatorFunction : function(value, $el, config, language, $form) {
          var password_new  = $("input[name=password]").val();
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
      errorMessage : 'Xác nhận mật khẩu không trùng khớp !',
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
<script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'job_description');
    CKEDITOR.replace( 'job_requirement');
    CKEDITOR.replace( 'orther_info');
</script>
@endsection