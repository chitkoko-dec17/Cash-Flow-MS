@extends('layouts.normalapp')


@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dropzone.css') }}">
@endpush

@section('content')
    <div class="container-fluid list-products">
        <div class="row">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Expense Invoice <code>Configuration - Edit</code></h5>
                </div>
                <div class="card-body">
                    <div class="tab-content pt-4" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="expense-form">
                            <div class="row">
                                <div class="col-xl-12 col-sm-12">
                                    <form method="post" action="{{ route('expense-invoice.update',$invoice->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                        		<div class="mb-3 col-sm-4">
                                                <label for="invoice_no">Invoice Number</label>
                                                <input type="text" value="{{ $invoice_no }}" class="form-control"
                                                    id="invoice_no" name="invoice_no" disabled>
                                            </div>
                                            <div class="mb-3 col-sm-4">
                                                <label for="branch_id">Branch</label>
                                                <select class="form-control form-select" id="branch_id" name="branch_id">
                                                    <option value="">Select Branch</option>
                                                    @foreach($branches as $optgroupLabel => $branchOptions)
                                                    <optgroup label="{{ $optgroupLabel }}">
                                                        @foreach($branchOptions as $branchId => $branchName)
                                                        @if($invoice->branch_id == $branchId)
                                                        	<option value="{{ $branchId }}" selected>{{ $branchName }}</option>
                                                        @else
                                                        	<option value="{{ $branchId }}">{{ $branchName }}</option>
                                                        @endif
                                                        
                                                        @endforeach
                                                    </optgroup>
                                                    @endforeach
                                                </select>
                                                @error('branch_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

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
                                                    name="invoice_date" value="{{$invoice->invoice_date }}">
                                                @error('invoice_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- Invoice Items -->
                                        <div class="form-group">
                                            <label for="invoiceItems">Invoice Items</label>
                                            <table class="table table-bordered" id="invoiceItems">
                                                <thead>
                                                    <tr>
                                                        <th>Category</th>
                                                        <th>Item</th>
                                                        <th width="60">Quantity</th>
                                                        <th width="100">Unit Price (MMK)</th>
                                                        <th>Total</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                	@if (count($invoice_items) > 0)
                                        						@foreach ($invoice_items as $invitem)
                                                    <tr>
                                                        <td>
                                                        	<input type="hidden" name="invitem[]" value="{{$invitem->id}}">
                                                            {{$invitem->category->name}}
                                                        </td>
                                                        <td>
                                                            {{$invitem->item->name}}
                                                        </td>
                                                        <td><input type="number" class="form-control quantity"
                                                                name="quantity[]" min="1" value="{{$invitem->qty}}"></td>
                                                        <td><input type="number" class="form-control amount"
                                                                name="amount[]" step="0.01" value="{{$invitem->amount}}"></td>
                                                        <td class="total">{{$invitem->qty * $invitem->amount}} MMK</td>
                                                        <td>
                                                            <!-- <button type="button"
                                                                class="btn btn-danger btn-sm action-btn remove-btn"><i
                                                                    class="fa fa-trash"></i></button> -->
                                                        </td>
                                                    </tr>
	                                                	@endforeach
	                                      					@endif
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                                        <td colspan="2" class="totalAmount">{{$invoice->total_amount }} MMK</td>
                                                        <input type="hidden" name="total_amount" id="total_amount" value="{{$invoice->total_amount }}">
                                                        @error('total_amount')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <button type="button" class="btn btn-light mt-2" id="add-item-btn"><i
                                                    class="fa fa-plus"></i> Add Item</button>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3">{{$invoice->description }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="docs">Invoice Files</label>
                                            <input type="file" class="form-control" id="docs" name="docs[]" multiple>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update</button>
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
        let jcates = '';
        @foreach($itemcategories as $cate)
            jcates += '<option value="{{ $cate->id }}">{{ $cate->name }}</option>';
        @endforeach
        $(document).ready(function() {
        		$('#branch_id').trigger('change');
            // Add new invoice item row
            $("#add-item-btn").click(function() {
                const newRow = `
                    <tr>
                        <td>
                            <select class="form-select category_id" name="category_ids[]">
                                <option value="">Select Category</option>
                                `+jcates+`
                            </select>
                        </td>
                        <td>
                            <select class="form-select item_id" name="items[]">
                                <option value="">Select Item</option>
                            </select>
                        </td>
                        <td><input type="number" class="form-control quantity" name="quantity[]" min="1" value="1"></td>
                        <td><input type="number" class="form-control amount" name="amount[]" step="0.01" value="0"></td>
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
            $("#invoiceItems").on("input", "input.quantity, input.amount", function() {
                const quantity = $(this).closest("tr").find(".quantity").val();
                const amount = $(this).closest("tr").find(".amount").val();
                const total = parseFloat(quantity) * parseFloat(amount);
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
                    data: {cate_id:cate_id, _token:token},
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

        $('#branch_id').on('change',function(){
            var branch_id = $(this).val();
            var token = $("input[name='_token']").val();
            if(branch_id){
                $.ajax({
                    url: "<?php echo route('get.projects') ?>",
                    method: 'POST',
                    data: {branch_id:branch_id, _token:token},
                    success: function(data) {
                        $('#project_id').find('option').remove();  

                        $('#project_id').append('<option selected="selected">Select Project</option>');
                        $.each(data.array_data, function(value, text){   
                            // console.log(text);                  
                          $('#project_id').append('<option value="' + text.id + '">' + text.name + '</option>');
                        });
                    }
                });
            }
        });
        
    </script>
@endpush
