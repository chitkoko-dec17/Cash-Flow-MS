@extends('layouts.normalapp')

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/todo.css')}}">
@endpush

@section('content')
    @component('components.expense_breadcrumb')
        @slot('breadcrumb_title')
            <h3>Invoice Detail</h3>
        @endslot
        <li class="breadcrumb-item"><a href="{{ route('expense-invoice.index') }}">Expense List</a></li>
        <li class="breadcrumb-item active">Detail</li> {{-- i think . no need to add href for active --}}

        @slot('print_url')
            <li><a href="{{ route('expense-invoice.template',$invoice->id) }}" data-container="body" data-bs-toggle="popover" data-placement="top" title="Print" data-original-title="Tables"><i data-feather="printer"></i></a></li>
        @endslot
    @endcomponent
    <div class="container-fluid list-products">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <div>
                                <div class="row invo-header">
                                    <div class="col-sm-6">
                                        @if (isset($invoice->admin->name))
                                            <div class="media">
                                                <div class="media-body">
                                                    <h4 class="media-heading f-w-600">{{ $invoice->admin->name }}</h4>
                                                    <p>
                                                        Admin
                                                    </p>
                                                </div>
                                            </div>
                                            <!-- End Info-->
                                        @endif
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="text-md-end text-xs-center">
                                            <h3 style="font-size:1.2em;">Invoice #<span
                                                    class="">{{ $invoice_no }}</span></h3>
                                            <p>
                                                Created: {{ $invoice->invoice_date }}
                                            </p>
                                        </div>
                                        <!-- End Title-->
                                    </div>
                                </div>
                            </div>
                            <!-- End InvoiceTop-->
                            <div class="row invo-profile">
                                <div class="col-xl-4">
                                    @if (isset($invoice->manager->name))
                                        <div class="media">
                                            <div class="media-body">
                                                <h4 class="media-heading f-w-600">{{ $invoice->manager->name }}</h4>
                                                <p>
                                                    Manager
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-xl-8">
                                    <div class="text-xl-end" id="project">
                                        <h6>Description</h6>
                                        <p>
                                            {{ $invoice->description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- End Invoice Mid-->
                            <div class="row invo-profile">
                                <div class="col-xl-4">
                                    @if (isset($invoice->staff->name))
                                        <div class="media">
                                            <div class="media-body">
                                                <h4 class="media-heading f-w-600">{{ $invoice->staff->name }}</h4>
                                                <p>
                                                    Created by
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-xl-8">
                                    <div class="text-xl-end" id="project">
                                        <h6>Status</h6>
                                        <p>
                                            {{ $invoice->admin_status }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- End Invoice Mid-->
                            <div>
                                <div class="table-responsive invoice-table" id="table">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                            <tr>
                                                <td class="item">
                                                    <h6 class="p-2 mb-0">Category</h6>
                                                </td>
                                                <td class="Hours">
                                                    <h6 class="p-2 mb-0">Item</h6>
                                                </td>
                                                <td class="Hours">
                                                    <h6 class="p-2 mb-0">Quantity</h6>
                                                </td>
                                                <td class="Rate">
                                                    <h6 class="p-2 mb-0">Unit Price (MMK)</h6>
                                                </td>
                                                <td class="subtotal">
                                                    <h6 class="p-2 mb-0">Total</h6>
                                                </td>
                                            </tr>
                                            @if (count($invoice_items) > 0)
                                                @foreach ($invoice_items as $invitem)
                                                    <tr>
                                                        <td>
                                                            <label>{{ $invitem->category->name }}</label>
                                                        </td>
                                                        <td>
                                                            <label>
                                                                @if($data['user_role'] == "Admin")
                                                                <a  href="javascript:void(0)"
                                                                    class="item-history-inv"
                                                                    data-toggle="modal"
                                                                    data-target="#itemHistoryModal"
                                                                    data-attr=""
                                                                    data-id="{{ $invitem->item_id }}"
                                                                    style="pointer:hand;">
                                                                    <span class="badge badge-primary">
                                                                        {{ $invitem->item->name }}
                                                                    </span>
                                                                </a>
                                                                @else
                                                                    {{ $invitem->item->name }}
                                                                @endif
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <p class="itemtext digits">{{ $invitem->qty }}</p>
                                                        </td>
                                                        <td>
                                                            <p class="itemtext digits">{{ $invitem->amount }}</p>
                                                        </td>
                                                        <td>
                                                            <p class="itemtext digits">
                                                                {{ $invitem->qty * $invitem->amount }}</p>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="Rate">
                                                    <h6 class="mb-0 p-2">Total</h6>
                                                </td>
                                                <td class="payment digits">
                                                    <h6 class="mb-0 p-2">{{ $invoice->total_amount }}</h6>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- End Table-->

                            </div>
                            <!-- End InvoiceBot-->
                        </div>
                        <!-- End Invoice-->
                        <!-- End Invoice Holder-->
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
                                    <li class="list-group-item d-flex mb-5">
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
                                            <a href="{{ url($invd->inv_file) }}" type="button" class="btn btn-outline-primary pmd-ripple-effect btn-xs"><i class="fa fa-eye m-0"></i></a>
                                            <a href="#" type="button" class="btn btn-outline-danger pmd-ripple-effect btn-xs"><i class="fa fa-trash m-0"></i></a>
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
                                            <h5>Note History</h5>
                                        </div>
                                        <!-- chat-header end-->
                                        <div class="chat-history chat-msg-box custom-scrollbar">
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

    <!-- Item History Modal Box -->
    <div class="modal fade" id="itemHistoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">Item History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="item-history">
                                <thead>
                                    <tr>
                                        <th>Invoice No.</th>
                                        <th>Item Name</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/counter/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/counter/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/js/counter/counter-custom.js') }}"></script>
    <script src="{{ asset('assets/js/print.js') }}"></script>
    <script src="{{ asset('js/jquery-dateformat.min.js') }}"></script>
@endpush

@section('customJs')
    <script type="text/javascript">
        $(document).on('click', '.item-history-inv', function() {
            let item_id = $(this).attr('data-id');
            get_items(item_id);
        });

        function get_items(item_id){
            $('#item-history tbody').empty();
            if(item_id){
                $.ajax({
                    url: "<?php echo route('expense-invoice.item') ?>",
                    method: 'GET',
                    data: {item_id:item_id},
                    success: function(data) {

                        $.each(data.data, function(value, text){

                            let row = "<tr>";
                            row += "<td>EXINV-"+text.invoice.invoice_no+"</td>";
                            row += "<td>"+text.item.name+"</td>";
                            row += "<td>"+text.qty+"</td>";
                            row += "<td>"+text.amount+"</td>";
                            row += "<td>"+$.format.date(text.created_at, "dd/MM/yyyy") +"</td>";
                            row += "</tr>";
                            $('#item-history').append(row);
                            $('#itemHistoryModal').modal('show');
                        });
                    }
                });
            }
        }

    </script>
@endsection
