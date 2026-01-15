@extends('admin.base_template')
@section('main')
<!-- Start content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="page-title-box">
          <h4 class="page-title">View Products</h4>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Products</a></li>
            <li class="breadcrumb-item active">View Products</li>
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
                  <h4 class="mt-0 header-title">View Products List</h4>
                </div>
                <div class="col-md-2"> 
                  <a class="btn btn-info cticket" href="{{route('add_product_view')}}" role="button" style="margin-left: 20px;"> Add Product</a>
                </div>
              </div>
              <hr style="margin-bottom: 50px;background-color: darkgrey;">
              <div class="table-rep-plugin">
                <div class="table-responsive b-0" data-pattern="priority-columns">
                  <table id="productTable" class="table table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th data-priority="1">Name</th>
                        <th data-priority="2">Category</th>
                        <th data-priority="2">Subcategory</th>
                        <th data-priority="3">SKU</th>
                        <th data-priority="3">Price</th>
                        <th data-priority="3">Image</th>
                        <th data-priority="4">Stock</th>
                        <th data-priority="6">Status</th>
                        <th data-priority="6">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if(!empty($products))
                      @php $a = 0; @endphp
                      @foreach($products as $product)
                      @php $a++; @endphp
                      <tr>
                        <th>{{$a}}</th>
                        <td>{{ $product->name}}</td>
                        <td>{{ $product->category->name ?? 'N/A'}}</td>
                        <td>{{ $product->subcategory->name ?? 'N/A'}}</td>
                        <td>{{ $product->sku ?? 'N/A'}}</td>
                        <td>₹{{ number_format($product->price, 2)}}</td>
                        <td>
                          @if($product->image != "" && $product->image != null)
                          <img id="product_img" height="80" width="120" src="{{asset($product->image)}}" alt="{{$product->name}}">
                          @else
                          <span>No Image</span>
                          @endif
                        </td>
                        <td>{{ $product->stock_quantity ?? 0}}</td>
                        @if($product->is_active == 1)
                        <td>
                          <p class="label pull-right status-active">Active</p>
                        </td>
                        @else
                        <td>
                          <p class="label pull-right status-inactive">InActive</p>
                        </td>
                        @endif
                        <td>
                          <div class="btn-group" id="btns<?php echo $a ?>">
                            <a href="{{route('edit_product_view', base64_encode($product->id))}}" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit info-icon"></i></a>
                            @if ($product->is_active == 0)
                            <a href="{{route('update_product_status',['active',base64_encode($product->id)])}}" data-toggle="tooltip" data-placement="top" title="Active"><i class="fas fa-check success-icon"></i></a>
                            @else
                            <a href="{{route('update_product_status',['inactive',base64_encode($product->id)])}}" data-toggle="tooltip" data-placement="top" title="Inactive"><i class="fas fa-times danger-icon"></i></a>
                            @endif
                            <a href="javascript:void(0);" class="dCnf" mydata="<?php echo $a ?>" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash danger-icon"></i></a>
                          </div>
                          <div style="display:none" id="cnfbox<?php echo $a ?>">
                            <p>Are you sure you want to delete this product?</p>
                            <a href="{{route('delete_product',base64_encode($product->id))}}" class="btn btn-danger">Yes</a>
                            <a href="javascript:void(0);" class="cans btn btn-default" mydatas="<?php echo $a ?>">No</a>
                          </div>
                        </td>
                      </tr>
                      @endforeach
                      @else
                      <tr>
                        <td colspan="10" class="text-center">No products found</td>
                      </tr>
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>
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
    $('#productTable').DataTable();
    
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
