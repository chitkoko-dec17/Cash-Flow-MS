@extends('layouts.normalapp')


@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endpush

@section('content')
    @component('components.return_breadcrumb')
        @slot('breadcrumb_title')
            <h3>Return Invoice Edit</h3>
        @endslot
        <li class="breadcrumb-item"><a href="{{ route('return-invoice.index') }}">Return List</a></li>
        <li class="breadcrumb-item active">Edit</li>
    @endcomponent
    @php
        $currency = isset($data['invoice']->currency) ? $data['invoice']->currency : 'MMK';
        $exp_total = isset($data['invoice']->total_amount) ? $data['invoice']->total_amount : 0;
        $exp_f_claimed_total = isset($data['invoice']->f_claimed_total) ? $data['invoice']->f_claimed_total : 0;
        $total_amt = $exp_total - $exp_f_claimed_total; 
        $total_amt = number_format($total_amt,2);
    @endphp
    <div class="container-fluid list-products">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content pt-4" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="return-form">
                            <div class="row">
                                <div class="col-xl-12 col-sm-12">
                                    <form method="post" action="{{ route('return-invoice.update', $invoice->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="mb-3 col-sm-4">
                                            <label for="invoice_id">Expense Invoice</label>
                                            <select class="form-control js-example-basic-single col-sm-12" name="invoice_id" id="invoice_id" disabled>
                                                <option value="">Select Invoice No.</option>
                                                @foreach($expense_invoices as $exp_inv)

                                                	@if($invoice->invoice_id == $exp_inv->id)
                                                		<option value="{{ $exp_inv->id }}" selected>{{ $exp_inv->invoice_no." (".$exp_inv->businessUnit->shorten_code.")" }}</option>
                                                	@else
                                                    <option value="{{ $exp_inv->id }}">{{ $exp_inv->invoice_no." (".$exp_inv->businessUnit->shorten_code.")" }}</option>
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
                                                            <th>Unit Price ({{$currency}})</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $item_no = 1;
                                                        @endphp
                                                        @if (count($data['invoice_items']) > 0)
                                                            @foreach ($data['invoice_items'] as $invitem)
                                                        <tr>
                                                            <td class="item_no">{{ $item_no }}</td>
                                                            <td class="category_name">{{$invitem->category->name}}</td>
                                                            <td class="fixed-column item_name">{{$invitem->item->name}}</td>
                                                            <td class="payment">
                                                                {{ ($invitem->payment_type == "bank") ? "Bank" : "Cash"; }}
                                                            </td>
                                                            <td class="description">{{$invitem->item_description}}</td>
                                                            <td>
                                                                <div class="row" style="justify-content: center;">
                                                                    <div
                                                                        class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-7">
                                                                        <span class="quantity">{{$invitem->qty}}</span>
                                                                    </div>
                                                                    <div
                                                                        class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-5">
                                                                        <span class="unit">{{$invitem->unit->name}}</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="unit_price">{{$invitem->amount .' '.$currency}}</td>
                                                            <td class="total">{{ number_format($invitem->qty * $invitem->amount,2) .' '.$currency}} </td>
                                                        </tr>
                                                        @php
                                                            $item_no++;
                                                        @endphp
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="8" class="text-right"><strong><b
                                                                        class="exp_total">{{ $exp_f_claimed_total .' '.$currency}}</b> </strong>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="mb-3 col-sm-4">
                                                <label for="invoice_date">Invoice Date</label>
                                                <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="{{$invoice->invoice_date }}">
                                                @error('invoice_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-sm-4">
                                                <label for="total_amount">Total Amount</label>
                                                <input type="text" class="form-control" id="total_amount" name="total_amount" value="{{$invoice->total_amount }}">
                                                @error('total_amount')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3">{{$invoice->description }}</textarea>
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
@endpush
