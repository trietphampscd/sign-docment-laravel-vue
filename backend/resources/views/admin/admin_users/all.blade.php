@extends('admin.template')
@section('styles')
@section('title', 'Add user')
@endsection
@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        List Admin
        <small></small>
      </h1>
      <p class="pull-right">
        <a href="/admin/admin-users/new" class="btn btn-success btn-sm ad-click-event">
          <i class="fa fa-plus"></i>
          Add
        </a>
      </p>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- /.box-header -->
      <div class="box-body">
        <table id="example" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th width="">Full Name</th>
              <th>Email</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($list as $item)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $item->name }}</td>
              <td>{{ $item->email }}</td>
              <td>
                @if($item->active == 1)
                  <span class="label label-success">Active</span>
                @elseif($item->status == 0)
                  <span class="label label-danger">Inactive</span>
                @endif
              </td>
              <td class="btn-act">
                  <a href="/admin/admin-users/{{ $item->id }}/update" id="edit3986" type="button" class="btn btn-primary edit-record">
                      <i class="fa fa-edit"></i>
                  </a>
                  <button href="#" data-toggle="modal" data-target="#confirmDelete{{ $item->id }}" type="button" class="btn btn-danger del-record" alt="3986"><i class="fa fa-remove"></i></button>
              </td>
            </tr>
            <!-- Modal confirm delete -->
            <div class="modal fade" id="confirmDelete{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span></button>
                      <h4 class="modal-title">Are you want to delete !</h4>
                    </div>
                    <div class="modal-footer">
                        <form action="/admin/admin-users/delete/{{ $item->id }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                            <button class="btn btn-danger">Yes</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>
            @endforeach
          </tfoot>
        </table>
      </div>
      <!-- /.box-body -->
    </section>
    <!-- /.content -->
  </div>
@endsection
@section('scripts')
<script>
  $(function () {
    $('#example').DataTable()
  })
</script>
@endsection