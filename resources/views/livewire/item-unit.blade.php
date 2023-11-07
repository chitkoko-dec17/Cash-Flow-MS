<div class="row">
    <div class="card">
        <div class="card-header pb-10">
            <span class="float-start">
                <h5 class="mb-2">Configuration </h5>
                <span>Item Units Configuration</span>
            </span>
            <button wire:click="create" class="btn btn-primary float-end" type="button" data-bs-toggle="modal"
                data-bs-target="#dataprocessModal"><i class="fa fa-edit"></i> Create New Item Unit</button>
        </div>
        <div class="card-body pt-0">

            <div class="row">
                <div class="table-container">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="fixed-column">Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($itemUnits) > 0)
                                @foreach ($itemUnits as $itemUnit)
                                    <tr>
                                        <td class="fixed-column">{{$itemUnit->name}}</td>
                                        <td>{{$itemUnit->description}}</td>
                                        <td class="action-buttons">
                                            <button wire:click="edit({{ $itemUnit->id }})"
                                                        class="btn btn-outline-info btn-sm  action-btn" title="Edit"
                                                        data-toggle="tooltip"><i class="fa fa-pencil"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2" align="center">
                                        No Item Unit Found.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="row p-0">
                    {{ $itemUnits->links('cfms.livewire-pagination-links') }}
                </div>
            </div>

            @include('cfms.modals.item-unit-modal')
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
                notifyToUser('Item Unit Updated', 'Success! Item Unit is updated successfully!',
                    'primary');
            } else if (action == 'delete') {
                notifyToUser('Item Unit Deleted', 'Success! Item Unit is deleted successfully!',
                    'primary');
            } else if (action == 'create') {
                notifyToUser('Item Unit Created', 'Success! Item Unit is created successfully!',
                    'primary');
            } else if (action == 'store_duplicate_error') {
                notifyToUser('Item Unit Duplicate Error',
                    'Error! Item Unit is already created!',
                    'danger');
            }
        });
    </script>
@endsection
