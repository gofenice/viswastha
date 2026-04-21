@extends('Admin.admin_header')
@section('title', 'vishwastha  | Product')
@section('content')
    <style>
        .radio {
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .packagelist {
            margin: 0 auto;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Product</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Product</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Horizontal Form -->
        <div class="card card-info col-md-8" style="margin: 0 auto;">
            <div class="card-header">
                <h3 class="card-title">Add Product</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" method="POST" action="{{ route('add_product') }}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label for="productName" class="col-sm-4 col-form-label">Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="productName" name="productName"
                                placeholder="Enter the product name" required>
                            @error('productName')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="package_id" class="col-sm-4 col-form-label">Package</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="package_id" name="package_id" required>
                                <option>--Choose Package--</option>
                                @foreach ($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->name }} - {{ $package->amount }}
                                    </option>
                                @endforeach
                            </select>
                            @error('package_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="product_image" class="col-sm-4 col-form-label">Image</label>
                        <div class="col-sm-8">
                            <input type="file" class="form-control" id="product_image" name="product_image"
                                placeholder="Enter the product name" required>
                            @error('product_image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="product_control" class="col-sm-4 col-form-label">Product control</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="product_control" name="product_control" required>
                                <option value="1">Managed by vishwastha</option>
                                <option value="2">Managed by Dque</option>
                                <option value="3">Managed by Impact</option>
                            </select>
                            @error('product_control')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="product_description" class="col-sm-4 col-form-label">Description</label>
                        <div class="col-sm-12">
                            <textarea class="textarea" name="product_description" placeholder="Place some text here"
                                style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                            @error('product_description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-info float-right">Add Product</button>
                </div>
                <!-- /.card-footer -->
            </form>
        </div>
        <!-- /.card -->
        <div class="card mt-3 packagelist col-md-11">
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Product code</th>
                            <th>Package</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->product_code }}</td>
                                <td>{{ $product->package->name }}</td>
                                <td>{{ $product->product_status ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <button class="btn btn-success btn-sm" data-toggle="modal"
                                        data-target="#editProductModal"
                                        onclick="editProduct('{{ $product->id }}', '{{ $product->product_name }}', '{{ $product->product_code }}', '{{ $product->package->id }}', '{{ $product->product_image }}', '{{ $product->product_control }}', '{{ $product->product_description }}', '{{ $product->product_status }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#deleteproductModal" onclick="productDelete('{{ $product->id }}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>

        {{-- Edit product modal --}}
        <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="editProductForm" method="POST" action="{{ route('edit_product') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body row">
                            <div class="form-group col-md-6">
                                <input type="hidden" class="form-control" id="packageId" name="id">
                                <label for="editName">Product Name</label>
                                <input type="text" class="form-control" id="editName" name="name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="editPackage">Package</label>
                                <select class="form-control" id="editPackage" name="package" required>
                                    @foreach ($packages as $package)
                                        <option value="{{ $package->id }}"
                                            {{ $package->package_id == $package->id ? 'selected' : '' }}>
                                            {{ $package->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12 row">
                                <label for="productImage" class="col-md-12">Product Image</label>
                                <div class="col-sm-6 d-flex">
                                    <img id="productImagePreview" src="" alt="Product Image"
                                        class="img-thumbnail mt-2 mx-auto" style="max-width: 150px;">
                                </div>
                                <div class="col-sm-6">
                                    <input type="file" class="form-control" name="product_image">
                                    <input type="hidden" class="form-control" id="productImage"
                                        name="product_image_old">
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Status</label><br>
                                <input type="radio" name="status" id="status_active" value="1"> Active
                                <input type="radio" name="status" id="status_inactive" value="0"> Inactive
                            </div>
                            <div class="form-group col-md-6">
                                <label for="product_control">Product Control</label>
                                <select class="form-control" id="product_control" name="product_control" required>
                                    <option value="1">Internal</option>
                                    <option value="2">External</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="product_description">Product description</label>
                                <textarea class="textarea" id="product_description" name="product_description" placeholder="Place some text here"
                                    style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Delete product modal --}}
        <div class="modal fade" id="deleteproductModal" tabindex="-1" role="dialog"
            aria-labelledby="deleteproductModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteproductModal">Confirm Delete</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="deleteMessage">
                            Are you sure you want to delete this product?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <form id="deleteProductForm" method="POST" action="{{ route('delete_product') }}">
                            @csrf
                            <input type="hidden" class="form-control" id="product_id" name="product_id">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    @if (session()->has('success'))
        <script>
            Swal.fire({
                position: "top-center",
                icon: "success",
                title: "{{ session()->get('success') }}",
                showConfirmButton: false,
                timer: 1500
            });
        </script>
    @endif
    <script>
        function editProduct(id, name, productCode, packageName, productImage, product_control, product_description,
        status) {
            var modal = $('#editProductModal');

            modal.find('#packageId').val(id);
            modal.find('#editName').val(name);
            modal.find('#editCode').val(productCode);
            modal.find('#editPackage').val(packageName);
            modal.find('#productImage').val(productImage);
            if (productImage) {
                modal.find('#productImagePreview').attr('src', productImage);
            }

            // Set product_control dropdown
            modal.find('#product_control').val(product_control);

            // Set product_description textarea
            modal.find('#product_description').summernote('code', product_description);

            modal.find('#status_active').prop('checked', status == 1);
            modal.find('#status_inactive').prop('checked', status == 0);
            modal.find('.error-message').text("");

            modal.modal('toggle');
        }

        function productDelete(product_id, name) {
            var packageId = $('#deleteproductModal').find('#product_id');
            packageId.val(product_id);
            $('#deleteproductModal').modal('toggle');
        }
        $(function() {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
            });
        });

        $(document).ready(function() {
            const pinLink = $('.nav-link.product');
            const treeviewLink = $('.nav.nav-treeview.product');
            const mainLiLink = $('.nav-item.has-treeview.product');
            const viewpinLink = $('.nav-link.addproduct');
            if (viewpinLink.length) {
                viewpinLink.addClass('active');
                pinLink.addClass('active');
                mainLiLink.addClass('menu-open');
                treeviewLink.css('display', 'block');
            }
        });
    </script>
    <script>
        $(function() {
            // Summernote
            $('.textarea').summernote()
        })
    </script>
@endsection
