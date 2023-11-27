@extends('layouts.normalapp')


@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
    <style>
        .income-expense-form{
            border-top: 1px dashed #6f6f6f3d;
        }
        .expense-item-title{
            display: block;
            text-align: center;
            font-size: 1.2rem;
        }

        .switch-container {
            display: flex;
            align-items: center;
            margin-top: 40px;
        }

        .switch-label {
            margin-right: 10px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 45px !important;
            height: 24px !important;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 6px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #24695c;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #24695c;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(16px);
            -ms-transform: translateX(16px);
            transform: translateX(16px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        #income-expense-form {
            display: none;
        }
    </style>
@endpush

@section('content')
    @component('components.income_breadcrumb')
        @slot('breadcrumb_title')
            <h3>Income Invoice Create</h3>
        @endslot
        <li class="breadcrumb-item"><a href="{{ route('income-invoice.index') }}">Income List</a></li>
        <li class="breadcrumb-item active">Create</li>
    @endcomponent

    <div class="container-fluid list-products">
        <div class="row">
            <div class="card">
                <!-- <div class="card-header pb-0">
                    <h5>Expense Invoice <code>Configuration</code></h5>
                </div> -->
                <div class="card-body">
                    <div class="tab-content pt-4" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="income-form">
                            <div class="row">
                                <div class="col-xl-12 col-sm-12">
                                    <form method="post" action="{{ route('income-invoice.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            @if($data['user_role'] != "Staff")
                                            <div class="mb-3 col-sm-4">
                                                <label for="branch_id">Branch</label>
                                                <select class="form-control form-select" id="branch_id" name="branch_id">
                                                    <option value="">Select Branch</option>
                                                    @foreach($branches as $optgroupLabel => $branchOptions)
                                                    <optgroup label="{{ $optgroupLabel }}">
                                                        @foreach($branchOptions as $branchId => $branchName)
                                                        <option value="{{ $branchId }}">{{ $branchName }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                    @endforeach
                                                </select>
                                                @error('branch_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            @endif
                                            @if($data['user_role'] == "Staff")
                                                <input type="hidden" id="branch_id" name="branch_id" value="{{ $data['branch_id'] }}">
                                            @endif
                                            <div class="mb-3 col-sm-4">
                                                <label for="project_id">Project</label>
                                                <select class="form-control form-select" id="project_id" name="project_id">
                                                    <option value="">Select Project</option>
                                                </select>
                                                @error('project_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-sm-4">
                                                <label for="invoice_date">Invoice Date</label>
                                                <input type="date" class="form-control" id="invoice_date"
                                                    name="invoice_date" value="{{ date('Y-m-d') }}">
                                                @error('invoice_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-sm-4">
                                                <label for="currency">Currency</label>
                                                <select class="form-control form-select" id="currency" name="currency" required>
                                                    <option value="MMK" selected>Myanmar Kyat (MMK)</option>
                                                    <option value="USD">US Dollar ($)</option>
                                                    <option value="CNY">Chinese Yuan (¥)</option>
                                                    <option value="THB">Thai Baht (฿)</option>
                                                </select>
                                                @error('currency')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-sm-4" id="exchange_rate_group">
                                                <label for="exchange_rate">Exchange Rate (MMK)</label>
                                                <input id="exchange_rate" type="number" class="form-control" name="exchange_rate" value="1" placeholder="0" required></td>
                                                @error('exchange_rate')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-sm-4">
                                                <label for="for_date">For Date</label>
                                                <input type="date" class="form-control" id="for_date"
                                                    name="for_date" value="{{ date('Y-m-d') }}" required>
                                                @error('for_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- Invoice Items -->
                                        <div class="form-group">
                                            <label for="invoiceItems">Invoice Items</label>
                                            <div class="table-container">
                                                <table class="table table-bordered" id="invoiceItems">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th>Category</th>
                                                            <th>Item</th>
                                                            <th>Quantity & Unit</th>
                                                            <th>Unit Price (<span class="currency_sign">MMK</span>)</th>
                                                            <th>Payment</th>
                                                            <th>Description</th>
                                                            <th>Total</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td></td>
                                                            <td>
                                                                <select class="form-select category_id" name="category_ids[]" id="category_id">
                                                                    <option value="">Select Category</option>
                                                                    @foreach($itemcategories as $cate)
                                                                        <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="form-select js-example-basic-single item_id" name="items[]">
                                                                    <option value="">Select Item</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <div class="row" style="justify-content: center;">
                                                                    <div class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-7">
                                                                        <input type="number" class="form-control quantity" name="quantity[]" min="1" value="1">
                                                                    </div>
                                                                    <div class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-5">
                                                                        <select class="form-select" name="unit_ids[]">
                                                                            @foreach($itemunits as $unit)
                                                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td><input type="number" class="form-control amount"
                                                                    name="amount[]" step="0.01" value="0"></td>
                                                            <td>
                                                                <select class="form-select" name="payment_type[]">
                                                                    <option value="cash">Cash</option>
                                                                    <option value="bank">Bank</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <textarea class="form-control" id="itemDescription" name="idescription[]" rows="2"></textarea>
                                                            </td>
                                                            <td><span class="total">0.00 </span> <span class="currency_sign">MMK</span></td>
                                                            <td class="action-buttons">
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm action-btn remove-btn"><i
                                                                        class="fa fa-trash"></i></button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="7" class="text-right" style="text-align: right;"><strong>Income Total:</strong></td>
                                                            <td colspan="2" ><span class="totalAmount">0.00 </span> <span class="currency_sign">MMK</span></td>
                                                            <input type="hidden" name="total_amount" id="total_amount" value="">
                                                            @error('total_amount')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <button type="button" class="btn btn-light mt-2" id="add-item-btn"><i
                                                    class="fa fa-plus"></i> Add Item</button>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="docs">Invoice Files</label>
                                            <input type="file" class="form-control" id="docs" name="docs[]" multiple>
                                        </div>
                                        
                                        {{-- <button type="button" id="btn_expense" class="btn btn-light float-end"><i class="fa fa-plus"></i> Add Expenses</button> --}}
                                        <div class="form-group">
                                            <div class="switch-container">
                                                <label class="switch-label">Add Expense Items:</label>
                                                <label class="switch">
                                                    <input type="checkbox" id="expenseItemSwitch">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Expense Items -->
                                        <div class="form-group" id="income-expense-form">
                                            <label for="expenseItems" class="expense-item-title mt-2">Expense Items</label>
                                            <div class="table-container">
                                                <table class="table table-bordered" id="expenseItems">
                                                    <thead>
                                                        <tr>
                                                            <th>No.</th>
                                                            <th>Category</th>
                                                            <th>Item</th>
                                                            <th>Quantity & Unit</th>
                                                            <th>Unit Price (<span class="currency_sign">MMK</span>)</th>
                                                            <th>Payment</th>
                                                            <th>Description</th>
                                                            <th>Total</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td></td>
                                                            <td>
                                                                <select class="form-select exp_category_id" name="exp_category_ids[]" id="exp_category_id">
                                                                    <option value="">Select Category</option>
                                                                    @foreach($itemcategories as $cate)
                                                                        <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="form-select js-example-basic-single item_id" name="exp_items[]">
                                                                    <option value="">Select Item</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <div class="row" style="justify-content: center;">
                                                                    <div class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-7">
                                                                        <input type="number" class="form-control quantity" name="exp_quantity[]" min="1" value="1">
                                                                    </div>
                                                                    <div class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-5">
                                                                        <select class="form-select" name="exp_unit_ids[]">
                                                                            @foreach($itemunits as $unit)
                                                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td><input type="number" class="form-control amount"
                                                                    name="exp_amount[]" step="0.01" value="0"></td>
                                                            <td>
                                                                <select class="form-select" name="exp_payment_type[]">
                                                                    <option value="cash">Cash</option>
                                                                    <option value="bank">Bank</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <textarea class="form-control" id="itemDescription" name="exp_idescription[]" rows="2"></textarea>
                                                            </td>
                                                            <td><span class="exp_total">0.00 </span> <span class="currency_sign">MMK</span></td>
                                                            <td class="action-buttons">
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm action-btn remove-btn"><i
                                                                        class="fa fa-trash"></i></button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="7" class="text-right" style="text-align: right;"><strong>Expense Total:</strong></td>
                                                            <td colspan="2" ><span class="expTotalAmount">0.00 </span> <span class="currency_sign">MMK</span></td>
                                                            <input type="hidden" name="exp_total_amount" id="exp_total_amount" value="">
                                                            @error('total_amount')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </tr>
                                                        <tr>
                                                            <td colspan="7" class="text-right" style="text-align: right;"><strong>Total (Income - Expense):</strong></td>
                                                            <td colspan="2" ><span class="netTotalAmount">0.00 </span> <span class="currency_sign">MMK</span></td>
                                                            <input type="hidden" name="net_total_amount" id="net_total_amount" value="">
                                                            @error('total_amount')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <button type="button" class="btn btn-light mt-2" id="add-exp-item-btn"><i
                                                    class="fa fa-plus"></i> Add Expense Item</button>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Income</button>

                                    </form>


                                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')

    <script>
        let jcates = '';
        let junits = '';
        @foreach($itemcategories as $cate)
            jcates += '<option value="{{ $cate->id }}">{{ $cate->name }}</option>';
        @endforeach
        @foreach($itemunits as $unit)
            junits += '<option value="{{ $unit->id }}">{{ $unit->name }}</option>';
        @endforeach
        $(document).ready(function() {
            // Toggle visibility of the expense item table based on the switch state
            // $("#expenseItemSwitch").change(function () {
            //     $("#income-expense-form").toggle(this.checked);
            // });
            let isExpenseFormEnable = false;
                // Toggle visibility of the expense item table based on the switch state
            $("#expenseItemSwitch").change(function () {
                if ($(this).prop("checked")) {
                    // Show the table
                    $("#income-expense-form").show();
                } else {
                    // Hide the table and remove all items
                    $("#income-expense-form").hide();
                    $("#expenseItems tbody tr").remove();
                    calculateExpenseTotal(); // reset expense total
                }
            });
            if(!isExpenseFormEnable) { // conditional here to enable only when total amount of above income invoice > 0
                $("#expenseItemSwitch").prop("disabled", true);
            }

            let selectedCurrency = $("#currency").val(); // initialize current currency value;
            // Initially hide the "exchange_rate" input (MMK is Default)
            $("#exchange_rate_group").hide();

            $("#currency").change(function () {
                // Show or hide the "To Currency" dropdown based on the selected "From Currency"
                if ($("#currency").val() === 'MMK') {
                    $("#exchange_rate_group").hide();
                } else {
                    $("#exchange_rate_group").show();
                }
                updateCurrencySign(); // update currency sign
            });

            function updateCurrencySign() {
                selectedCurrency = $("#currency").val();
                $(".currency_sign").text(selectedCurrency);
            }

            @if($data['user_role'] == "Staff")
            $('#branch_id').trigger('change');
            @endif

            // Add new invoice item row
            $("#add-item-btn").click(function() {
                const newRow = `
                    <tr>
                        <td></td>
                        <td>
                            <select class="form-select category_id" name="category_ids[]">
                                <option value="">Select Category</option>
                                `+jcates+`
                            </select>
                        </td>
                        <td class="fixed-column">
                            <select class="form-select js-example-basic-single item_id" name="items[]">
                                <option value="">Select Item</option>
                            </select>
                        </td>
                        <td>
                            <div class="row" style="justify-content: center;">
                                <div class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-7">
                                    <input type="number" class="form-control quantity" name="quantity[]" min="1" value="1">
                                </div>
                                <div class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-5">
                                    <select class="form-select" name="unit_ids[]">
                                        `+junits+`
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td><input type="number" class="form-control amount" name="amount[]" step="0.01" value="0"></td>
                        <td>
                            <select class="form-select" name="payment_type[]">
                                <option value="cash">Cash</option>
                                <option value="bank">Bank</option>
                            </select>
                        </td>
                        <td>
                            <textarea class="form-control" id="itemDescription" name="idescription[]" rows="2"></textarea>
                        </td>
                        <td><span class="total">0.00 </span> <span class="currency_sign">`+ selectedCurrency +`</span></td>
                        <td class="action-buttons"><button type="button" class="btn btn-danger btn-sm action-btn remove-btn"><i class="fa fa-trash"></i></button></td>
                    </tr>
                `;
                $("#invoiceItems tbody").append(newRow);

                setTimeout(function(){
                    $('.js-example-basic-single').select2();
                }, 100);
            });

            // Add new expense invoice item row
            $("#add-exp-item-btn").click(function() {
                const newRow = `
                    <tr>
                        <td></td>
                        <td>
                            <select class="form-select category_id" name="exp_category_ids[]">
                                <option value="">Select Category</option>
                                `+jcates+`
                            </select>
                        </td>
                        <td class="fixed-column">
                            <select class="form-select js-example-basic-single item_id" name="exp_items[]">
                                <option value="">Select Item</option>
                            </select>
                        </td>
                        <td>
                            <div class="row" style="justify-content: center;">
                                <div class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-7">
                                    <input type="number" class="form-control quantity" name="exp_quantity[]" min="1" value="1">
                                </div>
                                <div class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-5">
                                    <select class="form-select" name="exp_unit_ids[]">
                                        `+junits+`
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td><input type="number" class="form-control amount" name="exp_amount[]" step="0.01" value="0"></td>
                        <td>
                            <select class="form-select" name="exp_payment_type[]">
                                <option value="cash">Cash</option>
                                <option value="bank">Bank</option>
                            </select>
                        </td>
                        <td>
                            <textarea class="form-control" id="itemDescription" name="exp_idescription[]" rows="2"></textarea>
                        </td>
                        <td><span class="exp_total">0.00 </span> <span class="currency_sign">`+ selectedCurrency +`</span></td>
                        <td class="action-buttons"><button type="button" class="btn btn-danger btn-sm action-btn remove-btn"><i class="fa fa-trash"></i></button></td>
                    </tr>
                `;
                $("#expenseItems tbody").append(newRow);

                setTimeout(function(){
                    $('.js-example-basic-single').select2();
                }, 100);
            });

            // Remove invoice item row
            $("#invoiceItems").on("click", ".remove-btn", function() {
                $(this).closest("tr").remove();
                calculateTotal();
            });
            // Remove expense invoice item row
            $("#expenseItems").on("click", ".remove-btn", function() {
                $(this).closest("tr").remove();
                calculateTotal();
            });

            // Calculate total amount dynamically
            $("#invoiceItems").on("input", "input.quantity, input.amount", function() {
                const quantity = $(this).closest("tr").find(".quantity").val();
                const amount = $(this).closest("tr").find(".amount").val();
                const total = parseFloat(quantity) * parseFloat(amount);
                //$(this).closest("tr").find(".total").text(total.toFixed(2));
                $(this).closest("tr").find(".total").text(total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));

                calculateTotal();
            });

            // Calculate expense total amount dynamically
            $("#expenseItems").on("input", "input.quantity, input.amount", function() {
                const quantity = $(this).closest("tr").find(".quantity").val();
                const amount = $(this).closest("tr").find(".amount").val();
                const total = parseFloat(quantity) * parseFloat(amount);
                //$(this).closest("tr").find(".total").text(total.toFixed(2));
                $(this).closest("tr").find(".exp_total").text(total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));

                calculateExpenseTotal();
                calculateNetTotal();
            });

            function calculateTotal() {
                let totalAmount = 0;
                $("#invoiceItems tbody tr").each(function() {
                    const total = parseFloat($(this).find(".total").text().replace(/,/g, ''));
                    totalAmount += isNaN(total) ? 0 : total;
                });
                if(totalAmount <= 0) {
                    $("#expenseItemSwitch").prop("disabled", true);
                    $("#expenseItemSwitch").prop("checked", false);
                    $("#income-expense-form").hide();
                    $("#expenseItems tbody tr").remove();
                    calculateNetTotal();
                    calculateExpenseTotal(); // reset expense total

                } else {
                    $("#expenseItemSwitch").prop("disabled", false);
                    calculateExpenseTotal();
                    calculateNetTotal();
                }
                $(".totalAmount").text(totalAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $("#total_amount").val(totalAmount.toFixed(0));
            }

            function calculateExpenseTotal() {
                let totalAmount = 0;
                $("#expenseItems tbody tr").each(function() {
                    const total = parseFloat($(this).find(".exp_total").text().replace(/,/g, ''));
                    totalAmount += isNaN(total) ? 0 : total;
                });
                $(".expTotalAmount").text(totalAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $("#exp_total_amount").val(totalAmount.toFixed(0));
            }

            function calculateNetTotal(){
                let incomeTotalAmount = 0;
                $("#invoiceItems tbody tr").each(function() {
                    const total = parseFloat($(this).find(".total").text().replace(/,/g, ''));
                    incomeTotalAmount += isNaN(total) ? 0 : total;
                });
                let expenseTotalAmount = 0;
                $("#expenseItems tbody tr").each(function() {
                    const total = parseFloat($(this).find(".exp_total").text().replace(/,/g, ''));
                    expenseTotalAmount += isNaN(total) ? 0 : total;
                });
                let netTotalAmount = incomeTotalAmount - expenseTotalAmount;
                $(".netTotalAmount").text(netTotalAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $("#net_total_amount").val(netTotalAmount.toFixed(0));
            }
        });


        $(document).on('change','.category_id',function(){
            get_items($(this));
        });

        function get_items(main){
            var cate_id = main.val();
            var token = $("input[name='_token']").val();
            if(cate_id){
                $.ajax({
                    url: "<?php echo route('get.items') ?>",
                    method: 'POST',
                    data: {cate_id:cate_id, inv_type:2, _token:token},
                    success: function(data) {
                        // $('.item_id').find('option').remove();
                        main.closest('tr').find('select.item_id option').remove();
                        var selectbox = main.closest('tr').find('select.item_id');

                        selectbox.append('<option selected="selected">Select Item</option>');
                        $.each(data.array_data, function(value, text){
                            // console.log(text);
                          selectbox.append('<option value="' + text.id + '">' + text.name + '</option>');
                        });
                    }
                });
            }
        }

        //for expense items
        $(document).on('change','.exp_category_id',function(){
            get_exp_items($(this));
        });

        function get_exp_items(main){
            var cate_id = main.val();
            var token = $("input[name='_token']").val();
            if(cate_id){
                $.ajax({
                    url: "<?php echo route('get.items') ?>",
                    method: 'POST',
                    data: {cate_id:cate_id, inv_type:1, _token:token},
                    success: function(data) {
                        main.closest('tr').find('select.item_id option').remove();
                        var selectbox = main.closest('tr').find('select.item_id');

                        selectbox.append('<option selected="selected">Select Item</option>');
                        $.each(data.array_data, function(value, text){
                            // console.log(text);
                          selectbox.append('<option value="' + text.id + '">' + text.name + '</option>');
                        });
                    }
                });
            }
        }

        $('#branch_id').on('change',function(){
            var branch_id = $(this).val();
            console.log(branch_id);
            var token = $("input[name='_token']").val();
            if(branch_id){
                $.ajax({
                    url: "<?php echo route('get.projects') ?>",
                    method: 'POST',
                    data: {branch_id:branch_id, _token:token},
                    success: function(data) {
                        $('#project_id').find('option').remove();

                        $('#project_id').append('<option selected="selected" value="">Select Project</option>');
                        $.each(data.array_data, function(value, text){
                            // console.log(text);
                          $('#project_id').append('<option value="' + text.id + '">' + text.name + '</option>');
                        });
                    }
                });
            }
        });


    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>


@endpush

@section('customJs')
    <script type="text/javascript">

        @if ($message = Session::get('error'))
            notifyToUser('Income Invoice Error',
                'Error! {{ $message }}',
                'danger');
        @endif
    </script>
@endsection
