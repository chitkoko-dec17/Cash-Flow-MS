@extends('layouts.normalapp')


@section('content')
    @component('components.return_breadcrumb')
        @slot('breadcrumb_title')
            <h3>Return Invoice Detail</h3>
        @endslot
        <li class="breadcrumb-item"><a href="{{ route('return-invoice.index') }}">Return List</a></li>
        <li class="breadcrumb-item active">Detail</li>
    @endcomponent
    <div class="container-fluid list-products">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content pt-4" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="return-form">
                            <div class="row">
                                <div class="col-xl-12 col-sm-12">
                                    
                                        <div class="row">
                                            <div class="mb-3 col-sm-4">
                                                <label for="invoice_id">Expense Invoice No.</label>
                                                <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="{{$invoice->invoice_date }}">
                                            </div>

                                            <div class="mb-3 col-sm-4">
                                                <label for="invoice_date">Invoice Date</label>
                                                <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="{{$invoice->invoice_date }}">
                                            </div>

                                            <div class="mb-3 col-sm-4">
                                                <label for="total_amount">Total Amount</label>
                                                <input type="text" class="form-control" id="total_amount" name="total_amount" value="{{$invoice->total_amount }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3">{{$invoice->description }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="docs">Claim Return File</label>
                                            <div class="col-xl-6">
                                              @if($invoice->return_form_file)
                                              <div class="list-group">
                                                  <ul>
                                                      @php
                                                          $ext = pathinfo($invoice->return_form_file, PATHINFO_EXTENSION);
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
                                                              <h6>{{ basename($invoice->return_form_file) }}</h6>
                                                              <p><b class="f-12">Upload Date : </b>{{ date('d-m-Y', strtotime($invoice->created_at)) }}</p>
                                                              <a href="{{ url($invoice->return_form_file) }}" target="_blank" type="button" class="btn btn-outline-primary pmd-ripple-effect btn-xs"><i class="fa fa-eye m-0"></i></a>
                                                          </span>
                                                      </li>
                                                  </ul>
                                              </div>
                                              @endif
                                          </div>
                                        </div>
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
  
@endpush
