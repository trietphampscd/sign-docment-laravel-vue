@extends('admin.template')
@section('styles')
@section('title', 'List Contract')
@endsection
@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        List User
        <small></small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- /.box-header -->
      <div class="box-body">
        <table id="example" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th width="">Name Creator</th>
              <th>Amount File</th>
              <th>Status</th>
              <th>Update At</th>
<<<<<<< HEAD
=======
              <th>Owner</th>
>>>>>>> duong
              <th>Recipients</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          @foreach ($list as $item)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $item->name }}</td>
              <td>{{ $item->amount }}</td>
              <td>
                {{ $item->status }}
              </td>
              <td>
                {{ $item->updated_at }}
              </td>
              <td>
<<<<<<< HEAD
              <button href="#" data-toggle="modal" data-target="#viewRecipients{{ $item->document_id }}" type="button" class="btn btn-primary view-record" alt="3986" title="view">Recipients</button>
=======
                <button href="#" data-toggle="modal" data-target="#viewOwner{{ $item->document_id }}" type="button" class="btn btn-primary view-record" alt="3986" title="view">Owner</button>
              </td>
              <td>
                <button href="#" data-toggle="modal" data-target="#viewRecipients{{ $item->document_id }}" type="button" class="btn btn-primary view-record" alt="3986" title="view">Recipients</button>
>>>>>>> duong
              </td>
              <td class="btn-act">
                  <button href="#" data-toggle="modal" data-target="#confirmDelete{{ $item->document_id }}" type="button" class="btn btn-danger del-record" alt="3986"><i class="fa fa-remove"></i></button>
              </td>
            </tr>
            <!-- Modal confirm delete -->
            <div class="modal fade" id="confirmDelete{{ $item->document_id }}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel{{ $item->document_id }}" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span></button>
                      <h4 class="modal-title">Are you want to delete !</h4>
                    </div>
                    <div class="modal-footer">
                        <form action="/admin/contracts/delete/{{ $item->document_id }}" method="POST">
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

      @foreach ($list as $item)
<<<<<<< HEAD
=======
      <!-- Recipients -->
>>>>>>> duong
      <div class="modal fade" id="viewRecipients{{ $item->document_id }}" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">List Recipients</h4>
                </div>
                <div class="modal-body">
                  <table class="table table-striped" id="tblGrid">
                    <thead id="tblHead">
                      <tr>
                        <th>Name</th>
                        <th>Email or Phone</th>
                        <th>Action</th>
                        <th class="text-right">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($item['recipients'] as $recipient)
                      <tr>
                        <td>{{$recipient['name']}}</td>
                        <td>
                          <p>{{$recipient['email']}}</p>
                          <p>{{$recipient['phone']}}</p>
                        </td>
                        <td>{{$recipient['action']}}</td>
                        <td class="text-right">
                           @if($recipient['status']==0) waiting @endif
                           @if($recipient['status']==1) complete @endif
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- Owner -->
      <div class="modal fade" id="viewOwner{{ $item->document_id }}" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Infomation Owner</h4>
                </div>
                <div class="modal-body">
                  <table class="table table-striped" id="tblGrid">
                    <thead id="tblHead">
                      <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Acount Type</th>
                        <th class="text-right">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>{{$item['create']['name']}}</td>
                        <td>
                          <p>{{$item['create']['email']}}</p>
                        </td>
                        <td>{{$item['create']['client']['account_type']}}</td>
                        <td class="text-right">
                           @if($item['create']['active']==0) inactive @endif
                           @if($item['create']['active']==1) active @endif
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      @endforeach 
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