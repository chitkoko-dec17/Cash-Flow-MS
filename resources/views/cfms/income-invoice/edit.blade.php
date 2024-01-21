@extends('layouts.normalapp')


@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endpush

@section('content')
    @component('components.income_breadcrumb')
        @slot('breadcrumb_title')
            <h3>Income Invoice Edit</h3>
        @endslot
        <li class="breadcrumb-item"><a href="{{ route('income-invoice.index') }}">Income List</a></li>
        <li class="breadcrumb-item active">Edit</li> {{-- i think . no need to add href for active --}}
    @endcomponent

    <div class="container-fluid list-products">
        <div class="row">
            <div class="card">
                <!-- <div class="card-header pb-0">
                    <h5>Income Invoice <code>Configuration - Edit</code></h5>
                </div> -->
                <div class="card-body">
                    <div class="tab-content pt-4" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="income-form">
                            <div class="row">
                                <div class="col-xl-12 col-sm-12">
                                    <form method="post" action="{{ route('income-invoice.update',$invoice->id) }}" enctype="multipart/form-data">
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

                                            <!-- admin -->
                                            @if($data['user_role'] == "Admin")
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
                                            @endif

                                            <!-- hr -->
                                            @if($data['user_role'] == "HR" &&  ($invoice->admin_status == 'pending' || $invoice->admin_status == 'checking' || $invoice->admin_status == 'checkedup'))
                                            <div class="mb-3 col-sm-4">
                                                <label for="status">Invoice Status</label>
                                                <select class="form-control form-select" id="status" name="status">
                                                    @foreach($data['hr_statuses'] as $skey => $statuse)
                                                        @if($invoice->admin_status == $skey)
                                                            <option value="{{ $skey }}" selected>{{ $statuse }}</option>
                                                        @else
                                                            <option value="{{ $skey }}">{{ $statuse }}</option>
                                                        @endif

                                                    @endforeach
                                                </select>
                                            </div>
                                            @endif

                                            <!-- manager -->
                                            @if($data['user_role'] == "Manager"  &&  ($invoice->admin_status == 'pending' || $invoice->admin_status == 'checking' || $invoice->admin_status == 'checkedup' || $invoice->admin_status == 'reject' || $invoice->admin_status == 'complete'))
                                            <div class="mb-3 col-sm-4">
                                                <label for="status">Invoice Status</label>
                                                <select class="form-control form-select" id="status" name="status">
                                                    @foreach($data['manager_statuses'] as $skey => $statuse)
                                                        @if($invoice->admin_status == $skey)
                                                            <option value="{{ $skey }}" selected>{{ $statuse }}</option>
                                                        @else
                                                            <option value="{{ $skey }}">{{ $statuse }}</option>
                                                        @endif

                                                    @endforeach
                                                </select>
                                            </div>
                                            @endif

                                            <!-- account -->
                                            @if($data['user_role'] == "Account"  &&  ($invoice->admin_status == 'complete' || $invoice->admin_status == 'ready_to_claim' || $invoice->admin_status == 'claimed'))
                                            <div class="mb-3 col-sm-4">
                                                <label for="status">Invoice Status</label>
                                                <select class="form-control form-select" id="status" name="status">
                                                    @foreach($data['account_statuses'] as $skey => $statuse)
                                                        @if($invoice->admin_status == $skey)
                                                            <option value="{{ $skey }}" selected>{{ $statuse }}</option>
                                                        @else
                                                            <option value="{{ $skey }}">{{ $statuse }}</option>
                                                        @endif

                                                    @endforeach
                                                </select>
                                            </div>
                                            @endif

                                            <div class="mb-3 col-sm-4">
                                                <label for="currency">Currency</label>
                                                <select class="form-control form-select" id="currency" name="currency" required>
                                                    <option value="MMK" {{($invoice->currency == "MMK") ? 'selected' : ''}} >Myanmar Kyat (MMK)</option>
                                                    <option value="USD" {{($invoice->currency == "USD") ? 'selected' : ''}}>US Dollar ($)</option>
                                                    <option value="CNY" {{($invoice->currency == "CNY") ? 'selected' : ''}}>Chinese Yuan (¥)</option>
                                                    <option value="THB" {{($invoice->currency == "THB") ? 'selected' : ''}}>Thai Baht (฿)</option>
                                                </select>
                                                @error('currency')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-sm-4" id="exchange_rate_group">
                                                <label for="exchange_rate">Exchange Rate (MMK)</label>
                                                <input id="exchange_rate" type="number" class="form-control" name="exchange_rate" value="{{$invoice->exchange_rate }}" placeholder="0"></td>
                                                @error('exchange_rate')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-sm-4">
                                                <label for="for_date">For Date</label>
                                                <input type="date" class="form-control" id="for_date"
                                                    name="for_date" value="{{$invoice->for_date }}">
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
                                                            <th class="fixed-column">Item</th>
                                                            <th>Payment</th>
                                                            <th>Description</th>
                                                            <th>Quantity & Unit</th>
                                                            <th>Unit Price (<span class="currency_sign">MMK</span>)</th>
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
                                                            <td class="total">{{ number_format($invitem->qty * $invitem->amount,2) }} {{$invoice->currency}}</td>
                                                            <td class="action-buttons">
                                                                @if($data['submit_btn_control'] == true && $invoice->admin_status != "claimed")
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm action-btn remove-edit-btn" data-attr="{{ url('/income/item', $invitem->id) }}"><i
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
                                                            <td colspan="2" class="totalAmount">{{ number_format($invoice->total_amount,2) }} {{$invoice->currency}}</td>
                                                            <input type="hidden" name="total_amount" id="total_amount" value="{{  $invoice->total_amount }}">
                                                            @error('total_amount')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            @if($data['submit_btn_control'] == true && $invoice->admin_status != "claimed")
                                                @if(Auth::user()->user_role == "Manager" || Auth::user()->user_role == "Staff")
                                                    <button type="button" class="btn btn-light mt-2" id="add-item-btn"><i
                                                    class="fa fa-plus"></i> Add Item</button>
                                                @endif
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


                                        <!-- Expense Items -->
                                        @if (count($exp_invoice_items) > 0)
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
                                                        @php
                                                            $exp_item_no = 1;
                                                        @endphp

                                                            @foreach ($exp_invoice_items as $exp_invitem)
                                                        <tr>
                                                            <td>{{ $exp_item_no }}</td>
                                                            <td>
                                                                <input type="hidden" name="exp_invitem[]" value="{{$exp_invitem->id}}">
                                                                {{$exp_invitem->category->name}}
                                                            </td>
                                                            <td>
                                                                {{$exp_invitem->item->name}}
                                                            </td>
                                                            <td>
                                                                <div class="row" style="justify-content: center;">
                                                                    <div class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-7">
                                                                        <input type="number" class="form-control quantity"
                                                                    name="exp_quantity[]" min="1" value="{{$exp_invitem->qty}}">
                                                                    </div>
                                                                    <div class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-5">
                                                                        <select class="form-select" name="exp_unit_ids[]">
                                                                            @foreach($itemunits as $unit)
                                                                                @if($exp_invitem->unit_id == $unit->id)
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
                                                                    name="exp_amount[]" step="0.01" value="{{$exp_invitem->amount}}"></td>
                                                            <td>
                                                                <select class="form-select" name="exp_payment_type[]">
                                                                    @if($exp_invitem->payment_type == "bank")
                                                                        <option value="cash">Cash</option>
                                                                        <option value="bank" selected>Bank</option>
                                                                    @else
                                                                        <option value="cash" selected>Cash</option>
                                                                        <option value="bank">Bank</option>
                                                                    @endif
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <textarea class="form-control" id="itemDescription" name="exp_idescription[]" rows="2">{{$exp_invitem->item_description}}</textarea>
                                                            </td>

                                                            <td><span class="exp_total">{{ number_format($exp_invitem->qty * $exp_invitem->amount,2) }}</span> {{$invoice->currency}}</td>
                                                            <td class="action-buttons">
                                                            </td>
                                                        </tr>
                                                            @php
                                                                $exp_item_no++;
                                                            @endphp
                                                            @endforeach

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="7" class="text-right" style="text-align: right;"><strong>Expense Total:</strong></td>
                                                            <td colspan="2" ><span class="expTotalAmount">{{$invoice->expense_total}} </span> <span class="currency_sign">{{$invoice->currency}}</span></td>
                                                            <input type="hidden" name="exp_total_amount" id="exp_total_amount" value="{{$invoice->expense_total}}">
                                                            @error('total_amount')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </tr>
                                                        <tr>
                                                            <td colspan="7" class="text-right" style="text-align: right;"><strong>Total (Income - Expense):</strong></td>
                                                            <td colspan="2" ><span class="netTotalAmount">{{$invoice->net_total}} </span> <span class="currency_sign">{{$invoice->currency}}</span></td>
                                                            <input type="hidden" name="net_total_amount" id="net_total_amount" value="{{$invoice->net_total}}">
                                                            @error('total_amount')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            @if($data['submit_btn_control'] == true && $invoice->admin_status != "claimed")
                                                @if(Auth::user()->user_role == "Manager" || Auth::user()->user_role == "Staff")
                                                    <button type="button" class="btn btn-light mt-2" id="add-exp-item-btn"><i
                                                    class="fa fa-plus"></i> Add Expense Item</button>
                                                @endif
                                            @endif
                                        </div>
                                        @endif

                                        @if($data['submit_btn_control'] == true && $invoice->admin_status != "claimed")
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
                                                        data-target="#deleteModal" type="button" class="btn btn-outline-danger pmd-ripple-effect btn-xs delete-inv-doc" data-attr="{{ url('/income/doc', $invd->id) }}"><i class="fa fa-trash m-0"></i></a>
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
                                        <form method="post" action="{{ route('income-note.add',$invoice->id) }}" >
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
        let currency = '{{ $invoice->currency }}';
        @foreach($itemcategories as $cate)
            jcates += '<option value="{{ $cate->id }}">{{ $cate->name }}</option>';
        @endforeach
        @foreach($itemunits as $unit)
            junits += '<option value="{{ $unit->id }}">{{ $unit->name }}</option>';
        @endforeach
        $(document).ready(function() {
        	$('#branch_id').trigger('change');

            let selectedCurrency = $("#currency").val(); // initialize current currency value;
            // Initially hide the "exchange_rate" input
            console.log(selectedCurrency);
            if (selectedCurrency === "MMK") {
                $("#exchange_rate_group").hide();
            } else {
                $("#exchange_rate_group").show();
            }

            // Force not to update Currency
            $("#currency").prop("disabled", true);
            // Set Currency sign to table datas
            $(".currency_sign").text(selectedCurrency);

            // Add new invoice item row
            $("#add-item-btn").click(function() {
                const newRow = `
                    <tr>
                        <td></td>
                        <td>
                            <select class="form-select category_id" name="category_ids_up[]" required>
                                <option value="">Select Category</option>
                                `+jcates+`
                            </select>
                        </td>
                        <td class="fixed-column">
                            <select class="form-select js-example-basic-single item_id" name="items_up[]" required>
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
                        <td class="total">0.00 `+currency+`</td>
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
                            <select class="form-select exp_category_id" name="exp_category_ids[]" required>
                                <option value="">Select Category</option>
                                `+jcates+`
                            </select>
                        </td>
                        <td class="fixed-column">
                            <select class="form-select js-example-basic-single item_id" name="exp_items_up[]" required>
                                <option value="">Select Item</option>
                            </select>
                        </td>
                        <td>
                            <div class="row" style="justify-content: center;">
                                <div class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-7">
                                    <input type="number" class="form-control quantity" name="exp_quantity_up[]" min="1" value="1">
                                </div>
                                <div class="m-0 p-0 ps-2 pe-2 col-sm-12 col-md-12 col-lg-5">
                                    <select class="form-select" name="exp_unit_ids_up[]">
                                        `+junits+`
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td><input type="number" class="form-control amount" name="exp_amount_up[]" step="0.01" value="0"></td>
                        <td>
                            <select class="form-select" name="exp_payment_type_up[]">
                                <option value="cash">Cash</option>
                                <option value="bank">Bank</option>
                            </select>
                        </td>
                        <td>
                            <textarea class="form-control" id="itemDescription" name="exp_idescription_up[]" rows="2"></textarea>
                        </td>
                        <td><span class="exp_total">0.00 </span> <span class="currency_sign">`+currency +`</span></td>
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
                $(this).closest("tr").find(".total").text(total.toFixed(2) + " "+currency);
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
                    const total = parseFloat($(this).find(".total").text().replace(currency, "").replace(/,/g, ""));
                    totalAmount += isNaN(total) ? 0 : total;
                });
                $(".totalAmount").text(totalAmount.toFixed(2) + " "+currency);
                $("#total_amount").val(totalAmount.toFixed(0));

                calculateNetTotal();
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
