<div class="container-fluid">
    <div class="row">
        <div class="col-md">
            <div class="card">
                <div class="card-header pb-4">
                    <span class="float-start">
                        <h6 class="mb-2">Project List</h6>
                        <code class="p-0">ပရောဂျက်စာရင်းများ</code>
                    </span>
                    <button wire:click="create" class="btn btn-primary float-end" type="button" data-bs-toggle="modal"
                        data-bs-target="#dataprocessModal"><i class="fa fa-edit"></i> Create New Project</button>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-4 form-inline m-2">
                                <label for="formSelect"> <span>Show</span></label>
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
                        <div class="table-container ">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th class="fixed-column" scope="col">Project Name
                                            <span wire:click="sortBy('name')" class="float-end" style="cursor: pointer;">
                                                <i class="fa fa-sort text-muted"></i>
                                            </span>
                                        </th>
                                        <th scope="col">Branch Name
                                            <span wire:click="sortBy('branch_id')" class="float-end" style="cursor: pointer;">
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
                                    @if (count($projects) > 0)
                                        @foreach ($projects as $project)
                                            <tr>
                                                <td class="fixed-column">{{ $project->name}}</td>
                                                <td><span class="badge badge-primary">{{ isset($project->branch->name) ? $project->branch->name : "" }}</span></td>
                                                <td>{{ $project->phone}}</td>
                                                <td>{{ $project->address}}</td>
                                                <td class="action-buttons">
                                                    <button wire:click="" class="btn btn-outline-success btn-sm action-btn"
                                                        title="View" data-toggle="tooltip"><i class="fa fa-eye"></i></button>
                                                    <button wire:click="edit({{ $project->id }})"
                                                        class="btn btn-outline-info btn-sm  action-btn" title="Edit"
                                                        data-toggle="tooltip"><i class="fa fa-pencil"></i></button>
                                                    <button wire:click="confirmDelete({{ $project->id }}, '{{ $project->name }}')"
                                                        class="btn btn-outline-danger btn-sm  action-btn" title="Delete"
                                                        data-toggle="tooltip"><i class="fa fa-trash"></i></button>
                                                </td>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" align="center">
                                                No Projects Found.
                                            </td>
                                        </tr>
                                    @endif
                                    <!-- Repeat the above row for each branch -->
                                </tbody>
                            </table>
                        </div>
                        {{ $projects->links('cfms.livewire-pagination-links')}}
                    </div>
                    @include('cfms.modals.project-modal')
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
                notifyToUser('Project Updated', 'Success! Project is updated successfully!',
                    'primary');
            } else if (action == 'delete') {
                notifyToUser('Project Deleted', 'Success! Project is deleted successfully!',
                    'primary');
            } else if (action == 'create') {
                notifyToUser('Project Created', 'Success! Project is created successfully!',
                    'primary');
            } else if (action == 'store_duplicate_error') {
                notifyToUser('Project Duplicate Error',
                    'Error! Project is already created!',
                    'danger');
            }
        });
    </script>
@endsection

