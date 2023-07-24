<div class="row">
    <div class="card">
        <div class="card-header pb-10">
            <span class="float-start">
                <h5 class="mb-2">Configuration </h5>
                <span>User Configuration</span>
            </span>
            <button wire:click="create" class="btn btn-primary float-end" type="button" data-bs-toggle="modal"
                data-bs-target="#businessUnitModal"><i class="fa fa-edit"></i> Create New User</button>
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
                                <th>Role</th>
                                <th>Email</th>
                                <th>Branch</th>
                                <th>Project</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($users) > 0)
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->role->name}} - {{$user->role_id}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->branchUser ? $user->branchUser->branch->name : ''}} - {{$user->branchUser ? $user->branchUser->branch->id : ''}} </td>
                                        <td>{{$user->projectUser ? $user->projectUser->project->name : ''}} - {{$user->projectUser ? $user->projectUser->project->id : ''}}</td>
                                        <td>
                                            <button wire:click="" class="btn btn-outline-success btn-sm action-btn"
                                                title="View" data-toggle="tooltip"><i class="fa fa-eye"></i></button>
                                            <button wire:click="edit({{ $user->id }})"
                                                class="btn btn-outline-info btn-sm  action-btn" title="Edit"
                                                data-toggle="tooltip"><i class="fa fa-pencil"></i></button>
                                            <button wire:click="confirmDelete({{ $user->id }}, '{{ $user->name }}')"
                                                class="btn btn-outline-danger btn-sm  action-btn" title="Delete"
                                                data-toggle="tooltip"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" align="center">
                                        No User Found.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                {{ $users->links('cfms.livewire-pagination-links') }}
            </div>

            @include('cfms.modals.user-modal')
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
                notifyToUser('User Updated', 'Success! User is updated successfully!',
                    'primary');
            } else if (action == 'delete') {
                notifyToUser('User Deleted', 'Success! User is deleted successfully!',
                    'primary');
            } else if (action == 'create') {
                notifyToUser('User Created', 'Success! User is created successfully!',
                    'primary');
            } else if (action == 'store_duplicate_error') {
                notifyToUser('User Duplicate Error',
                    'Error! User is already created!',
                    'danger');
            }
        });
    </script>
@endsection
