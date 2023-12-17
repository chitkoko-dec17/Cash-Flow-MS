@extends('layouts.normalapp')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endpush

@section('content')
    @component('components.expense_breadcrumb')
        @slot('breadcrumb_title')
            <h3>Financial Report</h3>
        @endslot
        {{-- <li class="breadcrumb-item"><a href="{{ route('expense-invoice.index') }}">Expense List</a></li> --}}
        <li class="breadcrumb-item active">Financial report</li> {{-- i think . no need to add href for active --}}
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
                    <div class="collapse show" id="collapseicon" aria-labelledby="collapseicon" data-parent="#accordion">
                        <div class="card-body filter-cards-view animate-chk pt-0">
                            <form class="row g-3">
                                @csrf
                                    <div class="row">
                                        @if(Auth::user()->user_role != "Manager")
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label for="business_unit_id" style="font-size:1.4rex;">Business Unit</label>
                                                <select name="business_unit_id" class="form-select" id="business_unit_id">
                                                    <option value="">Select a business unit</option>
                                                    @foreach ($businessUnits as $businessUnit)
                                                        @if($data['selected_business_unit_id'] == $businessUnit->id)
                                                            <option value="{{ $businessUnit->id }}" selected>{{ $businessUnit->name }}</option>
                                                        @else
                                                            <option value="{{ $businessUnit->id }}">{{ $businessUnit->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label for="branch_id" style="font-size:1.4rex;">Branch</label>
                                                <select name="branch_id" class="form-select" id="branch_id">
                                                    <option value="">Select a branch</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label for="project_id" style="font-size:1.4rex;">Project</label>
                                                <select name="project_id" class="form-select" id="project_id">
                                                    <option value="">Select a project</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label for="budget_id" style="font-size:1.4rex;">Budget Name</label>
                                                <select name="budget_id" class="form-select" id="budget_id">
                                                    <option value="">Select a budget</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <input class="btn btn-primary mt-4" type="submit" value="Search">
                                            </div>
                                        </div>
                                    </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Charts -->
        @if($data['est_budget'])
        <div class="row">
            <div class="col-xl-6 xl-50 box-col-6">
                <div class="card">
                    <div class="card-body">
                        <div id="expenseComparisonPieChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 xl-50 box-col-6">
                <div class="card">
                    <div class="card-body">
                        <div id="stackedcolumnchart"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush

@section('customJs')
    <script>
        @if($data['est_budget'])

        $(document).ready(function() {
            $('#business_unit_id').trigger('change');
            let budget_id = "{{ $data['selected_budget_id'] }}";


            let item_counts = "{{ $data['est_budget']['amt'].','. $data['actual_expense']['amt']}}";
            item_counts = item_counts.split(',');
            let item_names = "{{ $data['est_budget']['name'].','. $data['actual_expense']['name']}}";
            item_names = item_names.split(',');
            var items = {
                series: [{
                  name: item_names,
                  data: item_counts
                }],
                chart: {
                  width: '80%',
                  height: 450,
                  type: 'bar',
                  events: {
                    click: function(chart, w, e) {
                      // console.log(chart, w, e)
                    }
                  }
                },
                // colors: colors,
                plotOptions: {
                  bar: {
                    columnWidth: '45%',
                    distributed: true,
                  }
                },
                dataLabels: {
                  enabled: false
                },
                legend: {
                  show: false
                },
                xaxis: {
                  categories: item_names,
                  labels: {
                    style: {
                      // colors: colors,
                      fontSize: '12px'
                    }
                  }
                }
            };

            var chart = new ApexCharts(document.querySelector("#stackedcolumnchart"), items);
            chart.render();
        });

        // Assuming you have data for actual expenses and expected expenses
        var actualExpenses = {{$data['actual_expense']['amt']}}; // Replace with your actual data
        var expectedExpenses = {{ $data['est_budget']['amt'] }}; // Replace with your expected data

        var expenseComparisonPieChart = new ApexCharts(document.querySelector("#expenseComparisonPieChart"), {
            chart: {
                type: 'pie',
                height: 350
            },
            labels: ['Actual Expenses', '{{ $data["est_budget"]["name"] }}'],
            series: [actualExpenses, expectedExpenses],
            title: {
                text: 'Expense Comparison'
            },
            subtitle: {
                text: '{{ $data["est_budget"]["name"] }}'
            }
        });

        expenseComparisonPieChart.render();
        @endif

        // Attach a click event handler to the Apply button
        $('#applyFilters').click(function() {
            // Get selected filter values
            const selectedBusinessUnit = $('#businessUnitFilter').val();
            const selectedBranch = $('#branchFilter').val();
            const selectedProject = $('#projectFilter').val();
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();

            // Use selected filter values to fetch and update chart data
            // Implement logic to update charts based on filter selections
            // You will need to fetch the appropriate data based on filters
            // and then update the chart series using chart1.updateSeries() and similar methods.
        });

        $(document).on('change', '#business_unit_id', function() {
            get_branch($(this));
            updateBudgetList();
        });

        $(document).on('change', '#branch_id', function() {
            get_project($(this));
            updateBudgetList();
        });
        $(document).on('change', '#project_id', function() {
            updateBudgetList();
        });

        function get_branch(main) {
            var business_unit_id = main.val();
            var token = $("input[name='_token']").val();
            console.log(main.val() + " : " + token);
            if (business_unit_id) {
                $.ajax({
                    url: "<?php echo route('get.branches'); ?>",
                    method: 'POST',
                    data: {
                        business_unit_id: business_unit_id,
                        inv_type: 1,
                        _token: token
                    },
                    success: function(data) {
                        $('#branch_id').find('option').remove();

                        $('#branch_id').append('<option selected="selected" value="">Select a branch</option>');
                        $.each(data.array_data, function(value, text) {
                            // console.log(text);
                            $('#branch_id').append('<option value="' + text.id + '">' + text.name +
                                '</option>');
                        });
                    }
                });
            }
        }

        function get_project(main) {
            var branch_id = main.val();
            var token = $("input[name='_token']").val();
            console.log(main.val() + " : " + token);
            if (branch_id) {
                $.ajax({
                    url: "<?php echo route('get.projects'); ?>",
                    method: 'POST',
                    data: {
                        branch_id: branch_id,
                        inv_type: 1,
                        _token: token
                    },
                    success: function(data) {
                        $('#project_id').find('option').remove();

                        $('#project_id').append(
                            '<option selected="selected" value="">Select a project</option>');
                        $.each(data.array_data, function(value, text) {
                            // console.log(text);
                            $('#project_id').append('<option value="' + text.id + '">' + text.name +
                                '</option>');
                        });
                    }
                });
            }
        }

        function updateBudgetList() {
            var businessUnitId = $('#business_unit_id').val();
            var branchId = $('#branch_id').val();
            var projectId = $('#project_id').val();

            var token = $("input[name='_token']").val();

            if (businessUnitId) {
                $.ajax({
                    url: "<?php echo route('get.budgets'); ?>",
                    method: 'POST',
                    data: {
                        business_unit_id: businessUnitId,
                        branch_id: branchId,
                        project_id: projectId,
                        _token: token
                    },
                    success: function(data) {
                        // Clear and update the budget dropdown list
                        var budgetDropdown = $('#budget_id');
                        budgetDropdown.empty();
                        budgetDropdown.append('<option value="">Select a budget</option>');

                        $.each(data.budget_data, function(index, budget) {
                            if(budget_id){
                                budgetDropdown.append('<option value="' + budget.id + '" selected>' + budget.name + '</option>');
                            }else{
                                budgetDropdown.append('<option value="' + budget.id + '">' + budget.name + '</option>');
                            }
                            
                        });
                    }
                });
            }
        }
    </script>
@endsection
