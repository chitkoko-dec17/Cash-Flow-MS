<!DOCTYPE html>
<html>
<head>
    <title>Income Invoice</title>
</head>
<style type="text/css">
    body{
        font-family: 'Roboto Condensed', sans-serif;
    }
    .m-0{
        margin: 0px;
    }
    .p-0{
        padding: 0px;
    }
    .pt-5{
        padding-top:5px;
    }
    .mt-10{
        margin-top:10px;
    }
    .text-center{
        text-align:center !important;
    }
    .w-100{
        width: 100%;
    }
    .w-50{
        width:50%;   
    }
    .w-85{
        width:85%;   
    }
    .w-15{
        width:15%;   
    }
    .logo img{
        width:45px;
        height:45px;
        padding-top:30px;
    }
    .logo span{
        margin-left:8px;
        top:19px;
        position: absolute;
        font-weight: bold;
        font-size:25px;
    }
    .gray-color{
        color:#5D5D5D;
    }
    .text-bold{
        font-weight: bold;
    }
    .border{
        border:1px solid black;
    }
    table tr,th,td{
        border: 1px solid #d2d2d2;
        border-collapse:collapse;
        padding:7px 8px;
    }
    table tr th{
        background: #F4F4F4;
        font-size:15px;
    }
    table tr td{
        font-size:13px;
    }
    table{
        border-collapse:collapse;
    }
    .box-text p{
        line-height:10px;
    }
    .float-left{
        float:left;
    }
    .total-part{
        font-size:16px;
        line-height:12px;
    }
    .total-right p{
        padding-right:20px;
    }
</style>
<body>
<div class="head-title">
    <h1 class="text-center m-0 p-0">Income Invoice</h1>
</div>
<div class="add-detail mt-10">
    <img src="/storage/{{ $invoice->businessUnit->bu_letter_image }}">
    <div style="clear: both;"></div>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <!-- <tr>
            <th class="w-50">From</th>
            <th class="w-50">To</th>
        </tr> -->
        <tr>
            <td>
                <div class="box-text">
                    @if (isset($invoice->admin->name))
                    <p>Admin : {{ $invoice->admin->name }}</p>
                    @endif
                    @if (isset($invoice->manager->name))
                    <p>Manager : {{ $invoice->manager->name }}</p>
                    @endif
                    @if (isset($invoice->staff->name))
                    <p>Created by : {{ $invoice->staff->name }}</p>
                    @endif
                </div>
            </td>
            <td>
                <div class="box-text">
                    <p>Invoice No. : #{{ $invoice_no }}</p>
                    <p>Invoice Date : {{ $invoice->invoice_date }}</p>
                    <p>Status : {{ $invoice->admin_status }}</p>
                    <p>Description </p>
                    <p>{{ $invoice->description }}</p>
                </div>
            </td>
        </tr>
    </table>
</div>
<!-- <div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">Payment Method</th>
            <th class="w-50">Shipping Method</th>
        </tr>
        <tr>
            <td>Cash On Delivery</td>
            <td>Free Shipping - Free Shipping</td>
        </tr>
    </table>
</div> -->
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-10">No</th>
            <th class="w-50">Category</th>
            <th class="w-50">Item</th>
            <th class="w-30">Payment</th>
            <th class="w-50">Description</th>
            <th class="w-50">Qty</th>
            <th class="w-30">Unit Price (MMK)</th>
            <th class="w-50">Total</th>
        </tr>
        @if (count($invoice_items) > 0)
            @php
                $item_no = 1;
            @endphp
            @foreach ($invoice_items as $invitem)
                <tr align="center">
                    <td>{{ $item_no }}</td>
                    <td>{{ $invitem->category->name }}</td>
                    <td>{{ $invitem->item->name }}</td>
                    <td>{{ ($invitem->payment_type == "bank") ? 'Bank' : 'Cash'  }}</td>
                    <td>{{ $invitem->item_description }}</td>
                    <td>{{ $invitem->qty }} {{ ($invitem->unit_id) ? $invitem->unit->name : ''; }}</td>
                    <td>{{ number_format($invitem->amount, 2) }}</td>
                    <td>{{ number_format($invitem->qty * $invitem->amount, 2) }}</td>
                </tr>
            @php
                $item_no++;
            @endphp
            @endforeach
        @endif
        <tr>
            <td colspan="8">
                <div class="total-part">
                    <div class="total-left w-85 float-left" align="right">
                        <p>Total Payable</p>
                    </div>
                    <div class="total-right w-15 float-left text-bold" align="right">
                        <p>{{ number_format($invoice->total_amount,2) }}</p>
                    </div>
                    <div style="clear: both;"></div>
                </div> 
            </td>
        </tr>
    </table>
</div>
</html>
