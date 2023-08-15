@extends('layouts.normalapp')

@section('title')
    Report
    {{ $title }}
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
{{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterange-picker.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/prism.css')}}"> --}}
@endpush

@section('content')
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
                            <button class="btn btn-link ps-0" data-bs-toggle="collapse" data-bs-target="#collapseicon" aria-expanded="true" aria-controls="collapseicon">Filter <i class="fa fa-filter"></i></button>
                        </h5>
                    </div>
                    <div class="collapse show" id="collapseicon" aria-labelledby="collapseicon" data-parent="#accordion">
                        <div class="card-body filter-cards-view animate-chk pt-0">
                            <form class="row g-3">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <select class="js-example-basic-single col-sm-12">
                                            <option value="">Select a business unit</option>
                                            <option value="AL">one</option>
                                            <option value="WY">two</option>
                                            <option value="WY">three</option>
                                            <option value="WY">four</option>
                                            <option value="WY">five</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">

                                        <select class="js-example-basic-single col-sm-12">
                                            <option value="">Select a branch</option>
                                            <option value="AL">one</option>
                                            <option value="WY">two</option>
                                            <option value="WY">three</option>
                                            <option value="WY">four</option>
                                            <option value="WY">five</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">

                                        <select class="js-example-basic-single col-sm-12">
                                            <option value="">Select a project</option>
                                            <option value="AL">one</option>
                                            <option value="WY">two</option>
                                            <option value="WY">three</option>
                                            <option value="WY">four</option>
                                            <option value="WY">five</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">

                                        <select class="js-example-basic-single col-sm-12">
                                            <option value="">Select a invoice status</option>
                                            <option value="AL">one</option>
                                            <option value="WY">two</option>
                                            <option value="WY">three</option>
                                            <option value="WY">four</option>
                                            <option value="WY">five</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-md-3 col-sm-12">
                                    <div class="form-group">

                                        <select class="js-example-basic-single col-sm-12">
                                            <option value="AL">By Quantity</option>
                                            <option value="AL">By Price</option>
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">

                                        <input type="date" class="form-control" id="fromDate">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">

                                        <input type="date" class="form-control" id="toDate">
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <input class="btn btn-primary" type="submit" value="Search">
                                        {{-- <input class="btn btn-info" type="reset" value="Clear Filter"> --}}
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-xl-9 xl-70 box-col-9">
                <div class="row">
                    <div class="col-xl-12 xl-100 box-col-12">
                        <div class="row">

                            <div class="col-xl-6 xl-50 box-col-6">
                                <div class="card">
                                    <div class="chart-widget-top">
                                        <div class="card-header">
                                            <h5>Report Title</h5>
                                        </div>
                                        <div class="row pt-0 card-body chart-block">
                                            <div class="chart-overflow" id="column-chart1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 xl-50 box-col-6">
                                <div class="card">
                                    <div class="card-header pb-0">
                                      <h5>Report Title</h5>
                                      <div class="setting-list">
                                        <ul class="list-unstyled setting-option">
                                          <li>
                                            <div class="setting-primary"><i class="icon-settings"></i></div>
                                          </li>
                                          <li><i class="view-html fa fa-code font-white"></i></li>
                                          <li><i class="icofont icofont-maximize full-card font-white"></i></li>
                                          <li><i class="icofont icofont-minus minimize-card font-white"></i></li>
                                          <li><i class="icofont icofont-refresh reload-card font-white"></i></li>
                                          <li><i class="icofont icofont-error close-card font-white"></i></li>
                                        </ul>
                                      </div>
                                    </div>
                                    <div class="card-body">
                                      <div class="chart-container">
                                        <div class="row">
                                          <div class="col-12">
                                            <div id="chart-widget7"></div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="code-box-copy">
                                        <button class="code-box-copy__btn btn-clipboard" data-clipboard-target="#example-head1" title="Copy"><i class="icofont icofont-copy-alt"></i></button>
                                        <pre><code class="language-html" id="example-head1">&lt;!-- Cod Box Copy begin --&gt;
                    &lt;div class="chart-container"&gt;
                    &lt;div class="row"&gt;
                    &lt;div class="col-12"&gt;
                      &lt;div id="chart-widget7"&gt;&lt;/div&gt;
                    &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;!-- Cod Box Copy end --&gt;</code></pre>
                                      </div>
                                    </div>
                                  </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 xl-100 box-col-12">
                        <div class="row">
                            <div class="col-xl-6 xl-50 box-col-6">
                                <div class="card o-hidden">
                                    <div class="card-header pb-0">
                                      <h5>Total Earning</h5>
                                    </div>
                                    <div class="bar-chart-widget">
                                      <div class="top-content bg-primary"></div>
                                      <div class="bottom-content card-body">
                                        <div class="row">
                                          <div class="col-12">
                                            <div id="chart-widget5"> </div>
                                          </div>
                                        </div>
                                        <div class="row text-center">
                                          <div class="col-4 b-r-light">
                                            <div><span class="font-primary">12%<i class="icon-angle-up f-12 ms-1"></i></span><span class="text-muted block-bottom">Year</span>
                                              <h4 class="num m-0"><span class="counter color-bottom">3659</span>M</h4>
                                            </div>
                                          </div>
                                          <div class="col-4 b-r-light">
                                            <div><span class="font-primary">15%<i class="icon-angle-up f-12 ms-1"></i></span><span class="text-muted block-bottom">Month</span>
                                              <h4 class="num m-0"><span class="counter color-bottom">698</span>M</h4>
                                            </div>
                                          </div>
                                          <div class="col-4">
                                            <div><span class="font-primary">34%<i class="icon-angle-up f-12 ms-1"></i></span><span class="text-muted block-bottom">Today</span>
                                              <h4 class="num m-0"><span class="counter color-bottom">9361</span>M</h4>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                            </div>
                            <div class="col-xl-6 xl-50 box-col-6">
                                <div class="card">
                                    <div class="card-header pb-0">
                                        <h5>Report Title</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="area-spaline"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

	@push('scripts')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
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
@endsection
