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
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
              <div class="box-header">
                <div class="row" id="requests-header">
                  <div class="col-sm-8 col-xs-6">
                      <h2 class="box-title">Profile</h2>
                  </div>

                  <div class="col-sm-4 col-xs-6">
                    <a href="/admin/profile/edit" id="edit5" type="button" class="btn btn-primary edit-record  pull-right">
                      <i class="fa fa-edit"></i>
                    </a>
                  </div>
                </div>
              </div>
              <!-- /.box-header -->
              
            <div class="row">
              <div class="col-md-8 col-sm-12 col-xs-12">	   
                  <div class="box-body profile-body">
                    <fieldset>
                      <table class="profile-table">
                        <tbody><tr>
                          <td class="title">Full name :</td>
                          <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                          <td class="title">Email :</td>
                          <td>{{ $user->email }}</td>
                        </tr>
                      </tbody></table>
                    </fieldset>
                  </div>
              </div>
              <div class="col-md-4 col-sm-12 col-xs-12 text-center box-body box-profile">	
                <div class="">
                  @if (!isset($user->avatar))
                    <img src="{{asset('assets/img/user.jpg')}}">
                  @else
                    <img src="{{ $user->avatar }}" style="width:300px">
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
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