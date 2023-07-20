<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header pb-0">
                    <span class="float-start">
                        <h6 class="mb-2">Branch Configuration</h6>
                        <span><code>ဌာနစာရင်းများကို</code> ပြင်ဆင်မည်။</span>
                    </span>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="{{ $branchId ? 'update' : 'create' }}">
                        <div class="form-group">
                            <label for="business_unit_id" class="mb-2">
                                Business Unit <span wire:click="" class="badge rounded-pill badge-info"> <i class="icon"><i class="icon-plus"></i></i></span>
                            </label>
                            <select wire:model="business_unit_id" class="col-sm-12 form-select" id="business_unit_id">
                                <option value="">Select a business unit</option>
                                @foreach ($businessUnits as $businessUnit)
                                    <option value="{{ $businessUnit->id }}">{{$businessUnit->name}}</option>
                                @endforeach
                            </select>
                            @error('business_unit_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input wire:model="name" type="text" class="form-control" id="name" placeholder="Enter name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input wire:model="phone" type="phone" class="form-control" id="phone" placeholder="Enter phone">
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea wire:model="address" class="form-control" id="address" placeholder="Enter address"></textarea>
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @if ($branchId)
                            <button wire:click="resetForm" type="button" class="btn btn-secondary">Cancel</button>
                        @endif
                        <button type="submit" class="btn btn-primary">{{ $branchId ? 'Save Changes' : 'Create' }}</button>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md">
            <div class="card">
                <div class="card-header pb-4">
                    <span class="float-start">
                        <h6 class="mb-2">Branch List</h6>
                        <code class="p-0">ဌာနစာရင်းများ</code>
                    </span>
                    <div class="col-md-4 pe-0 float-end">
                        <input wire:model.debounce.350ms="search" class="form-control" type="text" name="search" placeholder="Search..." />
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="table-responsive ">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Branch Name
                                            <span wire:click="sortBy('name')" class="float-end" style="cursor: pointer;">
                                                <i class="fa fa-sort text-muted"></i>
                                            </span>
                                        </th>
                                        <th scope="col">Business Unit Name
                                            <span wire:click="sortBy('business_unit_id')" class="float-end" style="cursor: pointer;">
                                                <i class="fa fa-sort text-muted"></i>
                                            </span>
                                        </th>
                                        <th scope="col">Phone
                                            <span wire:click="sortBy('phone')" class="float-end" style="cursor: pointer;">
                                                <i class="fa fa-sort text-muted"></i>
                                            </span>
                                        </th>
                                        <th scope="col">Address
                                            <span wire:click="sortBy('address')" class="float-end" style="cursor: pointer;">
                                                <i class="fa fa-sort text-muted"></i>
                                            </span>
                                        </th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Loop through branches data -->
                                    @if (count($branches) > 0)
                                        @foreach ($branches as $branch)
                                            <tr>
                                                <td>{{ $branch->name}}</td>
                                                <td><span class="badge badge-primary">{{ isset($branch->businessUnit->name) ? $branch->businessUnit->name : "" }}</span></td>
                                                <td>{{ $branch->phone}}</td>
                                                <td>{{ $branch->address}}</td>
                                                <td>
                                                    <button wire:click="" class="btn btn-outline-success btn-sm action-btn"
                                                        title="View" data-toggle="tooltip"><i class="fa fa-eye"></i></button>
                                                    <button wire:click="edit({{ $branch->id }})"
                                                        class="btn btn-outline-info btn-sm  action-btn" title="Edit"
                                                        data-toggle="tooltip"><i class="fa fa-pencil"></i></button>
                                                    <button wire:click="confirmDelete({{ $branch->id }}, '{{ $branch->name }}')"
                                                        class="btn btn-outline-danger btn-sm  action-btn" title="Delete"
                                                        data-toggle="tooltip"><i class="fa fa-trash"></i></button>
                                                </td>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" align="center">
                                                No Branches Found.
                                            </td>
                                        </tr>
                                    @endif
                                    <!-- Repeat the above row for each branch -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Confirmation Modal -->
            <div wire:ignore.self class="modal fade" id="confirmationModal" tabindex="-1"role="dialog"
            aria-labelledby="confirmationModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalLabel">Confirm Deletion</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <code>{{ $selectedName }}</code>?
                    </div>
                    <div class="modal-footer">
                        <button wire:click="closeModal" class="btn btn-secondary" type="button"
                            data-bs-dismiss="modal">Close</button>
                        <button wire:click="delete()" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
            </div>
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
                notifyToUser('Branch Updated', 'Success! Branch is updated successfully!',
                    'primary');
            } else if (action == 'delete') {
                notifyToUser('Branch Deleted', 'Success! Branch is deleted successfully!',
                    'primary');
            } else if (action == 'create') {
                notifyToUser('Branch Created', 'Success! Branch is created successfully!',
                    'primary');
            } else if (action == 'store_duplicate_error') {
                notifyToUser('Branch Duplicate Error',
                    'Error! Branch is already created!',
                    'danger');
            }
        });
    </script>
@endsection

