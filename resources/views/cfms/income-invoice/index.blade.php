@extends('layouts.normalapp')


@section('content')
<div class="container-fluid list-products">
	<div class="row">
	    <div class="card">
	        <div class="card-header pb-10">
	            <span class="float-start">
	                <h5 class="mb-2">Configuration </h5>
	                <span>Income Invoice Configuration</span>
	            </span>
	            <a href="{{ route('income-invoice.create') }}" class="btn btn-primary float-end" type="button"><i class="fa fa-edit"></i> Create New Income Invoice</a>
	        </div>
	        <div class="card-body pt-0">
	            <div class="row">
	                <div class="col-md-12">
	                    <div class="col-md-4 form-inline m-2">
	                        <label for="formSelect"> <span>Show </span></label>
	                        <select wire:model="perPage" class="p-1 m-2" id="formSelect">
	                            <option value="10">10</option>
	                            <option value="20">20</option>
	                            <option value="50">50</option>
	                            <option value="100">100</option>
	                        </select>
	                        <label class="p-1" for="formSelect"> <span> entries</span></label>
	                    </div>
	                    <div class="col-md-3 form-inline float-end">
	                        <div class="input-group">
	                            <input wire:model.debounce.350ms="search" type="text" class="form-control"
	                                placeholder="Search...">
	                        </div>
	                    </div>
	                </div>
	            </div>

	            <div class="row">
	                <div class="table-responsive">
	                    <table class="table table-hover table-bordered">
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
	                            @if (count($income_invoices) > 0)
	                                @foreach ($income_invoices as $inv)
	                                    <tr>
	                                        <td>{{$inv->invoice_no}}</td>
	                                        <td>{{$inv->invoice_date}}</td>
	                                        <td>{{$inv->upload_user_id}}</td>
	                                        <td>{{$inv->total_amount}}</td>
	                                        <td>{{$inv->admin_status}}</td>
	                                        <td>
	                                            <button  class="btn btn-outline-success btn-sm action-btn"
	                                                title="View" data-toggle="tooltip"><i class="fa fa-eye"></i></button>
	                                            <button 
	                                                class="btn btn-outline-info btn-sm  action-btn" title="Edit"
	                                                data-toggle="tooltip"><i class="fa fa-pencil"></i></button>
	                                            <!-- <button
	                                                class="btn btn-outline-danger btn-sm  action-btn" title="Delete"
	                                                data-toggle="tooltip"><i class="fa fa-trash"></i></button> -->
	                                        </td>
	                                    </tr>
	                                @endforeach
	                            @else
	                                <tr>
	                                    <td colspan="6" align="center">
	                                        No Income Invoice Found.
	                                    </td>
	                                </tr>
	                            @endif
	                        </tbody>
	                    </table>
	                </div>
	                {{ $income_invoices->links('cfms.livewire-pagination-links') }}
	            </div>
	        </div>
	    </div>
	</div>
</div>
@endsection

@section('customJs')
	<script type="text/javascript">
		@if ($message = Session::get('success'))
			notifyToUser('Income Invoice Action', '{{ $message }}',
                    'primary');
		@endif

		@if ($message = Session::get('error'))
			notifyToUser('Income Invoice Error',
                    'Error! {{ $message }}',
                    'danger');
		@endif
	</script>
@endsection