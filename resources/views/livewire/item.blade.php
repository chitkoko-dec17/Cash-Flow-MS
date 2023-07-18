<div class="row">
    <div class="card">
        <div class="card-header pb-10">
            <span class="float-start">
                <h5 class="mb-2">Configuration </h5>
                <span>Item Configuration</span>
            </span>
            <button wire:click="create" class="btn btn-primary float-end" type="button" data-bs-toggle="modal"
                data-bs-target="#businessUnitModal"><i class="fa fa-edit"></i> Create New Item</button>
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
                </div>
            </div>

            <div class="row">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($items) > 0)
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{$item->name}}</td>
                                        <td>{{ isset($item->category->name) ? $item->category->name : ''}}</td>
                                        <td>
                                            <button wire:click="edit({{ $item->id }})"
                                                class="btn btn-outline-info btn-sm  action-btn" title="Edit"
                                                data-toggle="tooltip"><i class="fa fa-pencil"></i></button>
                                            <button wire:click="confirmDelete({{ $item->id }}, '{{ $item->name }}')"
                                                class="btn btn-outline-danger btn-sm  action-btn" title="Delete"
                                                data-toggle="tooltip"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" align="center">
                                        No Item Found.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                {{ $items->links('cfms.livewire-pagination-links') }}
            </div>

            @include('cfms.modals.item-modal')
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
        window.addEventListener('openConfirmModal', function() {
            $('#confirmationModal').modal('show');
        });
        window.addEventListener('closeConfirmModal', function() {
            $('#confirmationModal').modal('hide');
        });

        Livewire.on('btnCreateOrUpdated', action => {
            if (action == 'edit') {
                notifyToUser('Item Updated', 'Success! Item is updated successfully!',
                    'primary');
            } else if (action == 'delete') {
                notifyToUser('Item Deleted', 'Success! Item is deleted successfully!',
                    'primary');
            } else if (action == 'create') {
                notifyToUser('Item Created', 'Success! Item is created successfully!',
                    'primary');
            } else if (action == 'store_duplicate_error') {
                notifyToUser('Item Duplicate Error',
                    'Error! Item is already created!',
                    'danger');
            }
        });
    </script>
@endsection
