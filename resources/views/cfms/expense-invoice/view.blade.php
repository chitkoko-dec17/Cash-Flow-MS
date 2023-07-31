@extends('layouts.normalapp')


@section('content')
<div class="container-fluid list-products">
  <div class="row">
      <div class="col-sm-12">
          <div class="card">
              <div class="card-body">
                  <div>
                      <div>
                          <div class="row invo-header">
                              <div class="col-sm-6">
                                @if(isset($invoice->admin->name))
                                  <div class="media">
                                      <div class="media-body">
                                          <h4 class="media-heading f-w-600">{{$invoice->admin->name }}</h4>
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
                                      <h3 style="font-size:1.2em;">Invoice #<span class="">{{ $invoice_no }}</span></h3>
                                      <p>
                                          Created: {{$invoice->invoice_date }}
                                      </p>
                                  </div>
                                  <!-- End Title-->
                              </div>
                          </div>
                      </div>
                      <!-- End InvoiceTop-->
                      <div class="row invo-profile">
                          <div class="col-xl-4">
                            @if(isset($invoice->manager->name))
                              <div class="media">
                                  <div class="media-body">
                                      <h4 class="media-heading f-w-600">{{$invoice->manager->name }}</h4>
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
                                      {{$invoice->description }}
                                  </p>
                              </div>
                          </div>
                      </div>
                      <!-- End Invoice Mid-->
                      <div class="row invo-profile">
                          <div class="col-xl-4">
                            @if(isset($invoice->staff->name))
                              <div class="media">
                                  <div class="media-body">
                                      <h4 class="media-heading f-w-600">{{$invoice->staff->name }}</h4>
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
                                      {{$invoice->admin_status }}
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
                                                <label>{{$invitem->category->name}}</label>
                                            </td>
                                            <td>
                                                <label>{{$invitem->item->name}}</label>
                                            </td>
                                            <td>
                                                <p class="itemtext digits">{{$invitem->qty}}</p>
                                            </td>
                                            <td>
                                                <p class="itemtext digits">{{$invitem->amount}}</p>
                                            </td>
                                            <td>
                                                <p class="itemtext digits">{{$invitem->qty * $invitem->amount}}</p>
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
                                              <h6 class="mb-0 p-2">{{$invoice->total_amount }}</h6>
                                          </td>
                                      </tr>
                                  </tbody>
                              </table>
                          </div>
                          <!-- End Table-->

                      </div>
                      <!-- End InvoiceBot-->
                  </div>
                  <div class="col-sm-12 text-center mt-3">
                      <button class="btn btn btn-primary me-2" type="button" onclick="myFunction()">Print</button>
                      <button class="btn btn-secondary" type="button">Cancel</button>
                  </div>
                  <!-- End Invoice-->
                  <!-- End Invoice Holder-->
              </div>
          </div>
      </div>
  </div>

</div>
@endsection

@push('scripts')
  <script src="{{asset('assets/js/counter/jquery.waypoints.min.js')}}"></script>
  <script src="{{asset('assets/js/counter/jquery.counterup.min.js')}}"></script>
  <script src="{{asset('assets/js/counter/counter-custom.js')}}"></script>
  <script src="{{asset('assets/js/print.js')}}"></script>
@endpush
