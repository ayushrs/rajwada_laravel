@extends('admin.base_template')
@section('main')
<!-- Start content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="page-title-box">
          <h4 class="page-title">View Combo Products</h4>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Combo Products</a></li>
            <li class="breadcrumb-item active">View Combo Products</li>
          </ol>
        </div>
      </div>
    </div>
    <!-- end row -->
    <div class="page-content-wrapper">
      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">
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
              <div class="row">
                <div class="col-md-10">
                  <h4 class="mt-0 header-title">View Combo Products List</h4>
                </div>
                <div class="col-md-2">
                  <a class="btn btn-info cticket" href="{{ route('add_combo_product_view') }}" role="button" style="margin-left: 20px;">Add Combo Product</a>
                </div>
              </div>
              <hr style="margin-bottom: 50px;background-color: darkgrey;">
              <div class="table-rep-plugin">
                <div class="table-responsive b-0" data-pattern="priority-columns">
                  <table id="comboProductTable" class="table table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th data-priority="1">Name</th>
                        <th data-priority="2">Price</th>
                        <th data-priority="3">MRP</th>
                        <th data-priority="3">Products</th>
                        <th data-priority="3">Image</th>
                        <th data-priority="4">Sort</th>
                        <th data-priority="6">Status</th>
                        <th data-priority="6">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if(!empty($combos))
                      @php $a = 0; @endphp
                      @foreach($combos as $combo)
                      @php $a++; @endphp
                      <tr>
                        <th>{{ $a }}</th>
                        <td>{{ $combo->name }}</td>
                        <td>₹{{ number_format($combo->price, 2) }}</td>
                        <td>₹{{ $combo->mrp ? number_format($combo->mrp, 2) : '–' }}</td>
                        <td>{{ $combo->products->count() }} item(s)</td>
                        <td>
                          @if($combo->image)
                          <img id="combo_img" height="50" width="80" src="{{ asset($combo->image) }}" alt="{{ $combo->name }}">
                          @else
                          <span>No Image</span>
                          @endif
                        </td>
                        <td>{{ $combo->sort_order ?? 0 }}</td>
                        @if($combo->is_active == 1)
                        <td><p class="label pull-right status-active">Active</p></td>
                        @else
                        <td><p class="label pull-right status-inactive">InActive</p></td>
                        @endif
                        <td>
                          <div class="btn-group" id="btns{{ $a }}">
                            <a href="{{ route('edit_combo_product_view', base64_encode($combo->id)) }}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit info-icon"></i></a>
                            @if ($combo->is_active == 0)
                            <a href="{{ route('update_combo_product_status', ['active', base64_encode($combo->id)]) }}" data-toggle="tooltip" data-placement="top" title="Active"><i class="fas fa-check success-icon"></i></a>
                            @else
                            <a href="{{ route('update_combo_product_status', ['inactive', base64_encode($combo->id)]) }}" data-toggle="tooltip" data-placement="top" title="Inactive"><i class="fas fa-times danger-icon"></i></a>
                            @endif
                            <a href="javascript:void(0);" class="dCnf" mydata="{{ $a }}" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash danger-icon"></i></a>
                          </div>
                          <div style="display:none" id="cnfbox{{ $a }}">
                            <p>Are you sure you want to delete this combo product?</p>
                            <a href="{{ route('delete_combo_product', base64_encode($combo->id)) }}" class="btn btn-danger">Yes</a>
                            <a href="javascript:void(0);" class="cans btn btn-default" mydatas="{{ $a }}">No</a>
                          </div>
                        </td>
                      </tr>
                      @endforeach
                      @else
                      <tr>
                        <td colspan="9" class="text-center">No combo products found</td>
                      </tr>
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    $('#comboProductTable').DataTable();

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
