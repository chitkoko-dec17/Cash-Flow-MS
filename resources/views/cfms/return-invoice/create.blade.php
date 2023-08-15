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
                                    <form method="post" action="{{ route('return-invoice.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">

                                            <div class="mb-3 col-sm-4">
                                                <label for="invoice_id">Expense Invoice</label>
                                                <select class="form-control js-example-basic-single col-sm-12" name="invoice_id" id="invoice_id">
                                                    <option value="">Select Invoice No.</option>
                                                    @foreach($expense_invoices as $optgroupLabel => $exp_inv)
                                                        @if($data['expense_inv_id'] == $exp_inv->id)
                                                            <option value="{{ $exp_inv->id }}" selected>{{ $exp_inv->invoice_no }}</option>
                                                        @else
                                                            <option value="{{ $exp_inv->id }}">{{ $exp_inv->invoice_no }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('invoice_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-sm-4">
                                                <label for="invoice_date">Invoice Date</label>
                                                <input type="date" class="form-control" id="invoice_date" name="invoice_date">
                                                @error('invoice_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-sm-4">
                                                <label for="total_amount">Total Amount</label>
                                                <input type="text" class="form-control" id="total_amount" name="total_amount">
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
@endpush
