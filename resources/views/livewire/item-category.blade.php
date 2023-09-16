<div class="row">
    <div class="card">
        <div class="card-header pb-10">
            <span class="float-start">
                <h5 class="mb-2">Configuration </h5>
                <span>Item Category Configuration</span>
            </span>
            <button wire:click="create" class="btn btn-primary float-end" type="button" data-bs-toggle="modal"
                data-bs-target="#dataprocessModal"><i class="fa fa-edit"></i> Create New Item Category</button>
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
                <div class="table-container">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="fixed-column">Name
                                    <span wire:click="sortBy('name')" class="float-end" style="cursor: pointer;">
                                        <i class="fa fa-sort text-muted"></i>
                                    </span>
                                </th>
                                <th>Business Unit
                                    <span wire:click="sortBy('business_unit_id')" class="float-end" style="cursor: pointer;">
                                        <i class="fa fa-sort text-muted"></i>
                                    </span>
                                </th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($itemcategories) > 0)
                                @foreach ($itemcategories as $itemcategory)
                                    <tr>
                                        <td class="fixed-column">{{$itemcategory->name}}</td>
                                        <td><span class="badge badge-primary">{{ isset($itemcategory->businessUnit->name) ? $itemcategory->businessUnit->name : "" }}</span></td>
                                        <td class="action-buttons">
                                            <button wire:click="edit({{ $itemcategory->id }})"
                                                class="btn btn-outline-info btn-sm  action-btn" title="Edit"
                                                data-toggle="tooltip"><i class="fa fa-pencil"></i></button>
                                            <button wire:click="confirmDelete({{ $itemcategory->id }}, '{{ $itemcategory->name }}')"
                                                class="btn btn-outline-danger btn-sm  action-btn" title="Delete"
                                                data-toggle="tooltip"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" align="center">
                                        No Item Category Found.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                {{ $itemcategories->links('cfms.livewire-pagination-links') }}
            </div>

            @include('cfms.modals.item-category-modal')
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
                notifyToUser('Item Category Updated', 'Success! Item Category is updated successfully!',
                    'primary');
            } else if (action == 'delete') {
                notifyToUser('Item Category Deleted', 'Success! Item Category is deleted successfully!',
                    'primary');
            } else if (action == 'create') {
                notifyToUser('Item Category Created', 'Success! Item Category is created successfully!',
                    'primary');
            } else if (action == 'store_duplicate_error') {
                notifyToUser('Item Category Duplicate Error',
                    'Error! Item Category is already created!',
                    'danger');
            }
        });
    </script>
@endsection
