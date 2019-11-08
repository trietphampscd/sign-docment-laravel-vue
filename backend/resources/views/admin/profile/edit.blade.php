@extends('admin.template')
@section('styles')
@endsection
@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Danh mục
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- /.box-header -->
      <div class="box box-info">
        <div class="box-body">
            <fieldset>
                <div id="legend">
                    <legend class="">Edit Profile</legend>
                </div>
                <form id="form-record" action="/admin/profile/edit" method="post" enctype="multipart/form-data" class="form-horizontal">
                    {{ csrf_field() }}
                    {{ method_field('POST') }}
                    <div class="form-group row">
                        <!-- Date Time Start -->
                        <label class="control-label col-md-3" for="datetime_start">FullName :</label>
                        <div class="controls controlsDisplay col-md-7">
                            <div>
                                <input value="{{ $user->name }}" data-validation="length"  data-validation-length="3-100" type="text" id="datetime_start" name="name" placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <!-- Date Time Start -->
                        <label class="control-label col-md-3" for="datetime_start">Email :</label>
                        <div class="controls controlsDisplay col-md-7">
                            <div>
                                <input value="{{ $user->email }}" data-validation="length" disabled type="text" placeholder="" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3" for="avata">Avatar</label>
                        <div class="controls col-md-7">
                            @if (!isset($user->avatar))
                              <img class="img-responsive pad" alt="default" src="{{asset('assets/img/user.jpg')}}" >
                            @else
                              <img class="img-responsive pad" src="{{ $user->avatar }}" alt="Avatar" style="width:300px;">
                            @endif
                            <input type="hidden" value="" name="image_old">
                            <input type="file" id="avata" name="image" placeholder="" class="form-control">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <div class="controls col-md-10">
                            <a style="margin-left:10px;" href="/admin/profile/view" class="btn btn-info cancelBtt pull-right">Cancel</a>
                            <input class="btn btn-success pull-right" type="submit" name="btn_submit" value="Edit">
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
  $.validate({
  });
</script>
@endsection