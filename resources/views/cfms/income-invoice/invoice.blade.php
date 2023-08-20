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
    <div class="w-50 float-left mt-10">
        <!-- <p class="m-0 pt-5 text-bold w-100">Invoice Id - <span class="gray-color">#{{ $invoice_no }}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Order Id - <span class="gray-color">162695CDFS</span></p>
        <p class="m-0 pt-5 text-bold w-100">Order Date - <span class="gray-color">03-06-2022</span></p> -->
    </div>
    <div class="w-50 float-left logo mt-10">
        <!-- <img src="https://www.nicesnippets.com/image/imgpsh_fullsize.png"> <span>Nicesnippets.com</span> -->     
    </div>
    <div style="clear: both;"></div>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">From</th>
            <th class="w-50">To</th>
        </tr>
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
            <th class="w-50">Category</th>
            <th class="w-50">Item</th>
            <th class="w-50">Qty</th>
            <th class="w-50">Unit Price (MMK)</th>
            <th class="w-50">Total</th>
        </tr>
        @if (count($invoice_items) > 0)
            @foreach ($invoice_items as $invitem)
                <tr align="center">
                    <td>{{ $invitem->category->name }}</td>
                    <td>{{ $invitem->item->name }}</td>
                    <td>{{ $invitem->qty }}</td>
                    <td>{{ $invitem->amount }}</td>
                    <td>{{ $invitem->qty * $invitem->amount }}</td>
                </tr>
            @endforeach
        @endif
        <tr>
            <td colspan="5">
                <div class="total-part">
                    <div class="total-left w-85 float-left" align="right">
                        <p>Total Payable</p>
                    </div>
                    <div class="total-right w-15 float-left text-bold" align="right">
                        <p>{{ $invoice->total_amount }}</p>
                    </div>
                    <div style="clear: both;"></div>
                </div> 
            </td>
        </tr>
    </table>
</div>
</html>
