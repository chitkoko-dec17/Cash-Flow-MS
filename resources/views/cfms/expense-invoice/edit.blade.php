@extends('layouts.normalapp')


@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endpush

@section('content')
    @component('components.expense_breadcrumb')
        @slot('breadcrumb_title')
            <h3>Expense Invoice Edit</h3>
        @endslot
        <li class="breadcrumb-item"><a href="{{ route('expense-invoice.index') }}">Expense List</a></li>
        <li class="breadcrumb-item active">Edit</li> {{-- i think . no need to add href for active --}}
    @endcomponent

    <div class="container-fluid list-products">
        <div class="row">
            <div class="card">
                <!-- <div class="card-header pb-0">
                    <h5>Expense Invoice <code>Configuration - Edit</code></h5>
                </div> -->
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
                                                <select class="form-control form-select" id="branch_id" name="branch_id" disabled>
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
                                                <select class="form-control form-select" id="project_id" name="project_id" disabled>
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

                                            @if($data['user_role'] != "Staff")
                                            <div class="mb-3 col-sm-4">
                                                <label for="status">Invoice Status</label>
                                                <select class="form-control form-select" id="status" name="status">
                                                	@foreach($statuses as $skey => $statuse)
                                                    @if($invoice->admin_status == $skey)
                                                    	<option value="{{ $skey }}" selected>{{ $statuse }}</option>
                                                    @else
                                                        @if($data['user_role'] == "Manager" && $skey == "complete")

                                                        @else
                                                            <option value="{{ $skey }}">{{ $statuse }}</option>
                                                        @endif
                                                    @endif

                                                  @endforeach
                                                </select>
                                            </div>
                                            @endif
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
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $item_no = 1;
                                                        @endphp
                                                        @if (count($invoice_items) > 0)
                                                            @foreach ($invoice_items as $invitem)
                                                        <tr>
                                                            <td>{{ $item_no }}</td>
                                                            <td>
                                                                <input type="hidden" name="invitem[]" value="{{$invitem->id}}">
                                                                {{$invitem->category->name}}
                                                            </td>
                                                            <td class="fixed-column">
                                                                {{$invitem->item->name}}
                                                            </td>
                                                            <td>
                                                                <select class="form-select" name="payment_type[]">
                                                                    @if($invitem->payment_type == "bank")
                                                                        <option value="cash">Cash</option>
                                                                        <option value="bank" selected>Bank</option>
                                                                    @else
                                                                        <option value="cash" selected>Cash</option>
                                                                        <option value="bank">Bank</option>
                                                                    @endif
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <textarea class="form-control" id="itemDescription" name="idescription[]" rows="2">{{$invitem->item_description}}</textarea>
                                                            </td>
                                                            <td>
                                                                <div class="row" style="justify-content: center;">
                                                                    <div class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-7">
                                                                        <input type="number" class="form-control quantity"
                                                                    name="quantity[]" min="1" value="{{$invitem->qty}}">
                                                                    </div>
                                                                    <div class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-5">
                                                                        <select class="form-select" name="unit_ids[]">
                                                                            @foreach($itemunits as $unit)
                                                                                @if($invitem->unit_id == $unit->id)
                                                                                    <option value="{{ $unit->id }}" selected>{{ $unit->name }}</option>
                                                                                @else
                                                                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                                                @endif
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td><input type="number" class="form-control amount"
                                                                    name="amount[]" step="0.01" value="{{$invitem->amount}}"></td>
                                                            <td class="total">{{ number_format($invitem->qty * $invitem->amount,2) }} MMK</td>
                                                            <td class="action-buttons">
                                                                @if($data['submit_btn_control'] == true)
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm action-btn remove-edit-btn" data-attr="{{ url('/expense/item', $invitem->id) }}"><i
                                                                        class="fa fa-trash"></i></button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $item_no++;
                                                        @endphp
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="7" class="text-right"><strong>Total:</strong></td>
                                                            <td colspan="2" class="totalAmount">{{ number_format($invoice->total_amount,2) }} MMK</td>
                                                            <input type="hidden" name="total_amount" id="total_amount" value="{{ number_format($invoice->total_amount,2) }}">
                                                            @error('total_amount')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            @if($data['submit_btn_control'] == true)
                                            <button type="button" class="btn btn-light mt-2" id="add-item-btn"><i
                                                    class="fa fa-plus"></i> Add Item</button>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3">{{$invoice->description }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="docs">Invoice Files</label>
                                            <input type="file" class="form-control" id="docs" name="docs[]" multiple>
                                        </div>
                                        @if($data['submit_btn_control'] == true)
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-xl-4 col-md-4 box-col-4">
                <div class="file-content">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5>All Invoice Files</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">

                                <ul>
                                    @foreach ($invoice_docs as $invd)
                                    @php
                                        $ext = pathinfo($invd->inv_file, PATHINFO_EXTENSION);
                                    @endphp
                                    <li class="list-group-item d-flex mb-2">
                                        @if($ext == "xls")
                                            <i class="fa fa-file-excel-o"
                                            style="font-size: 4em;"></i>
                                        @elseif($ext == "pdf")
                                            <i class="fa fa-file-text-o" style="font-size: 4em;"></i>
                                        @else
                                            <i class="fa fa-file-image-o"
                                            style="font-size: 4em;"></i>
                                        @endif
                                        <span class="media-body">
                                            <h6>{{ $invd->title .'.'. $ext }}</h6>
                                            <p><b class="f-12">Upload Date : </b>{{ date('d-m-Y', strtotime($invd->created_at)) }}</p>
                                            <a href="{{ url($invd->inv_file) }}" target="_blank" type="button" class="btn btn-outline-primary pmd-ripple-effect btn-xs"><i class="fa fa-eye m-0"></i></a>
                                            <a href="javascript:void(0)" data-toggle="modal"
                                                        data-target="#deleteModal" type="button" class="btn btn-outline-danger pmd-ripple-effect btn-xs delete-inv-doc" data-attr="{{ url('/expense/doc', $invd->id) }}"><i class="fa fa-trash m-0"></i></a>
                                        </span>
                                    </li>
                                    @endforeach
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="col-xl-12 col-md-12 box-col-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="row chat-box">
                                <!-- Chat right side start-->
                                <div class="col chat-right-aside">
                                    <!-- chat start-->
                                    <div class="chat">
                                        <!-- chat-header start-->
                                        <div class="media chat-header clearfix">
                                            <h5>Invoice Note History</h5>
                                        </div>
                                        <!-- chat-header end-->
                                        <div class="chat-history chat-msg-box custom-scrollbar" style="margin-bottom:110px;">
                                            <ul>

                                                @foreach ($invoice_notes as $invnote)
                                                <li>
                                                    <div class="message my-message" style="width: 100%!important;">
                                                            <div class="message-data-time float-start">{{ $invnote->added_user->name }}<code>{{ $invnote->added_user->role->name }}</code></div>
                                                            <div class="message-data text-end"><span class="message-data-time">{{ date('d-m-Y - H:i:s', strtotime($invnote->created_at)) }}</span></div>
                                                        {{ $invnote->description }}
                                                    </div>
                                                </li>
                                                @endforeach

                                            </ul>
                                        </div>
                                        <!-- end chat-history-->
                                        <form method="post" action="{{ route('expense-note.add',$invoice->id) }}" >
                                            @csrf
                                            <div class="chat-message clearfix">
                                                <div class="row">
                                                    @if($data['user_role'] != "Staff")
                                                    <div class="col-xl-12 d-flex" style="margin-bottom:10px;">
                                                        <select class="form-control form-select" id="status" name="status">
                                                            @foreach($statuses as $skey => $statuse)
                                                            @if($invoice->admin_status == $skey)
                                                                <option value="{{ $skey }}" selected>{{ $statuse }}</option>
                                                            @else
                                                                @if($data['user_role'] == "Manager" && $skey == "complete")

                                                                @else
                                                                    <option value="{{ $skey }}">{{ $statuse }}</option>
                                                                @endif
                                                            @endif

                                                          @endforeach
                                                        </select>
                                                    </div>
                                                    <br>
                                                    @endif
                                                    @if($data['user_role'] == "Staff")
                                                        <input type="hidden" name="status" value="{{ $invoice->admin_status }}">
                                                    @endif
                                                    <div class="col-xl-12 d-flex">
                                                        <div class="input-group text-box">
                                                            <input class="form-control input-txt-bx" id="invoice_note"
                                                                type="text" name="invoice_note"
                                                                placeholder="Type a message......" />
                                                            <button class="btn btn-primary input-group-text"
                                                                type="submit">SEND</button>
                                                        </div>
                                                    </div>
                                                    @error('invoice_note')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </form>
                                        <!-- end chat-message-->
                                        <!-- chat end-->
                                        <!-- Chat right side ends-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

