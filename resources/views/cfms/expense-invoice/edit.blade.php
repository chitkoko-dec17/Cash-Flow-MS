@extends('layouts.normalapp')


@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dropzone.css') }}">
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
                                                <select class="form-control form-select" id="branch_id" name="branch_id" readonly>
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

                                            <div class="mb-3 col-sm-4">
                                                <label for="status">Invoice Status</label>
                                                <select class="form-control form-select" id="status" name="status">
                                                	@foreach($statuses as $skey => $statuse)
                                                    @if($invoice->admin_status == $skey)
                                                    	<option value="{{ $skey }}" selected>{{ $statuse }}</option>
                                                    @else
                                                    	<option value="{{ $skey }}">{{ $statuse }}</option>
                                                    @endif
                                                    
                                                  @endforeach
                                                </select>
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
                                                            @if($data['submit_btn_control'] == true)
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm action-btn remove-edit-btn" data-attr="{{ url('/expense/item', $invitem->id) }}"><i
                                                                    class="fa fa-trash"></i></button>
                                                            @endif
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

                                @foreach ($invoice_docs as $invd)

                                @php
                                    $ext = pathinfo($invd->inv_file, PATHINFO_EXTENSION);
                                @endphp
                                <a class="list-group-item list-group-item-action flex-column align-items-start mt-2"
                                    href="{{ url($invd->inv_file) }}" target="_blank">
                                    <div class="d-flex">
                                        <div style="margin: auto;">
                                            @if($ext == "xls")
                                                <i class="fa fa-file-excel-o"
                                                style="font-size: 4em;"></i>
                                            @elseif($ext == "pdf")
                                                <i class="fa fa-file-text-o" style="font-size: 4em;"></i>
                                            @else
                                                <i class="fa fa-file-image-o"
                                                style="font-size: 4em;"></i>
                                            @endif
                                        </div>
                                        <div></div>
                                        <div class="file-bottom w-100 p-2">
                                            <h6>{{ $invd->title .'.'. $ext }} </h6>
                                            <!-- <p class="mb-1">2.0 MB</p> -->
                                            <p> <b>Upload Date : </b>{{ date('d-m-Y', strtotime($invd->created_at)) }}</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript:void(0)" data-toggle="modal"
                                                        data-target="#deleteModal"
                                                        class="btn btn-outline-danger btn-sm  action-btn delete-inv-doc"
                                                        title="Delete" data-toggle="tooltip" 
                                                        data-attr="{{ url('/expense/doc', $invd->id) }}"><i
                                                            class="fa fa-trash"></i></a>
                                @endforeach
                                
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
                                                                <option value="{{ $skey }}">{{ $statuse }}</option>
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
    <script src="{{ asset('assets/js/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/dropzone/dropzone-script.js') }}"></script>
    <script>
        let jcates = '';
        let project_id = '{{ $invoice->project_id }}';
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
                            <select class="form-select category_id" name="category_ids_up[]">
                                <option value="">Select Category</option>
                                `+jcates+`
                            </select>
                        </td>
                        <td>
                            <select class="form-select item_id" name="items_up[]">
                                <option value="">Select Item</option>
                            </select>
                        </td>
                        <td><input type="number" class="form-control quantity" name="quantity_up[]" min="1" value="1"></td>
                        <td><input type="number" class="form-control amount" name="amount_up[]" step="0.01" value="0"></td>
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
