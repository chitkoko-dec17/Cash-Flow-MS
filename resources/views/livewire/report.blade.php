<div>

    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
        {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterange-picker.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/prism.css')}}"> --}}
    @endpush

    @component('components.expense_breadcrumb')
        @slot('breadcrumb_title')
            <h3>Report</h3>
        @endslot
        {{-- <li class="breadcrumb-item"><a href="{{ route('expense-invoice.index') }}">Expense List</a></li> --}}
        <li class="breadcrumb-item active">report</li> {{-- i think . no need to add href for active --}}
    @endcomponent
    <div class="container-fluid chart-widget">
        <div class="row">
            <div class="col-xl-12 xl-100 box-col-12">

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 p-0">
                            <button class="btn btn-link ps-0" data-bs-toggle="collapse" data-bs-target="#collapseicon"
                                aria-expanded="true" aria-controls="collapseicon">Filter <i
                                    class="fa fa-filter"></i></button>
                        </h5>
                    </div>
                    <div class="collapse show" id="collapseicon" aria-labelledby="collapseicon"
                        data-parent="#accordion">
                        <div class="card-body filter-cards-view animate-chk pt-0">
                            <form class="row g-3">
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="business_unit_id" style="font-size:1.4rex;">Business Unit</label>
                                        <select wire:model="business_unit_id" class="form-select" id="business_unit_id">
                                            <option value="">Select a business unit</option>
                                            @foreach ($businessUnits as $businessUnit)
                                                <option value="{{ $businessUnit->id }}">{{ $businessUnit->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="branch_id" style="font-size:1.4rex;">Branch</label>
                                        <select wire:model="branch_id" class="form-select" id="branch_id">
                                            <option value="">Select a branch</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="project_id" style="font-size:1.4rex;">Project</label>
                                        <select wire:model="project_id" class="form-select" id="project_id">
                                            <option value="">Select a project</option>
                                            @foreach ($projects as $project)
                                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="status" style="font-size:1.4rex;">Invoice Status</label>
                                        <select wire:model="status" class="form-select" id="status">
                                            <option value="">Select an invoice status</option>
                                            @foreach ($statuses as $skey => $status)
                                                <option value="{{ $skey }}">{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="fromDate" style="font-size:1.4rex;">From</label>
                                        <input wire:model="selected_from_date" type="date" class="form-control"
                                            id="fromDate">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label for="toDate" style="font-size:1.4rex;">To</label>
                                        <input wire:model="selected_to_date" type="date" class="form-control"
                                            id="toDate">
                                    </div>
                                </div>
                                {{-- <div class="col-md-12 ">
                                    <div class="form-group">
                                        <input wire:click="search" class="btn btn-primary" type="submit" value="Search">
                                        <input class="btn btn-info" type="reset" value="Clear Filter">
                                    </div>
                                </div> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 xl-50 box-col-6">
                <div class="card">
                    <div class="card-body">
                        <div id="stackedcolumnchart"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 xl-50 box-col-6">
                <div class="card">
                    <div class="card-body">
                        <div id="stackedcolumnchart2"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 xl-100 box-col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="row float-end">
                                    <div class="">{{count($data)}}
                                        <button wire:click='export({{$data}})' type="button" class="btn btn-primary btn-sm">Export <i class="fa fa-file-excel-o ms-1"></i></button>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <span class="">Total Expenses : <strong class="fs-12"> {{ $this->calculateTotalSum() }} MMK</strong></span>
                                </div>
                                {{-- <div class="col-md-3 form-inline m-2 float-end">
                                    <button type="button" class="btn btn-primary btn-xs ms-auto">Export</button>
                                </div> --}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Invoice No.
                                            </th>
                                            <th>Date
                                            </th>
                                            <th>Created By
                                            </th>
                                            <th>Status
                                            </th>
                                            <th>Created For</th>
                                            <th>Amount<code>(expense - return)</code></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($expense_invoices) > 0)
                                            @foreach ($expense_invoices as $inv)
                                                <tr>
                                                    <td>{{ $inv->invoice_no }}</td>
                                                    <td>{{ $inv->invoice_date }}</td>
                                                    <td>{{ $inv->staff->name }}</td>
                                                    <td>{{ $inv->admin_status }}</td>
                                                    {{-- <td>{{ $inv->business_unit_id }}</td> --}}
                                                    <td width="25%">
                                                        <code>{{ isset($inv->businessUnit->name) ? $inv->businessUnit->name : '' }}
                                                            {{ isset($inv->branch->name) ? ' - ' . $inv->branch->name : '' }}
                                                            {{ isset($inv->project->name) ? ' - ' . $inv->project->name : '' }}</code>
                                                    </td>
                                                    <td width="20%">{{ $inv->total_amount }} -
                                                        {{ $inv->return_total_amount }} =
                                                        {{ $this->calculateTotal($inv) }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" align="center">
                                                    No Expense Invoice Found.
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            {{ $expense_invoices->links('cfms.livewire-pagination-links') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
        <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        {{-- <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/chart-custom.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/daterange-picker/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/daterange-picker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/daterange-picker/daterange-picker.custom.js') }}"></script>
	<script src="{{ asset('assets/js/chart/google/google-chart-loader.js') }}"></script>
    <script src="{{ asset('assets/js/chart/google/google-chart.js') }}"></script>
    <script src="{{asset('assets/js/chart/apex-chart/moment.min.js')}}"></script>
    <script src="{{asset('assets/js/chart/apex-chart/apex-chart.js')}}"></script>
    <script src="{{asset('assets/js/chart/apex-chart/stock-prices.js')}}"></script>
    <script src="{{asset('assets/js/prism/prism.min.js')}}"></script>
    <script src="{{asset('assets/js/clipboard/clipboard.min.js')}}"></script>
    <script src="{{asset('assets/js/counter/jquery.waypoints.min.js')}}"></script>
    <script src="{{asset('assets/js/counter/jquery.counterup.min.js')}}"></script>
    <script src="{{asset('assets/js/counter/counter-custom.js')}}"></script>
    <script src="{{asset('assets/js/custom-card/custom-card.js')}}"></script>
    <script src="{{asset('assets/js/chart-widget.js')}}"></script>
    <script src="{{asset('assets/js/tooltip-init.js')}}"></script> --}}
    @endpush

    @section('customJs')
        <script type="text/javascript">
            $(document).ready(function() {
                var options = {
                    series: [{
                        name: 'ITEM A',
                        data: [44, 55, 41, 67, 22, 43]
                    }, {
                        name: 'ITEM B',
                        data: [13, 23, 20, 8, 13, 27]
                    }, {
                        name: 'ITEM C',
                        data: [11, 17, 15, 15, 21, 14]
                    }, {
                        name: 'ITEM D',
                        data: [21, 7, 25, 13, 22, 8]
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                        stacked: true,
                        toolbar: {
                            show: true
                        },
                        zoom: {
                            enabled: false
                        }
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            legend: {
                                position: 'bottom',
                                offsetX: -10,
                                offsetY: 0
                            }
                        }
                    }],
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            borderRadius: 10,
                            dataLabels: {
                                total: {
                                    enabled: true,
                                    style: {
                                        fontSize: '13px',
                                        fontWeight: 900
                                    }
                                }
                            }
                        },
                    },
                    xaxis: {
                        type: 'datetime',
                        categories: ['01/01/2011 GMT', '01/02/2011 GMT', '01/03/2011 GMT', '01/04/2011 GMT',
                            '01/05/2011 GMT', '01/06/2011 GMT'
                        ],
                    },
                    legend: {
                        position: 'right',
                        offsetY: 40
                    },
                    fill: {
                        opacity: 1
                    }
                };

                var chart = new ApexCharts(document.querySelector("#stackedcolumnchart"), options);
                chart.render();
                var chart2 = new ApexCharts(document.querySelector("#stackedcolumnchart2"), options);
                chart2.render();
            });
        </script>
    @endsection
</div>
