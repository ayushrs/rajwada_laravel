@extends('admin.base_template')
@section('main')
<!-- Start content -->
<div class="content">
  <div class="container-fluid">

    <!-- Page title & breadcrumb -->
    <div class="row">
      <div class="col-sm-12">
        <div class="page-title-box">
          <h4 class="page-title">Registered Users</h4>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Users</a></li>
            <li class="breadcrumb-item active">View Users</li>
          </ol>
        </div>
      </div>
    </div>
    <!-- end row -->

    <!-- Users table -->
    <div class="page-content-wrapper">
      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">

              <!-- show success and error messages -->
              @if (session('success'))
              <div class="alert alert-success" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              @endif
              @if (session('error'))
              <div class="alert alert-danger" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              @endif
              <!-- End show success and error messages -->

              <div class="row">
                <div class="col-md-10">
                  <h4 class="mt-0 header-title">Registered Users List</h4>
                </div>
                {{-- Optional: Add User Button --}}
                {{-- 
                <div class="col-md-2"> 
                  <a class="btn btn-info" href="{{route('add_user_view')}}" role="button">Add User</a>
                </div> 
                --}}
              </div>
              <hr style="margin-bottom: 30px;background-color: darkgrey;">

              <div class="table-rep-plugin">
                <div class="table-responsive b-0" data-pattern="priority-columns">
                  <table id="usersTable" class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th data-priority="1">Name</th>
                        <th data-priority="2">Email</th>
                        <th data-priority="3">Registered At</th>
                        <th data-priority="4">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if(!empty($users))
                      @php $i = 0; @endphp
                      @foreach($users as $user)
                      @php $i++; @endphp
                      <tr>
                        <th>{{ $i }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                          <div class="btn-group" id="btns{{ $i }}">
                            {{-- <a href="{{ route('edit_user_view', base64_encode($user->id)) }}" data-toggle="tooltip" data-placement="top" title="Edit">
                              <i class="fas fa-edit info-icon"></i>
                            </a> --}}
                            <a href="javascript:void(0);" class="dCnf" mydata="{{ $i }}" data-toggle="tooltip" data-placement="top" title="Delete">
                              <i class="fas fa-trash danger-icon"></i>
                            </a>
                          </div>
                          <div style="display:none" id="cnfbox{{ $i }}">
                            <p>Are you sure you want to delete this user?</p>
                            {{-- <a href="{{ route('delete_user', base64_encode($user->id)) }}" class="btn btn-danger">Yes</a> --}}
                            <a href="javascript:void(0);" class="cans btn btn-default" mydatas="{{ $i }}">No</a>
                          </div>
                        </td>
                      </tr>
                      @endforeach
                      @else
                      <tr>
                        <td colspan="5" class="text-center">No users found</td>
                      </tr>
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- End table -->

            </div>
          </div>
        </div> <!-- end col -->
      </div> <!-- end row -->
    </div>
    <!-- end page content-->
  </div> <!-- container-fluid -->
</div> <!-- content -->

<script>
$(document).ready(function() {
    $('#usersTable').DataTable();
    
    // Delete confirmation
    $('.dCnf').click(function() {
        var mydata = $(this).attr('mydata');
        $('#btns' + mydata).hide();
        $('#cnfbox' + mydata).show();
    });
    
    $('.cans').click(function() {
        var mydatas = $(this).attr('mydatas');
        $('#btns' + mydatas).show();
        $('#cnfbox' + mydatas).hide();
    });
});
</script>
@endsection
