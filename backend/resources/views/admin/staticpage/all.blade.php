@extends('admin.template')
@section('styles')
@endsection
@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Static page
        <small></small>
      </h1>
      <p class="pull-right">
        <a href="/admin/jobs/new" class="btn btn-success btn-sm ad-click-event">
          <i class="fa fa-plus"></i>
          Add
        </a>
      </p>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- /.box-header -->
      <div class="box-body">

      </div>
      <!-- /.box-body -->
    </section>
    <!-- /.content -->
  </div>
@endsection
@section('scripts')
<script>
  $(function () {
    $('#example').DataTable({
      "aaSorting": []
    })
  })
</script>
@endsection