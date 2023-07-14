        <div class="row">
            <!-- Individual column searching (text inputs) Starts-->
            <div class="col-sm-12">
                <div class="col-md-12 project-list">
                    <div class="row">
                        <div class="col-md-6 p-0">

                        </div>
                        @if(!$addItem || !$updateItem)
                        <div class="col-md-6 p-0">
                            <div class="form-group mb-0 me-0"></div>
                            <button wire:ignore wire:click="addItem()" class="btn btn-primary" > <i data-feather="plus-square"> </i>Create New Item</button>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-12">
                    @if($addItem)
                        @include('cfms.item.create')
                    @endif
                    @if($updateItem)
                        @include('cfms.item.edit')
                    @endif
                </div>

                <div class="card">
                    <!-- User creating alert -->
                    <div class="row">
                        <div class="col-sm-12">
                            @if(session()->has('success'))
                            <div class="alert alert-primary alert-dismissible fade show" role="alert"><strong>{{ session()->get('success') }}</strong>
                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif
                            @if(session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>{{ session()->get('error') }}</strong>
                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="card-header pb-0">
                        <h5>Item List</h5>
                    </div>
                    <div class="card-header pb-0">
                    </div>
                    <div class="card-body">
                        <div class="table">
                            <table wire:ignore class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($items) > 0)
                                        @foreach ($items as $item)
                                            <tr>
                                                <td>{{$item->name}}</td>
                                                <td>{{$item->category->name}}</td>
                                                <td>
                                                    <div class="product-icon">
                                                        <ul class="product-social">
                                                            <li class="d-inline-block">
                                                                <a href="javascript:void(0)" wire:click="editItem({{$item->id}})" title="Edit item"><i class="fa fa-edit"></i></a>
                                                            </li>
                                                            <li class="d-inline-block">
                                                                <a href="javascript:void(0)" wire:click="deleteItem({{$item->id}})" title="Delete item"><i class="fa fa-trash-o"></i></a>
                                                            </li>
                                                        </ul>
                                                        <form class="d-inline-block f-right"></form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" align="center">
                                                No Item Found.
                                            </td>
                                        </tr>
                                    @endif
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

