@extends('layouts.normalapp')

@push('css')
    <style>
        .filter-collapse {
            padding: 20px;
            background: rgba(221, 221, 221, 0.25);
        }
    </style>
@endpush


@section('content')
    <div class="container-fluid list-products">
        <div class="row">
            <div class="card">
                <div class="card-header pb-10">
                    <span class="float-start">
                        <h5 class="mb-2">Expense Invoice list</h5>
                        <span>Configuration</span>
                    </span>
                    <a href="{{ route('expense-invoice.create') }}" class="btn btn-primary float-end" type="button"><i
                            class="fa fa-edit"></i> Create New Expense Invoice</a>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-4 form-inline m-2">
                                <label for="formSelect"> <span>Show </span></label>
                                <select wire:model="perPage" class="p-1 m-2" id="formSelect">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <label class="p-1" for="formSelect"> <span> entries</span></label>
                            </div>
                            <div class="col-md-3 form-inline float-end">

                                <div class="input-group">
                                    <input wire:model.debounce.350ms="search" type="text" class="form-control"
                                        placeholder="Search...">
                                </div>
                            </div>
                            <div class="col-md form-inline float-end me-2">
                                <div class="input-group">
                                    <button type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse"
                                        aria-expanded="false" aria-controls="filterCollapse"
                                        class="btn btn-primary btn-square"><i class="fa fa-filter"></i> Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- filter form area --}}
                    <div class="row mt-0 collapse filter-collapse" id="filterCollapse">
                        <form class="row g-3">
                            <div class="col-md-4">
                                <label for="inputState" class="form-label">Create by</label>
                                <select id="inputState" class="form-select">
                                    <option selected>Choose...</option>
                                    <option>...</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="fromDate" class="form-label">From</label>
                                <input type="date" class="form-control" id="fromDate">
                            </div>
                            <div class="col-md-4">
                                <label for="toDate" class="form-label">To</label>
                                <input type="date" class="form-control" id="toDate">
                            </div>
                            <div class="col-md-4">
                                <label for="inputState" class="form-label">Status</label>
                                <select id="inputState" class="form-select">
                                    <option selected>Choose...</option>
                                    <option>...</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </form>
                    </div>

                    <div class="row mt-4">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Invoice No.</th>
                                        <th>Date</th>
                                        <th>Create By</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($expense_invoices) > 0)
                                        @foreach ($expense_invoices as $inv)
                                            <tr>
                                                <td>{{ $inv->invoice_no }}</td>
                                                <td>{{ $inv->invoice_date }}</td>
                                                <td>{{ $inv->staff->name }}</td>
                                                <td>{{ $inv->total_amount }}</td>
                                                <td>{{ $inv->admin_status }}</td>
                                                <td>
                                                    <a href="{{ route('expense-invoice.show', $inv->id) }}"
                                                        class="btn btn-outline-success btn-sm action-btn" title="View"
                                                        data-toggle="tooltip"><i class="fa fa-eye"></i></a>
                                                    <a href="{{ route('expense-invoice.edit', $inv->id) }}"
                                                        class="btn btn-outline-info btn-sm  action-btn" title="Edit"
                                                        data-toggle="tooltip"><i class="fa fa-pencil"></i></a>
                                                    <a href="javascript:void(0)" data-toggle="modal"
                                                        data-target="#deleteModal"
                                                        class="btn btn-outline-danger btn-sm  action-btn delete-inv"
                                                        title="Delete" data-toggle="tooltip" data-id="{{ $inv->id }}"
                                                        data-attr="{{ route('expense-invoice.destroy', $inv->id) }}"><i
                                                            class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" align="center">
                                                No Expense Invoice Found.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if (count($expense_invoices) > 0)
                        {{ $expense_invoices->links('cfms.livewire-pagination-links') }}
                        @endif
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
                    <h5 class="modal-title" id="exampleModalLabel2">Delete Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="delete-inv" method="post">
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

@section('customJs')
    <script type="text/javascript">
        @if ($message = Session::get('success'))
            notifyToUser('Expense Invoice Action', '{{ $message }}',
                'primary');
        @endif

        @if ($message = Session::get('error'))
            notifyToUser('Expense Invoice Error',
                'Error! {{ $message }}',
                'danger');
        @endif

        $(document).on('click', '.delete-inv', function() {
            $('#deleteModal').modal('show');
            let href = $(this).attr('data-attr');
            $('#delete-inv').attr('action', href);
        });
    </script>
@endsection
