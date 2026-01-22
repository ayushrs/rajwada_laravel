@extends('admin.base_template')
@section('main')
<!-- Start content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Edit Combo Product</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Combo Products</a></li>
                        <li class="breadcrumb-item active">Edit Combo Product</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="page-content-wrapper">
            <div class="row">
                <div class="col-12">
                    <div class="card m-b-20">
                        <div class="card-body">
                            @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            @endif
                            @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            @endif
                            <h4 class="mt-0 header-title">Edit Combo Product Form</h4>
                            <hr style="margin-bottom: 50px;background-color: darkgrey;">
                            <form action="{{ route('update_combo_product_process', base64_encode($combo->id)) }}" method="post" enctype="multipart/form-data" id="comboProductForm">
                                @csrf
                                <h5 class="mb-3">Basic Information</h5>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $combo->name) }}" placeholder="Combo Name" required>
                                            <label for="name">Combo Name &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('name')<div style="color:red">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating">
                                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $combo->price) }}" placeholder="Price" required>
                                            <label for="price">Price &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('price')<div style="color:red">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating">
                                            <input type="number" step="0.01" class="form-control" id="mrp" name="mrp" value="{{ old('mrp', $combo->mrp) }}" placeholder="MRP">
                                            <label for="mrp">MRP</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-floating">
                                            <textarea class="form-control" id="description" name="description" placeholder="Description" rows="3">{{ old('description', $combo->description) }}</textarea>
                                            <label for="description">Description</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label class="form-label" for="image">Image</label>
                                        <input class="form-control" type="file" id="image" name="image" accept="image/jpeg,image/jpg,image/webp">
                                        <small class="form-text text-muted">JPEG, JPG, WEBP (Max: 2MB). Leave empty to keep current.</small>
                                        @if($combo->image)
                                        <div class="mt-2"><img src="{{ asset($combo->image) }}" alt="" height="60" width="80"></div>
                                        @endif
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $combo->sort_order ?? 0) }}" placeholder="Sort Order">
                                            <label for="sort_order">Sort Order</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-floating">
                                            <select class="form-control" name="is_active" id="is_active" required>
                                                <option value="1" {{ $combo->is_active == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ $combo->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            <label for="is_active">Status &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="mb-3 mt-4">Products in Combo</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="comboProductsTable">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th style="width:120px;">Quantity</th>
                                                <th style="width:80px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="comboProductsBody">
                                            @forelse($combo->products as $idx => $p)
                                            <tr class="combo-row-edit">
                                                <td>
                                                    <select class="form-control product-select" name="product_id[]">
                                                        <option value="">Select Product</option>
                                                        @foreach($products as $pr)
                                                        <option value="{{ $pr->id }}" {{ $pr->id == $p->id ? 'selected' : '' }}>{{ $pr->name }} (₹{{ number_format($pr->price ?? $pr->selling_price ?? 0, 2) }})</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="quantity[]" value="{{ $p->pivot->quantity ?? 1 }}" min="1" placeholder="Qty">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-danger remove-combo-row"><i class="fa fa-minus"></i></button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr class="combo-row">
                                                <td>
                                                    <select class="form-control product-select" name="product_id[]">
                                                        <option value="">Select Product</option>
                                                        @foreach($products as $pr)
                                                        <option value="{{ $pr->id }}">{{ $pr->name }} (₹{{ number_format($pr->price ?? $pr->selling_price ?? 0, 2) }})</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="quantity[]" value="1" min="1" placeholder="Qty">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success add-combo-row"><i class="fa fa-plus"></i></button>
                                                </td>
                                            </tr>
                                            @endforelse
                                            @if($combo->products->isNotEmpty())
                                            <tr class="combo-row">
                                                <td>
                                                    <select class="form-control product-select" name="product_id[]">
                                                        <option value="">Select Product</option>
                                                        @foreach($products as $pr)
                                                        <option value="{{ $pr->id }}">{{ $pr->name }} (₹{{ number_format($pr->price ?? $pr->selling_price ?? 0, 2) }})</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" name="quantity[]" value="1" min="1" placeholder="Qty">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success add-combo-row"><i class="fa fa-plus"></i></button>
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <p class="text-muted small">Add at least one product to this combo. Use + to add more rows, − to remove.</p>

                                <div class="form-group">
                                    <div class="w-100 text-center">
                                        <button type="submit" style="margin-top: 10px;" class="btn btn-danger"><i class="fa fa-save"></i> Update</button>
                                        <a href="{{ route('view_combo_products') }}" class="btn btn-secondary" style="margin-top: 10px;"><i class="fa fa-times"></i> Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    function addRow() {
        var $addRow = $('#comboProductsBody tr.combo-row').first();
        if (!$addRow.length) $addRow = $('#comboProductsBody tr').last();
        var $clone = $addRow.clone();
        $clone.removeClass('combo-row-edit').addClass('combo-row');
        $clone.find('select').val('');
        $clone.find('input[name="quantity[]"]').val(1);
        var $btn = $clone.find('.add-combo-row, .remove-combo-row');
        $btn.removeClass('add-combo-row btn-success remove-combo-row btn-danger').addClass('remove-combo-row btn-danger').html('<i class="fa fa-minus"></i>');
        $('#comboProductsBody').append($clone);
    }
    $(document).on('click', '.add-combo-row', addRow);
    $(document).on('click', '.remove-combo-row', function() {
        if ($('#comboProductsBody tr').length > 1) {
            $(this).closest('tr').remove();
        }
    });
});
</script>
@endsection
