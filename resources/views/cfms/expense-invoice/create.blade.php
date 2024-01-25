@extends('layouts.normalapp')


@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endpush

@section('content')
    @component('components.expense_breadcrumb')
        @slot('breadcrumb_title')
            <h3>Expense Invoice Create</h3>
        @endslot
        <li class="breadcrumb-item"><a href="{{ route('expense-invoice.index') }}">Expense List</a></li>
        <li class="breadcrumb-item active">Create</li> {{-- i think . no need to add href for active --}}
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
                            aria-labelledby="expense-form">
                            <div class="row">
                                <div class="col-xl-12 col-sm-12">
                                    <form method="post" action="{{ route('expense-invoice.store') }}" enctype="multipart/form-data">
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
                                                <input id="exchange_rate" type="number" class="form-control" name="exchange_rate" value="1" step="0.01" placeholder="0" required></td>
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
                                                        <tr style="background:aliceblue;">
                                                            <th>No.</th>
                                                            <th style="min-width: 200px;">Category</th>
                                                            <th style="min-width: 200px;">Item</th>
                                                            <th style="min-width: 130px;">Quantity & Unit</th>
                                                            <th style="min-width: 130px;">Unit Price (<span class="currency_sign">MMK</span>)</th>
                                                            <th style="min-width: 130px;">Payment</th>
                                                            <th style="min-width: 200px;">Description</th>
                                                            <th style="min-width: 130px;">Total</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><span class="td_number"></span></td>
                                                            <td>
                                                                <select class="form-select category_id" name="category_ids[]" id="category_id" required>
                                                                    <option value="">Select Category</option>
                                                                    @foreach($itemcategories as $cate)
                                                                        <option value="{{ $cate->id }}">{{ $cate->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="form-select js-example-basic-single item_id required" name="items[]" required>
                                                                    <option value="">Select Item</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <div class="row" style="justify-content: center;">
                                                                    <div class="m-1 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-12">
                                                                        <input type="number" class="form-control quantity" name="quantity[]" min="1" value="1">
                                                                    </div>
                                                                    <div class="m-1 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-12">
                                                                        <select class="form-select" name="unit_ids[]">
                                                                            @foreach($itemunits as $unit)
                                                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control amount"
                                                                    name="amount[]" step="0.01" value="0">
                                                            </td>
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
                                                            <td colspan="7" class="text-right" style="text-align: right;"><strong>Total:</strong></td>
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
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
        let table_number = 1;
        let jcates = '';
        let junits = '';
        @foreach($itemcategories as $cate)
            jcates += '<option value="{{ $cate->id }}">{{ $cate->name }}</option>';
        @endforeach
        @foreach($itemunits as $unit)
            junits += '<option value="{{ $unit->id }}">{{ $unit->name }}</option>';
        @endforeach
        $(document).ready(function() {

            $(".td_number").text(table_number);

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
                let no = table_number += 1;

                const newRow = `
                    <tr>
                        <td>${no}</td>
                        <td>
                            <select class="form-select category_id" name="category_ids[]" required>
                                <option value="">Select Category</option>
                                `+jcates+`
                            </select>
                        </td>
                        <td>
                            <select class="form-select js-example-basic-single item_id" name="items[]" required>
                                <option value="">Select Item</option>
                            </select>
                        </td>
                        <td>
                            <div class="row" style="justify-content: center;">
                                <div class="m-1 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-12">
                                    <input type="number" class="form-control quantity" name="quantity[]" min="1" value="1">
                                </div>
                                <div class="m-1 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-12">
                                    <select class="form-select" name="unit_ids[]">
                                        `+junits+`
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td><input type="text" class="form-control amount" name="amount[]" step="0.01" value="0"></td>
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

                // Update the row numbers after adding a new row
                updateRowNumbers();
            });

            // Remove invoice item row
            $("#invoiceItems").on("click", ".remove-btn", function() {
                $(this).closest("tr").remove();
                calculateTotal();

                updateRowNumbers();
            });

            // Function to update "No." column for remaining rows
            function updateRowNumbers() {
                // Find all rows in the table body
                $("#invoiceItems tbody tr").each(function(index) {
                    // Update the text content of the first <td> in each row (the "No." column)
                        table_number = index + 1;
                    $(this).find("td:first").text(index + 1);
                });
            }

            // Calculate total amount dynamically
            $("#invoiceItems").on("input", "input.quantity, input.amount", function() {
                const quantity = $(this).closest("tr").find(".quantity").val();
                const amount = $(this).closest("tr").find(".amount").val();
                const total = parseFloat(quantity) * parseFloat(amount);
                // $(this).closest("tr").find(".total").text(total.toFixed(2) + "MMK");
                $(this).closest("tr").find(".total").text(total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                calculateTotal();
            });

            function calculateTotal() {
                let totalAmount = 0;
                $("#invoiceItems tbody tr").each(function() {
                    const total = parseFloat($(this).find(".total").text().replace(/,/g, ''));
                    totalAmount += isNaN(total) ? 0 : total;
                });
                $(".totalAmount").text(totalAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $("#total_amount").val(totalAmount.toFixed(0));
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
                    data: {cate_id:cate_id, inv_type:1, _token:token},
                    success: function(data) {
                        // $('.item_id').find('option').remove();
                        main.closest('tr').find('select.item_id option').remove();
                        var selectbox = main.closest('tr').find('select.item_id');

                        //selectbox.append('<option selected="selected">Select Item</option>');
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
