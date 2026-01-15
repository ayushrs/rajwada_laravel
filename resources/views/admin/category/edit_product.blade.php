@extends('admin.base_template')
@section('main')
<!-- Start content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Edit Product</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Products</a></li>
                        <li class="breadcrumb-item active">Edit Product</li>
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
                            <h4 class="mt-0 header-title">Edit Product Form</h4>
                            <hr style="margin-bottom: 50px;background-color: darkgrey;">
                            <form action="{{route('update_product_process', base64_encode($product->id))}}" method="post" enctype="multipart/form-data" id="productForm">
                                @csrf
                                <!-- Basic Information -->
                                <h5 class="mb-3">Basic Information</h5>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <div class="form-floating">
                                            <select class="form-control" name="category_id" id="category_id" required>
                                                <option value="">Select Category</option>
                                                @if(!empty($categories))
                                                @foreach($categories as $category)
                                                <option value="{{$category->id}}" {{$product->category_id == $category->id ? 'selected' : ''}}>{{$category->name}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <label for="category_id">Category &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('category_id')
                                        <div style="color:red">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating">
                                            <select class="form-control" name="subcategory_id" id="subcategory_id" required>
                                                <option value="">Select Subcategory</option>
                                                @if(!empty($subcategories))
                                                @foreach($subcategories as $subcategory)
                                                <option value="{{$subcategory->id}}" {{$product->subcategory_id == $subcategory->id ? 'selected' : ''}}>{{$subcategory->name}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <label for="subcategory_id">Subcategory &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('subcategory_id')
                                        <div style="color:red">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" value="{{$product->name}}" id="name" name="name" placeholder="Enter Product Name" required>
                                            <label for="name">Product Name &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('name')
                                        <div style="color:red">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" value="{{$product->sku ?? ''}}" id="sku" name="sku" placeholder="SKU">
                                            <label for="sku">SKU (Optional)</label>
                                        </div>
                                        @error('sku')
                                        <div style="color:red">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-floating">
                                            <textarea class="form-control" id="short_description" name="short_description" placeholder="Short Description" rows="2">{{$product->short_description ?? ''}}</textarea>
                                            <label for="short_description">Short Description</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-floating">
                                            <textarea class="form-control" id="description" name="description" placeholder="Description" rows="4">{{$product->description ?? ''}}</textarea>
                                            <label for="description">Description</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pricing Information -->
                                <h5 class="mb-3 mt-4">Pricing Information</h5>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <div class="form-floating">
                                            <input type="number" step="0.01" class="form-control" value="{{$product->mrp ?? ''}}" id="mrp" name="mrp" placeholder="MRP">
                                            <label for="mrp">MRP</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-floating">
                                            <input type="number" step="0.01" class="form-control" value="{{$product->price}}" id="price" name="price" placeholder="Price" required>
                                            <label for="price">Price &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                        @error('price')
                                        <div style="color:red">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-floating">
                                            <input type="number" step="0.01" class="form-control" value="{{$product->gst_percentage ?? 0}}" id="gst_percentage" name="gst_percentage" placeholder="GST %">
                                            <label for="gst_percentage">GST Percentage (%)</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <div class="form-floating">
                                            <input type="number" step="0.01" class="form-control" value="{{$product->selling_price ?? ''}}" id="selling_price" name="selling_price" placeholder="Selling Price">
                                            <label for="selling_price">Selling Price</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Details -->
                                <h5 class="mb-3 mt-4">Product Details</h5>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" value="{{$product->stock_quantity ?? 0}}" id="stock_quantity" name="stock_quantity" placeholder="Stock">
                                            <label for="stock_quantity">Stock Quantity</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" value="{{$product->size ?? ''}}" id="size" name="size" placeholder="Size">
                                            <label for="size">Size</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" value="{{$product->color ?? ''}}" id="color" name="color" placeholder="Color">
                                            <label for="color">Color</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" value="{{$product->material ?? ''}}" id="material" name="material" placeholder="Material">
                                            <label for="material">Material</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" value="{{$product->brand ?? ''}}" id="brand" name="brand" placeholder="Brand">
                                            <label for="brand">Brand</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Images -->
                                <h5 class="mb-3 mt-4">Product Images</h5>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label class="form-label" for="image">Main Image</label>
                                        @if($product->image != "" && $product->image != null)
                                        <div class="mb-2">
                                            <img src="{{asset($product->image)}}" alt="Current Image" height="100" width="150" style="border: 1px solid #ddd; padding: 5px;">
                                        </div>
                                        @endif
                                        <input class="form-control" type="file" id="image" name="image" accept="image/jpeg,image/jpg,image/webp">
                                        <small class="form-text text-muted">Leave empty to keep current. JPEG, JPG, WEBP (Max: 2MB)</small>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="form-label" for="image2">Image 2</label>
                                        @if($product->image2 != "" && $product->image2 != null)
                                        <div class="mb-2">
                                            <img src="{{asset($product->image2)}}" alt="Current Image 2" height="100" width="150" style="border: 1px solid #ddd; padding: 5px;">
                                        </div>
                                        @endif
                                        <input class="form-control" type="file" id="image2" name="image2" accept="image/jpeg,image/jpg,image/webp">
                                        <small class="form-text text-muted">Leave empty to keep current. JPEG, JPG, WEBP (Max: 2MB)</small>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="form-label" for="image3">Image 3</label>
                                        @if($product->image3 != "" && $product->image3 != null)
                                        <div class="mb-2">
                                            <img src="{{asset($product->image3)}}" alt="Current Image 3" height="100" width="150" style="border: 1px solid #ddd; padding: 5px;">
                                        </div>
                                        @endif
                                        <input class="form-control" type="file" id="image3" name="image3" accept="image/jpeg,image/jpg,image/webp">
                                        <small class="form-text text-muted">Leave empty to keep current. JPEG, JPG, WEBP (Max: 2MB)</small>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="form-label" for="image4">Image 4</label>
                                        @if($product->image4 != "" && $product->image4 != null)
                                        <div class="mb-2">
                                            <img src="{{asset($product->image4)}}" alt="Current Image 4" height="100" width="150" style="border: 1px solid #ddd; padding: 5px;">
                                        </div>
                                        @endif
                                        <input class="form-control" type="file" id="image4" name="image4" accept="image/jpeg,image/jpg,image/webp">
                                        <small class="form-text text-muted">Leave empty to keep current. JPEG, JPG, WEBP (Max: 2MB)</small>
                                    </div>
                                </div>

                                <!-- Flags -->
                                <h5 class="mb-3 mt-4">Product Flags</h5>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_top" name="is_top" value="1" {{$product->is_top == 1 ? 'checked' : ''}}>
                                            <label class="form-check-label" for="is_top">Top Product</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{$product->is_featured == 1 ? 'checked' : ''}}>
                                            <label class="form-check-label" for="is_featured">Featured</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_new_arrival" name="is_new_arrival" value="1" {{$product->is_new_arrival == 1 ? 'checked' : ''}}>
                                            <label class="form-check-label" for="is_new_arrival">New Arrival</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-floating">
                                            <select class="form-control" name="is_active" id="is_active" required>
                                                <option value="1" {{$product->is_active == 1 ? 'selected' : ''}}>Active</option>
                                                <option value="0" {{$product->is_active == 0 ? 'selected' : ''}}>Inactive</option>
                                            </select>
                                            <label for="is_active">Status &nbsp;<span style="color:red;">*</span></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- SEO Information -->
                                <h5 class="mb-3 mt-4">SEO Information</h5>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" value="{{$product->meta_title ?? ''}}" id="meta_title" name="meta_title" placeholder="Meta Title">
                                            <label for="meta_title">Meta Title</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" value="{{$product->meta_keywords ?? ''}}" id="meta_keywords" name="meta_keywords" placeholder="Meta Keywords">
                                            <label for="meta_keywords">Meta Keywords</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-floating">
                                            <textarea class="form-control" id="meta_description" name="meta_description" placeholder="Meta Description" rows="2">{{$product->meta_description ?? ''}}</textarea>
                                            <label for="meta_description">Meta Description</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="w-100 text-center">
                                        <button type="submit" style="margin-top: 10px;" class="btn btn-danger"><i class="fa fa-save"></i> Update</button>
                                        <a href="{{route('view_products')}}" class="btn btn-secondary" style="margin-top: 10px;"><i class="fa fa-times"></i> Cancel</a>
                                    </div>
                                </div>
                            </form>
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
    // Load subcategories when category is selected
    $('#category_id').change(function() {
        var categoryId = $(this).val();
        $('#subcategory_id').html('<option value="">Loading...</option>');
        
        if(categoryId) {
            $.ajax({
                url: "{{route('get_subcategories')}}",
                type: 'GET',
                data: { category_id: categoryId },
                success: function(data) {
                    var currentSubcategoryId = "{{$product->subcategory_id}}";
                    $('#subcategory_id').html('<option value="">Select Subcategory</option>');
                    $.each(data, function(key, value) {
                        var selected = (value.id == currentSubcategoryId) ? 'selected' : '';
                        $('#subcategory_id').append('<option value="'+value.id+'" '+selected+'>'+value.name+'</option>');
                    });
                }
            });
        } else {
            $('#subcategory_id').html('<option value="">Select Subcategory</option>');
        }
    });

    // Auto-calculate selling price
    $('#price, #gst_percentage').on('input', function() {
        var price = parseFloat($('#price').val()) || 0;
        var gstPercentage = parseFloat($('#gst_percentage').val()) || 0;
        var gst = (price * gstPercentage) / 100;
        var sellingPrice = price + gst;
        
        if($('#selling_price').val() == '' || $('#selling_price').val() == 0) {
            $('#selling_price').val(sellingPrice.toFixed(2));
        }
    });
});
</script>
@endsection