<!-- Delete Modal Box -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="delete-inv-item" method="post">
                    @csrf
                    @method('DELETE')
                    <div class="row">
                        <p>Are you sure you want to delete?</p>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" id="delete-it" class="btn btn-danger">Delete</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script>
        let jcates = '';
        let junits = '';
        let project_id = '{{ $invoice->project_id }}';
        @foreach($itemcategories as $cate)
            jcates += '<option value="{{ $cate->id }}">{{ $cate->name }}</option>';
        @endforeach
        @foreach($itemunits as $unit)
            junits += '<option value="{{ $unit->id }}">{{ $unit->name }}</option>';
        @endforeach
        $(document).ready(function() {
        		$('#branch_id').trigger('change');
            // Add new invoice item row
            $("#add-item-btn").click(function() {
                const newRow = `
                    <tr>
                        <td></td>
                        <td>
                            <select class="form-select category_id" name="category_ids_up[]">
                                <option value="">Select Category</option>
                                `+jcates+`
                            </select>
                        </td>
                        <td class="fixed-column">
                            <select class="form-select js-example-basic-single item_id" name="items_up[]">
                                <option value="">Select Item</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-select" name="payment_type_up[]">
                                <option value="cash">Cash</option>
                                <option value="bank">Bank</option>
                            </select>
                        </td>
                        <td>
                            <textarea class="form-control" id="itemDescription" name="idescription_up[]" rows="2"></textarea>
                        </td>
                        <td>
                            <div class="row" style="justify-content: center;">
                                <div class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-7">
                                    <input type="number" class="form-control quantity" name="quantity_up[]" min="1" value="1">
                                </div>
                                <div class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-5">
                                    <select class="form-select" name="unit_ids_up[]">
                                        `+junits+`
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td><input type="text" class="form-control amount" name="amount_up[]" step="0.01" value="0"></td>
                        <td class="total">0.00 MMK</td>
                        <td class="action-buttons"><button type="button" class="btn btn-danger btn-sm action-btn remove-btn"><i class="fa fa-trash"></i></button></td>
                    </tr>
                `;
                $("#invoiceItems tbody").append(newRow);

                setTimeout(function(){
                    $('.js-example-basic-single').select2();
                }, 100);
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
                    data: {cate_id:cate_id, inv_type:1, _token:token},
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

                        $('#project_id').append('<option selected="selected" value="">Select Project</option>');
                        $.each(data.array_data, function(value, text){
                            // console.log(text);
                            if(project_id && project_id == text.id){

                            	$('#project_id').append('<option value="' + text.id + '" selected>' + text.name + '</option>');
                            }else{
                            	$('#project_id').append('<option value="' + text.id + '">' + text.name + '</option>');
                            }

                        });
                    }
                });
            }
        });

        $(document).on('click', '.remove-edit-btn', function() {
            $('#modal_title').html('Delete Invoice Item');
            $('#deleteModal').modal('show');
            let href = $(this).attr('data-attr');
            $('#delete-inv-item').attr('action', href);
        });

        $(document).on('click', '.delete-inv-doc', function() {
            $('#modal_title').html('Delete Invoice Doc');
            $('#deleteModal').modal('show');
            let href = $(this).attr('data-attr');
            $('#delete-inv-item').attr('action', href);
        });
    </script>
@endpush

@section('customJs')
    <script type="text/javascript">
        @if ($message = Session::get('success'))
            notifyToUser('Expense Invoice Action', '{{ $message }}',
                'primary');
        @endif
    </script>
@endsection
