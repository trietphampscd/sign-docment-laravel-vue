@extends('admin.template')
@section('styles')
@section('title', 'Add user')
@endsection
@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        List User
        <small></small>
      </h1>
      <p class="pull-right">
        <a href="/admin/users/new" class="btn btn-success btn-sm ad-click-event">
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
              <th>Profile info</th>
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
                @if($item->client)
                  {{-- <button href="#" data-toggle="modal" data-target="#clientInfo{{ $item->id }}" type="button" class="btn btn-success del-record" alt="3986">Complete</button> --}}
                  <a href="#" data-toggle="modal" data-target="#clientInfo{{ $item->id }}"><span class="label label-success" title="view">Complete</span><a>
                @else
                <span class="label label-danger">Incomplete</span>
                @endif
              </td>
              <td>
                @if($item->active == 1)
                  <span class="label label-success">Active</span>
                @elseif($item->status == 0)
                  <span class="label label-danger">Inactive</span>
                @endif
              </td>
              <td class="btn-act">
                  <a href="/admin/users/{{ $item->id }}/update" id="edit3986" type="button" class="btn btn-primary edit-record">
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
                        <form action="/admin/users/delete/{{ $item->id }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal">No</button>
                            <button class="btn btn-danger">Yes</button>
                        </form>
                    </div>
                </div>
                </div>
            </div>

            <div class="modal fade" id="clientInfo{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="clientInfoLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span></button>
                      <h4 class="modal-title">Profile info</h4>
                    </div>
                    <div class="modal-body">
                          <div class="row">
                              <div class="col-sm-6">
                                  <div class="form-group">
                                    <label for="sel1">First Name</label>
                                    <input disabled value="{{$item['first_name']}}" type="text" id="first_name" placeholder="First Name" name="first_name" class="form-control">
                                  </div>
                              </div>
                              <div class="col-sm-6">
                                  <div class="form-group">
                                    <label for="sel1">Last Name</label>
                                    <input disabled value="{{$item['last_name']}}" type="text" id="last_name" placeholder="Last Name" name="last_name" class="form-control">
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                            <label for="sel1">Purpose of using</label>
                            <select disabled class="form-control" id="sel1">
                              <option @if($item['client']['account_type'] == 'Personnel') selected @endif>My Personnel use</option>
                              <option @if($item['client']['account_type'] == 'Business') selected @endif>My Business</option>
                            </select>
                          </div>
                          @if($item['client']['account_type'] == 'Business')
                          <div class="row">
                              <div class="col-sm-6">
                                  <div class="form-group">
                                    <label for="company">Company</label>
                                    <input type="text" disabled value="{{$item['client']['company_name']}}" id="company" placeholder="Company" name="company" class="form-control">
                                  </div>
                              </div>
                              <div class="col-sm-6">
                                <label for="sel1">Number of Employees</label>
                                <select class="form-control" id="sel1" disabled>
                                @foreach($listCompanySize as $size)
                                  <option disabled @if($size['id'] == $item['client']['company_size_id']) selected @endif>{{$size['size']}}</option>
                                @endforeach
                                </select>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-sm-6">
                                <label for="sel1">Job Title</label>
                                <select class="form-control" id="sel1" disabled>
                                @foreach($listDepartment as $department)
                                  <option  @if($department['id'] == $item['client']['department_id']) selected @endif>{{$department['department_name']}}</option>
                                @endforeach
                                </select>
                              </div>
                              <div class="col-sm-6">
                                <label for="sel1">Select Industry</label>
                                <select class="form-control" id="sel1" disabled>
                                @foreach($listIndustries as $industry)
                                  <option  @if($industry['id'] == $item['client']['industry_id']) selected @endif>{{$industry['industry_name']}}</option>
                                @endforeach
                                </select>
                              </div>
                          </div>
                          @endif
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Exit</button>
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