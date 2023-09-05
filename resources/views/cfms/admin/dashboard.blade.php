@extends('cfms.layouts.admin.master')

@section('title', 'Dashboard')

@push('breadcrumb')
    <li class="breadcrumb-item">Pages</li>
    <li class="breadcrumb-item active">Sample Page</li>
@endpush

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/chartist.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/prism.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vector-map.css') }}">
    <style>
        .recent-order-sec .table td:first-child {
            min-width: fit-content !important;
        }
    </style>
@endpush
@section('content')
    @yield('breadcrumb-list')
    <!-- Container-fluid starts-->
    <div class="container-fluid dashboard-default-sec">
        <div class="row">
            <div class="col-sm-6 col-xl-3 col-lg-6">
                <a href="{{route('business-unit.index')}}">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="database"></i></div>
                                <div class="media-body">
                                    <span class="m-0">Create Business Unit</span>
                                    <h4 class="mb-0 counter">33</h4>
                                    <i class="icon-bg" data-feather="database"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3 col-lg-6">
                <a href="{{route('expense-invoice.index')}}">
                    <div class="card o-hidden border-0">
                        <div class="bg-secondary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="shopping-bag"></i></div>
                                <div class="media-body">
                                    <span class="m-0">Create Expense Invoices</span>
                                    <h4 class="mb-0 counter">42</h4>
                                    <i class="icon-bg" data-feather="shopping-bag"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3 col-lg-6">
                <a href="{{route('income-invoice.index')}}">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="file"></i></div>
                                <div class="media-body">
                                    <span class="m-0">Create Income Invoices</span>
                                    <h4 class="mb-0 counter">32</h4>
                                    <i class="icon-bg" data-feather="file"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3 col-lg-6">
                <a href="{{url('user')}}">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="user-plus"></i></div>
                                <div class="media-body">
                                    <span class="m-0">Create New User</span>
                                    <h4 class="mb-0 counter">123</h4>
                                    <i class="icon-bg" data-feather="user-plus"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-md-12 box-col-12">
                <div class="email-right-aside bookmark-tabcontent contacts-tabs">
                    <div class="card email-body radius-left">
                        <div class="ps-0">
                            <div class="tab-content">
                                <div class="tab-pane fade active show" id="pills-personal" role="tabpanel"
                                    aria-labelledby="pills-personal-tab">
                                    <div class="card mb-0">
                                        <div class="card-header d-flex">
                                            <h5>Business Unit</h5>
                                            <span class="f-14 pull-right mt-0">{{ count($businessUnits) }} Business
                                                Unit</span>
                                        </div>
                                        <div class="card-body p-0">
                                            @if (count($businessUnits) > 0)
                                                <div class="row list-persons" id="addcon">
                                                    <div class="col-xl-3 col-lg-2 xl-30 col-md-2 box-col-2">
                                                        <div class="nav flex-column nav-pills" id="v-pills-tab"
                                                            role="tablist" aria-orientation="vertical">
                                                            @foreach ($businessUnits as $key => $businessUnit)
                                                                <a class="contact-tab-{{ $key }} nav-link{{ $key === 0 ? ' active' : '' }}"
                                                                    id="v-pills-business-{{ $key }}-tab"
                                                                    data-bs-toggle="pill"
                                                                    onclick="loadData({{ $key }},{{$businessUnit->id}})"
                                                                    href="#v-pills-business-{{ $key }}"
                                                                    role="tab"
                                                                    aria-controls="v-pills-business-{{ $key }}"
                                                                    aria-selected="{{ $key === 0 ? 'true' : 'false' }}">
                                                                    <div class="media">
                                                                        <img class="img-50 img-fluid m-r-20 rounded-circle update_img_{{ $key }}"
                                                                            src="{{ asset('storage/' . $businessUnit->bu_image) }}"
                                                                            alt="" />
                                                                        <div class="media-body">
                                                                            <h6>
                                                                                <span
                                                                                    class="first_name_{{ $key }}">{{ $businessUnit->name }}</span>
                                                                            </h6>
                                                                            <p class="email_add_{{ $key }}">
                                                                                {{ $businessUnit->phone }}</p>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-9 col-lg-10 xl-70 col-md-8 col-md-10 box-col-10">
                                                        <div class="tab-content" id="v-pills-tabContent">
                                                            @foreach ($businessUnits as $key => $businessUnit)
                                                                <div class="tab-pane contact-tab-{{ $key }} tab-content-child fade {{ $key === 0 ? ' show active' : '' }}"
                                                                    id="v-pills-business-{{ $key }}"
                                                                    role="tabpanel"
                                                                    aria-labelledby="v-pills-business-{{ $key }}-tab">
                                                                    <div class="profile-mail">
                                                                        <div class="media align-items-center">
                                                                            <img class="img-100 img-fluid m-r-20 rounded-circle update_img_{{ $key }}"
                                                                                src="{{ asset('storage/' . $businessUnit->bu_image) }}"
                                                                                alt="" />
                                                                            <input class="updateimg" type="file"
                                                                                name="img"
                                                                                onchange="readURL(this,{{ $key }})" />
                                                                            <div class="media-body mt-0">
                                                                                <h5><span
                                                                                        class="first_name_{{ $key }}">{{ $businessUnit->name }}</span>
                                                                                </h5>
                                                                                <p class="email_add_{{ $key }}">
                                                                                    {{ $businessUnit->phone }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="email-general">
                                                                            {{-- <h6 class="mb-3">General</h6> --}}
                                                                            <div class="row">
                                                                                <div class="col-xl-12 recent-order-sec">
                                                                                    <div class="card">
                                                                                        <div class="card-body">
                                                                                            <div class="table-responsive">
                                                                                                <h5>Recent Expense Invoices
                                                                                                </h5>
                                                                                                <table class="table table-bordernone" id="expenseTable{{$businessUnit->id}}">
                                                                                                    <thead>
                                                                                                        <tr>
                                                                                                            <th>Invoice No.</th>
                                                                                                            <th>Date</th>
                                                                                                            <th>Create By</th>
                                                                                                            <th>Total Amount</th>
                                                                                                            <th>Status</th>
                                                                                                            <th>Action</th>
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                        <!-- Data will be populated here -->
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-xl-12 recent-order-sec">
                                                                                    <div class="card">
                                                                                        <div class="card-body">
                                                                                            <div class="table-responsive">
                                                                                                <h5>Recent Income Invoices
                                                                                                </h5>
                                                                                                <table class="table table-bordernone" id="incomeTable{{$businessUnit->id}}">
                                                                                                    <thead>
                                                                                                        <tr>
                                                                                                            <th>Invoice No.
                                                                                                            </th>
                                                                                                            <th>Date</th>
                                                                                                            <th>Create By
                                                                                                            </th>
                                                                                                            <th>Total Amount
                                                                                                            </th>
                                                                                                            <th>Status</th>
                                                                                                            <th>Action</th>
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
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
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <h5 class="m-3" style="text-align: center;">No Business Unit Created
                                                    Yet!</h5>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/chart/chartist/chartist.js') }}"></script>
        <script src="{{ asset('assets/js/chart/chartist/chartist-plugin-tooltip.js') }}"></script>
        <script src="{{ asset('assets/js/chart/knob/knob.min.js') }}"></script>
        <script src="{{ asset('assets/js/chart/knob/knob-chart.js') }}"></script>
        <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
        <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
        <script src="{{ asset('assets/js/prism/prism.min.js') }}"></script>
        <script src="{{ asset('assets/js/clipboard/clipboard.min.js') }}"></script>
        <script src="{{ asset('assets/js/counter/jquery.waypoints.min.js') }}"></script>
        <script src="{{ asset('assets/js/counter/jquery.counterup.min.js') }}"></script>
        <script src="{{ asset('assets/js/counter/counter-custom.js') }}"></script>
        <script src="{{ asset('assets/js/custom-card/custom-card.js') }}"></script>
        <script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
        <script src="{{ asset('assets/js/vector-map/jquery-jvectormap-2.0.2.min.js') }}"></script>
        <script src="{{ asset('assets/js/vector-map/map/jquery-jvectormap-world-mill-en.js') }}"></script>
        <script src="{{ asset('assets/js/vector-map/map/jquery-jvectormap-us-aea-en.js') }}"></script>
        <script src="{{ asset('assets/js/vector-map/map/jquery-jvectormap-uk-mill-en.js') }}"></script>
        <script src="{{ asset('assets/js/vector-map/map/jquery-jvectormap-au-mill.js') }}"></script>
        <script src="{{ asset('assets/js/vector-map/map/jquery-jvectormap-chicago-mill-en.js') }}"></script>
        <script src="{{ asset('assets/js/vector-map/map/jquery-jvectormap-in-mill.js') }}"></script>
        <script src="{{ asset('assets/js/vector-map/map/jquery-jvectormap-asia-mill.js') }}"></script>
        <script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
        <script src="{{ asset('assets/js/notify/index.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
    @endpush

    @section('customJs')
        <script>
             $(document).ready(function(){

             });
            function loadData(index,buID){
                $('.contacts-tabs .nav-link ').removeClass('active show');
                $('.contacts-tabs .tab-content .tab-content-child ').removeClass('active show');
                $( '.contact-tab-'+index ).addClass('active show');
                console.log("Clicked Business Unit Id " + buID);

                getExpenseInvoices(buID);
                getIncomeInvoices(buID);
            }

            function getExpenseInvoices(buID){
                var business_unit_id = buID;
                if(business_unit_id){
                    $.ajax({
                        url: "<?php echo route('get.expenseInvoices'); ?>",
                        method: 'POST',
                        data: {
                            business_unit_id: business_unit_id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            //$('#expenseTable').find('tbody').remove();
                            var table = $('#expenseTable' + buID).find('tbody').remove(); // Get the table body

                            $.each(data.array_data, function(index, exinvoice) {
                                console.log(exinvoice.id);
                                var row = '<tbody><tr>' +
                                    '<td>' + exinvoice.invoice_no + '</td>' +
                                    '<td>' + exinvoice.invoice_date + '</td>' +
                                    '<td>' + exinvoice.upload_user_id + '</td>' +
                                    '<td>' + exinvoice.total_amount + '</td>' +
                                    '<td>' + exinvoice.admin_status + '</td>' +
                                    '<td><a href="{{ route("expense-invoice.show",4) }}" class="btn btn-outline-success btn-sm action-btn pt-0 pb-0 " title="View" data-toggle="tooltip"><i class="fa fa-eye"></i></a></td>' +
                                '</tr><tbody>';
                                    console.log(row);
                                $('#expenseTable'+ buID).append(row);
                            });
                        }
                    });
                }
            }

            function getIncomeInvoices(buID){
                var business_unit_id = buID;
                if(business_unit_id){
                    $.ajax({
                        url: "<?php echo route('get.incomeInvoices'); ?>",
                        method: 'POST',
                        data: {
                            business_unit_id: business_unit_id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            //$('#expenseTable').find('tbody').remove();
                            var table = $('#incomeTable' + buID).find('tbody').remove(); // Get the table body

                            $.each(data.array_data, function(index, incinvoice) {
                                console.log(incinvoice.id);
                                var row = '<tbody><tr>' +
                                    '<td>' + incinvoice.invoice_no + '</td>' +
                                    '<td>' + incinvoice.invoice_date + '</td>' +
                                    '<td>' + incinvoice.upload_user_id + '</td>' +
                                    '<td>' + incinvoice.total_amount + '</td>' +
                                    '<td>' + incinvoice.admin_status + '</td>' +
                                    '<td><a href="{{ route("income-invoice.show",4) }}" class="btn btn-outline-success btn-sm action-btn pt-0 pb-0 " title="View" data-toggle="tooltip"><i class="fa fa-eye"></i></a></td>' +
                                '</tr><tbody>';
                                    console.log(row);
                                $('#incomeTable'+ buID).append(row);
                            });
                        }
                    });
                }
            }
        </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @endsection
@endsection
