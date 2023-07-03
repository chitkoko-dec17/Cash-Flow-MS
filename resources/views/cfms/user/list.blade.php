@extends('cfms.layouts.admin.master')

@section('title')User list
 {{ $title }}
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/owlcarousel.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/rating.css')}}">
@endpush

@section('content')
	<div class="container-fluid list-products">
        <!-- User creating alert -->
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-primary alert-dismissible fade show" role="alert"><strong>User sucessfully added !</strong>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Failed to add new user !</strong>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
                <div class="col-md-12 project-list">
                    <div class="row">
                        <div class="col-md-6 p-0">
                            <h3>User List</h3>
                        </div>
                        <div class="col-md-6 p-0">
                            <div class="form-group mb-0 me-0"></div>
                            <a class="btn btn-primary" href="{{ route('create-user') }}"> <i data-feather="plus-square"> </i>Create New User</a>
                        </div>
                    </div>
                </div>
	            <div class="card">
	                <div class="card-header pb-0">
	                </div>
	                <div class="card-body">
	                    <div class="table-responsive product-table">
	                        <table class="display" id="basic-1">
	                            <thead>
	                                <tr>
	                                    <th>Name</th>
	                                    <th>Role</th>
	                                    <th>Email</th>
	                                    <th>Phone</th>
	                                    <th>Address</th>
	                                    <th>Action</th>
	                                </tr>
	                            </thead>
	                            <tbody>
	                                <tr>
	                                    <td>
	                                        Nyan Lynn Htun
	                                    </td>
	                                    <td>
	                                        <a href="#"> <h6>Red Lipstick</h6></a><span>Interchargebla lens Digital Camera with APS-C-X Trans CMOS Sens</span>
	                                    </td>
	                                    <td>$10</td>
	                                    <td class="font-success">In Stock</td>
	                                    <td>2011/04/25</td>
	                                    <td>
                                            <div class="product-icon">
                                                <ul class="product-social">
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="View User"><i class="fa fa-eye"></i></a>
                                                    </li>
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="Edit user"><i class="fa fa-edit"></i></a>
                                                    </li>
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="Delete user"><i class="fa fa-trash-o"></i></a>
                                                    </li>
                                                </ul>
                                                <form class="d-inline-block f-right"></form>
                                            </div>
	                                    </td>
	                                </tr>
	                                <tr>
	                                    <td>
	                                        Nyan Lynn Htun
	                                    </td>
	                                    <td>
	                                        <a href="#"> <h6>Red Lipstick</h6></a><span>Interchargebla lens Digital Camera with APS-C-X Trans CMOS Sens</span>
	                                    </td>
	                                    <td>$10</td>
	                                    <td class="font-success">In Stock</td>
	                                    <td>2011/04/25</td>
	                                    <td>
                                            <div class="product-icon">
                                                <ul class="product-social">
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="View User"><i class="fa fa-eye"></i></a>
                                                    </li>
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="Edit user"><i class="fa fa-edit"></i></a>
                                                    </li>
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="Delete user"><i class="fa fa-trash-o"></i></a>
                                                    </li>
                                                </ul>
                                                <form class="d-inline-block f-right"></form>
                                            </div>
	                                    </td>
	                                </tr>
	                                <tr>
	                                    <td>
	                                        Nyan Lynn Htun
	                                    </td>
	                                    <td>
	                                        <a href="#"> <h6>Red Lipstick</h6></a><span>Interchargebla lens Digital Camera with APS-C-X Trans CMOS Sens</span>
	                                    </td>
	                                    <td>$10</td>
	                                    <td class="font-success">In Stock</td>
	                                    <td>2011/04/25</td>
	                                    <td>
                                            <div class="product-icon">
                                                <ul class="product-social">
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="View User"><i class="fa fa-eye"></i></a>
                                                    </li>
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="Edit user"><i class="fa fa-edit"></i></a>
                                                    </li>
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="Delete user"><i class="fa fa-trash-o"></i></a>
                                                    </li>
                                                </ul>
                                                <form class="d-inline-block f-right"></form>
                                            </div>
	                                    </td>
	                                </tr>
	                                <tr>
	                                    <td>
	                                        Nyan Lynn Htun
	                                    </td>
	                                    <td>
	                                        <a href="#"> <h6>Red Lipstick</h6></a><span>Interchargebla lens Digital Camera with APS-C-X Trans CMOS Sens</span>
	                                    </td>
	                                    <td>$10</td>
	                                    <td class="font-success">In Stock</td>
	                                    <td>2011/04/25</td>
	                                    <td>
                                            <div class="product-icon">
                                                <ul class="product-social">
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="View User"><i class="fa fa-eye"></i></a>
                                                    </li>
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="Edit user"><i class="fa fa-edit"></i></a>
                                                    </li>
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="Delete user"><i class="fa fa-trash-o"></i></a>
                                                    </li>
                                                </ul>
                                                <form class="d-inline-block f-right"></form>
                                            </div>
	                                    </td>
	                                </tr>
	                                <tr>
	                                    <td>
	                                        Nyan Lynn Htun
	                                    </td>
	                                    <td>
	                                        <a href="#"> <h6>Red Lipstick</h6></a><span>Interchargebla lens Digital Camera with APS-C-X Trans CMOS Sens</span>
	                                    </td>
	                                    <td>$10</td>
	                                    <td class="font-success">In Stock</td>
	                                    <td>2011/04/25</td>
	                                    <td>
                                            <div class="product-icon">
                                                <ul class="product-social">
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="View User"><i class="fa fa-eye"></i></a>
                                                    </li>
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="Edit user"><i class="fa fa-edit"></i></a>
                                                    </li>
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="Delete user"><i class="fa fa-trash-o"></i></a>
                                                    </li>
                                                </ul>
                                                <form class="d-inline-block f-right"></form>
                                            </div>
	                                    </td>
	                                </tr>
	                                <tr>
	                                    <td>
	                                        Nyan Lynn Htun
	                                    </td>
	                                    <td>
	                                        <a href="#"> <h6>Red Lipstick</h6></a><span>Interchargebla lens Digital Camera with APS-C-X Trans CMOS Sens</span>
	                                    </td>
	                                    <td>$10</td>
	                                    <td class="font-success">In Stock</td>
	                                    <td>2011/04/25</td>
	                                    <td>
                                            <div class="product-icon">
                                                <ul class="product-social">
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="View User"><i class="fa fa-eye"></i></a>
                                                    </li>
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="Edit user"><i class="fa fa-edit"></i></a>
                                                    </li>
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="Delete user"><i class="fa fa-trash-o"></i></a>
                                                    </li>
                                                </ul>
                                                <form class="d-inline-block f-right"></form>
                                            </div>
	                                    </td>
	                                </tr>
	                                <tr>
	                                    <td>
	                                        Nyan Lynn Htun
	                                    </td>
	                                    <td>
	                                        <a href="#"> <h6>Red Lipstick</h6></a><span>Interchargebla lens Digital Camera with APS-C-X Trans CMOS Sens</span>
	                                    </td>
	                                    <td>$10</td>
	                                    <td class="font-success">In Stock</td>
	                                    <td>2011/04/25</td>
	                                    <td>
                                            <div class="product-icon">
                                                <ul class="product-social">
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="View User"><i class="fa fa-eye"></i></a>
                                                    </li>
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="Edit user"><i class="fa fa-edit"></i></a>
                                                    </li>
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="Delete user"><i class="fa fa-trash-o"></i></a>
                                                    </li>
                                                </ul>
                                                <form class="d-inline-block f-right"></form>
                                            </div>
	                                    </td>
	                                </tr>
	                                <tr>
	                                    <td>
	                                        Nyan Lynn Htun
	                                    </td>
	                                    <td>
	                                        <a href="#"> <h6>Red Lipstick</h6></a><span>Interchargebla lens Digital Camera with APS-C-X Trans CMOS Sens</span>
	                                    </td>
	                                    <td>$10</td>
	                                    <td class="font-success">In Stock</td>
	                                    <td>2011/04/25</td>
	                                    <td>
                                            <div class="product-icon">
                                                <ul class="product-social">
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="View User"><i class="fa fa-eye"></i></a>
                                                    </li>
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="Edit user"><i class="fa fa-edit"></i></a>
                                                    </li>
                                                    <li class="d-inline-block">
                                                        <a href="javascript:void(0)" title="Delete user"><i class="fa fa-trash-o"></i></a>
                                                    </li>
                                                </ul>
                                                <form class="d-inline-block f-right"></form>
                                            </div>
	                                    </td>
	                                </tr>
                                </tbody>
	                        </table>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <!-- Individual column searching (text inputs) Ends-->
	    </div>
	</div>

	@push('scripts')
	<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/rating/jquery.barrating.js')}}"></script>
    <script src="{{asset('assets/js/rating/rating-script.js')}}"></script>
    <script src="{{asset('assets/js/owlcarousel/owl.carousel.js')}}"></script>
    <script src="{{asset('assets/js/ecommerce.js')}}"></script>
    <script src="{{asset('assets/js/product-list-custom.js')}}"></script>
	@endpush

@endsection
