@extends('layouts.normalapp')


@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dropzone.css') }}">
@endpush

@section('content')
    <div class="container-fluid list-products">
        <div class="row">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Invoice <code>Configuration</code></h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="expense-form" data-bs-toggle="pill" href="#pills-home"
                                role="tab" aria-controls="pills-home" aria-selected="true">
                                ထွက်ငွေ ထည့်မည်
                                <div class="media"></div>
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="income-form" data-bs-toggle="pill"
                                href="#pills-profile" role="tab" aria-controls="pills-profile"
                                aria-selected="false">ဝင်ငွေ ထည့်မည်</a></li>
                        <li class="nav-item"><a class="nav-link" id="refund-form" data-bs-toggle="pill"
                                href="#pills-contact" role="tab" aria-controls="pills-contact"
                                aria-selected="false">ပြန်အမ်းငွေ ထည့်မည်</a></li>
                    </ul>
                    <div class="tab-content pt-4" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="expense-form">
                            <div class="row">
                                <div class="col-xl-12 col-sm-12">
                                    <form>
                                        <div class="row">
                                            <div class="mb-3 col-sm-4">
                                                <label for="invoiceNumber">Invoice Number</label>
                                                <input type="text" value="INV00020234516" class="form-control"
                                                    id="invoiceNumber" name="invoiceNumber" disabled>
                                            </div>
                                            {{-- <div class="mb-3 col-sm-4">
                                                <label for="invoiceType">Invoice Type</label>
                                                <select class="form-control form-select" id="invoiceType" name="invoiceType">
                                                    <option value="">Choose Invoice</option>
                                                    <option value="expense">ထွက်ငွေ</option>
                                                    <option value="income">ဝင်ငွေ</option>
                                                    <option value="refund">ပြန်အမ်းငွေ</option>
                                                </select>
                                            </div> --}}
                                            <div class="mb-3 col-sm-4">
                                                <label for="invoiceDate">Invoice Date</label>
                                                <input type="date" class="form-control" id="invoiceDate"
                                                    name="invoiceDate">
                                            </div>
                                        </div>
                                        <!-- Invoice Items -->
                                        <div class="form-group">
                                            <label for="invoiceItems">Invoice Items</label>
                                            <table class="table table-bordered" id="invoiceItems">
                                                <thead>
                                                    <tr>
                                                        <th>Item</th>
                                                        <th>Quantity</th>
                                                        <th>Unit Price (MMK)</th>
                                                        <th>Total</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <select class="form-control" name="item[]">
                                                                <option value="">Select Item</option>
                                                                <!-- Add your item options here -->
                                                                <option value="item1">Item 1</option>
                                                                <option value="item2">Item 2</option>
                                                                <!-- Add more items as needed -->
                                                            </select>
                                                        </td>
                                                        <td><input type="number" class="form-control quantity"
                                                                name="quantity[]" min="1" value="1"></td>
                                                        <td><input type="number" class="form-control unitPrice"
                                                                name="unitPrice[]" step="0.01" value="0"></td>
                                                        <td class="total">0.00 MMK</td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm action-btn remove-btn"><i
                                                                    class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                                        <td colspan="2" class="totalAmount">0.00 MMK</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <button type="button" class="btn btn-light mt-2" id="add-item-btn"><i
                                                    class="fa fa-plus"></i> Add Item</button>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="attach">Attachment</label>
                                            <input type="file" class="form-control" id="attach" name="attach">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="income-form">
                            <div class="row">
                                <div class="col-xl-12 col-sm-12">
                                    <form>
                                        <div class="row">
                                            <div class="mb-3 col-sm-4">
                                                <label for="invoiceNumber">Invoice Number</label>
                                                <input type="text" value="INV00020234516" class="form-control"
                                                    id="invoiceNumber" name="invoiceNumber" disabled>
                                            </div>
                                            {{-- <div class="mb-3 col-sm-4">
                                                <label for="invoiceType">Invoice Type</label>
                                                <select class="form-control form-select" id="invoiceType" name="invoiceType">
                                                    <option value="">Choose Invoice</option>
                                                    <option value="expense">ထွက်ငွေ</option>
                                                    <option value="income">ဝင်ငွေ</option>
                                                    <option value="refund">ပြန်အမ်းငွေ</option>
                                                </select>
                                            </div> --}}
                                            <div class="mb-3 col-sm-4">
                                                <label for="invoiceDate">Invoice Date</label>
                                                <input type="date" class="form-control" id="invoiceDate"
                                                    name="invoiceDate">
                                            </div>
                                        </div>
                                        <!-- Invoice Items -->
                                        <div class="form-group">
                                            <label for="invoiceItems">Invoice Items</label>
                                            <table class="table table-bordered" id="invoiceItems">
                                                <thead>
                                                    <tr>
                                                        <th>Item</th>
                                                        <th>Quantity</th>
                                                        <th>Unit Price (MMK)</th>
                                                        <th>Total</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <select class="form-control" name="item[]">
                                                                <option value="">Select Item</option>
                                                                <!-- Add your item options here -->
                                                                <option value="item1">Item 1</option>
                                                                <option value="item2">Item 2</option>
                                                                <!-- Add more items as needed -->
                                                            </select>
                                                        </td>
                                                        <td><input type="number" class="form-control quantity"
                                                                name="quantity[]" min="1" value="1"></td>
                                                        <td><input type="number" class="form-control unitPrice"
                                                                name="unitPrice[]" step="0.01" value="0"></td>
                                                        <td class="total">0.00 MMK</td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm action-btn remove-btn"><i
                                                                    class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                                        <td colspan="2" class="totalAmount">0.00 MMK</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <button type="button" class="btn btn-light mt-2" id="add-item-btn"><i
                                                    class="fa fa-plus"></i> Add Item</button>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="attach">Attachment</label>
                                            <input type="file" class="form-control" id="attach" name="attach">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="refund-form">
                            <div class="row">
                                <div class="col-xl-12 col-sm-12">
                                    <form>
                                        <div class="row">
                                            <div class="mb-3 col-sm-4">
                                                <label for="invoiceNumber">Invoice Number</label>
                                                <input type="text" value="INV00020234516" class="form-control"
                                                    id="invoiceNumber" name="invoiceNumber" disabled>
                                            </div>
                                            {{-- <div class="mb-3 col-sm-4">
                                                <label for="invoiceType">Invoice Type</label>
                                                <select class="form-control form-select" id="invoiceType" name="invoiceType">
                                                    <option value="">Choose Invoice</option>
                                                    <option value="expense">ထွက်ငွေ</option>
                                                    <option value="income">ဝင်ငွေ</option>
                                                    <option value="refund">ပြန်အမ်းငွေ</option>
                                                </select>
                                            </div> --}}
                                            <div class="mb-3 col-sm-4">
                                                <label for="invoiceDate">Invoice Date</label>
                                                <input type="date" class="form-control" id="invoiceDate"
                                                    name="invoiceDate">
                                            </div>
                                        </div>
                                        <!-- Invoice Items -->
                                        <div class="form-group">
                                            <label for="invoiceItems">Invoice Items</label>
                                            <table class="table table-bordered" id="invoiceItems">
                                                <thead>
                                                    <tr>
                                                        <th>Item</th>
                                                        <th>Quantity</th>
                                                        <th>Unit Price (MMK)</th>
                                                        <th>Total</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <select class="form-control" name="item[]">
                                                                <option value="">Select Item</option>
                                                                <!-- Add your item options here -->
                                                                <option value="item1">Item 1</option>
                                                                <option value="item2">Item 2</option>
                                                                <!-- Add more items as needed -->
                                                            </select>
                                                        </td>
                                                        <td><input type="number" class="form-control quantity"
                                                                name="quantity[]" min="1" value="1"></td>
                                                        <td><input type="number" class="form-control unitPrice"
                                                                name="unitPrice[]" step="0.01" value="0"></td>
                                                        <td class="total">0.00 MMK</td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm action-btn remove-btn"><i
                                                                    class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                                        <td colspan="2" class="totalAmount">0.00 MMK</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <button type="button" class="btn btn-light mt-2" id="add-item-btn"><i
                                                    class="fa fa-plus"></i> Add Item</button>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="attach">Attachment</label>
                                            <input type="file" class="form-control" id="attach" name="attach">
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
    <script src="{{ asset('assets/js/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/dropzone/dropzone-script.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Add new invoice item row
            $("#add-item-btn").click(function() {
                const newRow = `
                    <tr>
                        <td>
                            <select class="form-control" name="item[]">
                                <option value="">Select Item</option>
                                <!-- Add your item options here -->
                                <option value="item1">Item 1</option>
                                <option value="item2">Item 2</option>
                                <!-- Add more items as needed -->
                            </select>
                        </td>
                        <td><input type="number" class="form-control quantity" name="quantity[]" min="1" value="1"></td>
                        <td><input type="number" class="form-control unitPrice" name="unitPrice[]" step="0.01" value="0"></td>
                        <td class="total">0.00 MMK</td>
                        <td><button type="button" class="btn btn-danger btn-sm action-btn remove-btn"><i class="fa fa-trash"></i></button></td>
                    </tr>
                `;
                $("#invoiceItems tbody").append(newRow);
            });

            // Remove invoice item row
            $("#invoiceItems").on("click", ".remove-btn", function() {
                $(this).closest("tr").remove();
                calculateTotal();
            });

            // Calculate total amount dynamically
            $("#invoiceItems").on("input", "input.quantity, input.unitPrice", function() {
                const quantity = $(this).closest("tr").find(".quantity").val();
                const unitPrice = $(this).closest("tr").find(".unitPrice").val();
                const total = parseFloat(quantity) * parseFloat(unitPrice);
                $(this).closest("tr").find(".total").text(total.toFixed(2) + "MMK");
                calculateTotal();
            });

            function calculateTotal() {
                let totalAmount = 0;
                $("#invoiceItems tbody tr").each(function() {
                    const total = parseFloat($(this).find(".total").text().replace("MMK", ""));
                    totalAmount += isNaN(total) ? 0 : total;
                });
                $(".totalAmount").text(totalAmount.toFixed(2) + "MMK");
            }
        });
    </script>
@endpush
