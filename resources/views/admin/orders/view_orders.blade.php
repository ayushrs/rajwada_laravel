@extends('admin.base_template')
@section('main')

<div class="content">
  <div class="container-fluid">

    <!-- Page Title -->
    <div class="row">
      <div class="col-sm-12">
        <div class="page-title-box">
          <h4 class="page-title">View Orders</h4>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Orders</a></li>
            <li class="breadcrumb-item active">View Orders</li>
          </ol>
        </div>
      </div>
    </div>

    <div class="page-content-wrapper">
      <div class="row">
        <div class="col-12">
          <div class="card m-b-20">
            <div class="card-body">

              {{-- Alerts --}}
              @if (session('success'))
                <div class="alert alert-success">
                  {{ session('success') }}
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
              @endif

              @if (session('error'))
                <div class="alert alert-danger">
                  {{ session('error') }}
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
              @endif

              <div class="row">
                <div class="col-md-12">
                  <h4 class="mt-0 header-title">Orders List</h4>
                </div>
              </div>

              <hr style="margin-bottom: 40px;background-color: darkgrey;">

              <div class="table-rep-plugin">
                <div class="table-responsive b-0">
                  <table id="ordersTable" class="table table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Order No</th>
                        <th>User</th>
                        <th>Total Amount</th>
                        <th>Payment</th>
                        <th>Order Status</th>
                        <th>Date</th>
                        <th>Action</th>
                      </tr>
                    </thead>

                    <tbody>
                      @if(!empty($orders))
                        @php $i = 0; @endphp
                        @foreach($orders as $order)
                          @php $i++; @endphp
                          <tr>
                            <td>{{ $i }}</td>
                            <td>#{{ $order->order_number }}</td>
                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                            <td>₹{{ number_format($order->total_amount, 2) }}</td>

                            <td>
                              <span class="badge badge-info">
                                {{ ucfirst($order->payment_method) }}
                              </span>
                            </td>

                            <td>
                              @if($order->order_status == 'delivered')
                                <span class="badge badge-success">Delivered</span>
                              @elseif($order->order_status == 'cancelled')
                                <span class="badge badge-danger">Cancelled</span>
                              @else
                                <span class="badge badge-warning">{{ ucfirst($order->order_status) }}</span>
                              @endif
                            </td>

                            <td>{{ $order->created_at->format('d M Y') }}</td>

                            <td>
                              {{-- <a href="{{ route('admin.orders.show', base64_encode($order->id)) }}" --}}
                                 data-toggle="tooltip" title="View Order">
                                <i class="fas fa-eye info-icon"></i>
                              </a>
                            </td>
                          </tr>
                        @endforeach
                      @else
                        <tr>
                          <td colspan="8" class="text-center">No orders found</td>
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

{{-- Datatable & JS --}}
<script>
$(document).ready(function() {
    $('#ordersTable').DataTable();
});
</script>

@endsection
