@extends('layouts.normalapp')


@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endpush

@section('content')
    @component('components.return_breadcrumb')
        @slot('breadcrumb_title')
            <h3>Return Invoice Create</h3>
        @endslot
        <li class="breadcrumb-item"><a href="{{ route('return-invoice.index') }}">Return List</a></li>
        <li class="breadcrumb-item active">Create</li>
    @endcomponent
    <div class="container-fluid list-products">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content pt-4" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="return-form">
                            <div class="row">
                                <div class="col-xl-12 col-sm-12">
                                    <form method="post" action="{{ route('return-invoice.store') }}"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="mb-3 col-sm-4">
                                            <label for="invoice_id">Expense Invoice</label>
                                            <select class="form-control js-example-basic-single col-sm-12" name="invoice_id"
                                                id="invoice_id">
                                                <option value="">Select Invoice No.</option>
                                                @foreach ($expense_invoices as $optgroupLabel => $exp_inv)
                                                    @if ($data['expense_inv_id'] == $exp_inv->id)
                                                        <option value="{{ $exp_inv->id }}" selected>
                                                            {{ $exp_inv->invoice_no }}</option>
                                                    @else
                                                        <option value="{{ $exp_inv->id }}">{{ $exp_inv->invoice_no }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('invoice_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
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
                                                            <th class="fixed-column">Item</th>
                                                            <th>Payment</th>
                                                            <th>Description</th>
                                                            <th>Quantity & Unit</th>
                                                            <th>Unit Price (MMK)</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="item_no"></td>
                                                            <td class="category_name">
                                                            </td>
                                                            <td class="fixed-column item_name">
                                                            </td>
                                                            <td class="payment">

                                                            </td>
                                                            <td class="description">

                                                            </td>
                                                            <td>
                                                                <div class="row" style="justify-content: center;">
                                                                    <div
                                                                        class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-7">
                                                                        <span class="quantity"></span>
                                                                    </div>
                                                                    <div
                                                                        class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-5">
                                                                        <span class="unit"></span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="unit_price"></td>
                                                            <td class="total"> MMK</td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="8" class="text-right"><strong><b
                                                                        class="exp_total"></b> </strong>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="mb-3 col-sm-4">
                                                <label for="invoice_date">Invoice Date</label>
                                                <input type="date" class="form-control" id="invoice_date"
                                                    name="invoice_date" value="{{ date('Y-m-d') }}">
                                                @error('invoice_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-sm-4">
                                                <label for="total_amount">Total Amount <code class="max_total">(max: 0 MMK)</code></label>
                                                <input type="number" class="form-control" id="total_amount"
                                                    name="total_amount">
                                                @error('total_amount')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="docs">Claim Return File</label>
                                            <input type="file" class="form-control" id="docs" name="docs">
                                            @error('docs')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>

    <script>
        var item_no = 1;
        var exp_total = 0;


        $(document).on('change', '#invoice_id', function() {
            getExpenseInvoice($(this));
        });

        // Main function to get and display expense invoice details
        function getExpenseInvoice(id) {
            // Reset data.
            $("#invoiceItems tbody tr").remove();
            item_no = 1;
            exp_total = 0;

            var exp_inv_id = id.val();
            console.log("Changed invoice id " + exp_inv_id);
            var token = $("input[name='_token']").val();

            if (exp_inv_id) {
                $.ajax({
                    url: "<?php echo route('get.expenseInvoiceById'); ?>",
                    method: 'POST',
                    data: {
                        id: exp_inv_id,
                        _token: token
                    },
                    success: function(expenseInvoiceByIdData) {
                        $.each(expenseInvoiceByIdData.array_data, function(value, text) {
                            $.ajax({
                                url: "<?php echo route('get.expenseInvoiceItems'); ?>",
                                method: 'POST',
                                data: {
                                    id: text.id,
                                    _token: token
                                },
                                success: function(itemData) {
                                    $.each(itemData.array_data, function(value, text) {
                                        $.ajax({
                                            url: "<?php echo route('get.itemCategoryById'); ?>",
                                            method: 'POST',
                                            data: {
                                                id: text.category_id,
                                                _token: token
                                            },
                                            success: function(catData) {
                                                var cat_name =
                                                    "Unknown Category";
                                                if (catData.array_data && catData.array_data.length > 0) {
                                                    cat_name = catData.array_data[0].name;
                                                }

                                                $.ajax({
                                                    url: "<?php echo route('get.itemById'); ?>",
                                                    method: 'POST',
                                                    data: {
                                                        id: text.item_id,
                                                        _token: token
                                                    },
                                                    success: function(itemData) {
                                                        var item_name = "";
                                                        if (itemData.array_data && itemData.array_data.length >0
                                                        ) {
                                                            item_name=itemData.array_data[0].name;
                                                        }

                                                        getItemUnitNameAndAppendRow(text,cat_name,item_name,token);
                                                    }
                                                });
                                            }
                                        });
                                    });
                                }
                            });
                        });
                    }
                });
            }
        }
        // Function to append row with retrieved data
        function getItemUnitNameAndAppendRow(text, cat_name, item_name, token) {
            getItemUnitName(text.unit_id, token, function(unitName) {
                const new_row = `
                    <tr>
                        <td class="item_no">${item_no}</td>
                        <td class="category_name">${cat_name || ''}</td>
                        <td class="fixed-column item_name">${item_name || ''}</td>
                        <td class="payment">${text.payment_type || ''}</td>
                        <td class="description">${text.item_description || ''}</td>
                        <td>
                            <div class="row" style="justify-content: center;">
                                <div style="text-align: center;">
                                    <span class="quantity">${text.qty || ''}</span> <span class="unit">${unitName || ''}</span>
                                </div>
                            </div>
                        </td>
                        <td class="unit_price">${text.amount}</td>
                        <td class="total">${text.qty * text.amount} MMK</td>
                    </tr>
                `;

                exp_total += text.qty * text.amount;
                $("#invoiceItems tbody").append(new_row);
                item_no++;

                $(".exp_total").text("Total: " + exp_total + " MMK");
                $(".max_total").text("max: " +exp_total + " MMK");
                $("#total_amount").prop("max", exp_total);
            });

        }

        // Function to get item unit name
        function getItemUnitName(unit_id, token, callback) {
            $.ajax({
                url: "<?php echo route('get.itemUnitById'); ?>",
                method: 'POST',
                data: {
                    id: unit_id,
                    _token: token
                },
                success: function(unitData) {
                    var unit_name = "Unknown Unit";
                    if (unitData.array_data && unitData.array_data.length > 0) {
                        unit_name = unitData.array_data[0].name;
                    }
                    callback(unit_name);
                }
            });
        }

    </script>
@endpush

@section('customJs')
@endsection
