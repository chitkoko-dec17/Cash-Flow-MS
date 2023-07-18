<div class="row">
    <div class="card">
        <div class="card-header pb-10">
            <span class="float-start">
                <h5 class="mb-2">Configuration </h5>
                <span>Invoice Type Configuration</span>
            </span>
            <!-- <button wire:click="create" class="btn btn-primary float-end" type="button" data-bs-toggle="modal"
                data-bs-target="#dataprocessModal"><i class="fa fa-edit"></i> Create New Invoice Type</button> -->
        </div>
        <div class="card-body pt-0">

            <div class="row">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($invoicetypes) > 0)
                                @foreach ($invoicetypes as $invtype)
                                    <tr>
                                        <td>{{$invtype->name}}</td>
                                        <td>
                                            <button wire:click="edit({{ $invtype->id }})"
                                                        class="btn btn-outline-info btn-sm  action-btn" title="Edit"
                                                        data-toggle="tooltip"><i class="fa fa-pencil"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" align="center">
                                        No Invoice Type Found.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="row p-0">
                    {{ $invoicetypes->links('cfms.livewire-pagination-links') }}
                </div>
            </div>

            @include('cfms.modals.invoice-type-modal')
        </div>
    </div>
</div>

@section('customJs')
    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
        window.addEventListener('openModal', function() {
            $('.addDataManage').modal('show');
        });
        window.addEventListener('closeModal', function() {
            $('.addDataManage').modal('hide');
        });

        Livewire.on('btnCreateOrUpdated', action => {
            if (action == 'edit') {
                notifyToUser('Invoice Type Updated', 'Success! Invoice Type is updated successfully!',
                    'primary');
            } else if (action == 'delete') {
                notifyToUser('Invoice Type Deleted', 'Success! Invoice Type is deleted successfully!',
                    'primary');
            } else if (action == 'create') {
                notifyToUser('Invoice Type Created', 'Success! Invoice Type is created successfully!',
                    'primary');
            } else if (action == 'store_duplicate_error') {
                notifyToUser('Invoice Type Duplicate Error',
                    'Error! Invoice Type is already created!',
                    'danger');
            }
        });
    </script>
@endsection
