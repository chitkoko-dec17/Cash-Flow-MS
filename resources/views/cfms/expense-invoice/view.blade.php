@extends('layouts.normalapp')


@section('content')
    @component('components.expense_breadcrumb')
        @slot('breadcrumb_title')
            <h3>Invoice Detail</h3>
        @endslot
        <li class="breadcrumb-item"><a href="{{ route('expense-invoice.index') }}">Expense List</a></li>
        <li class="breadcrumb-item active">Detail</li> {{-- i think . no need to add href for active --}}
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
                                                            <label>{{ $invitem->item->name }}</label>
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
                        {{-- <div class="col-sm-12 text-center mt-3">
                            <button class="btn btn btn-primary me-2" type="button" onclick="myFunction()">Print</button>
                            <button class="btn btn-secondary" type="button">Cancel</button>
                        </div> --}}
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
                                <a class="list-group-item list-group-item-action flex-column align-items-start mt-2"
                                    href="javascript:void(0)">
                                    <div class="d-flex">
                                        <div style="margin: auto;"><i class="fa fa-file-text-o" style="font-size: 4em;"></i>
                                        </div>
                                        <div></div>
                                        <div class="file-bottom w-100 p-2">
                                            <h6>file.pdf </h6>
                                            <p class="mb-1">2.0 MB</p>
                                            <p> <b>last open : </b>1 hour ago</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="list-group-item list-group-item-action flex-column align-items-start mt-2"
                                    href="javascript:void(0)">
                                    <div class="d-flex">
                                        <div style="margin: auto;"><i class="fa fa-file-image-o"
                                                style="font-size: 4em;"></i></div>
                                        <div class="file-bottom w-100 p-2">
                                            <h6>Logo.png </h6>
                                            <p class="mb-1">2.0 MB</p>
                                            <p> <b>last open : </b>1 hour ago</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="list-group-item list-group-item-action flex-column align-items-start mt-2"
                                    href="javascript:void(0)">
                                    <div class="d-flex">
                                        <div style="margin: auto;"><i class="fa fa-file-excel-o"
                                                style="font-size: 4em;"></i></div>
                                        <div class="file-bottom w-100 p-2">
                                            <h6>file.excel</h6>
                                            <p class="mb-1">2.0 MB</p>
                                            <p> <b>last open : </b>1 hour ago</p>
                                        </div>
                                    </div>
                                </a>
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
                                                <li>
                                                    <div class="message my-message" style="width: 100%!important;">
                                                            <div class="message-data-time float-start">Nyan Lynn Htun<code>admin</code></div>
                                                            <div class="message-data text-end"><span
                                                                class="message-data-time">10:12 am </span>-<span class="message-data-time">12/2/2023</span></div>
                                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi, cumque laudantium. Ex inventore voluptatem architecto omnis tenetur possimus eaque repellat iusto eveniet, unde, nemo voluptas temporibus quod, doloremque suscipit minus?
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="message my-message" style="width: 100%!important;">
                                                            <div class="message-data-time float-start">Staff Name<code>staff</code></div>
                                                            <div class="message-data text-end"><span
                                                                class="message-data-time">10:12 am </span>-<span class="message-data-time">12/2/2023</span></div>
                                                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quod sequi consectetur iusto cumque, illo, accusantium ducimus porro assumenda repellendus, dolorum sunt ad! Molestiae numquam nisi iusto quibusdam, repudiandae et voluptates.
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="message my-message" style="width: 100%!important;">
                                                        <img class="rounded-circle float-start chat-user-img img-30"
                                                            src="{{ asset('assets/images/user/3.png') }}"
                                                            alt="" />
                                                            <div class="message-data-time float-start">Admin Name<code>manager</code></div>
                                                            <div class="message-data text-end"><span
                                                                class="message-data-time">10:12 am </span>-<span class="message-data-time">12/2/2023</span></div>
                                                        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ducimus eligendi ratione porro a facere amet blanditiis delectus neque iste dolorem labore alias fugiat similique, possimus repudiandae excepturi. Soluta, in facere.
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- end chat-history-->
                                        {{-- <div class="chat-message clearfix">
                                            <div class="row">
                                                <div class="col-xl-12 d-flex">
                                                    <div class="input-group text-box">
                                                        <input class="form-control input-txt-bx" id="message-to-send"
                                                            type="text" name="message-to-send"
                                                            placeholder="Type a message......" />
                                                        <button class="btn btn-primary input-group-text"
                                                            type="button">SEND</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
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
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/counter/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/counter/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/js/counter/counter-custom.js') }}"></script>
    <script src="{{ asset('assets/js/print.js') }}"></script>
@endpush
