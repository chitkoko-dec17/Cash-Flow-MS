<div class="row">
    <div class="card">
        <div class="card-header pb-10">
            <span class="float-start">
                <h5 class="mb-2">Configuration </h5>
                <span>Role Configuration</span>
            </span>
            <!-- <button wire:click="create" class="btn btn-primary float-end" type="button" data-bs-toggle="modal"
                data-bs-target="#dataprocessModal"><i class="fa fa-edit"></i> Create New Role</button> -->
        </div>
        <div class="card-body pt-0">

            <div class="row">
                <div class="table-container">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="fixed-column">
                                <th>Name</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($roles) > 0)
                                        @foreach ($roles as $role)
                                            <tr>
                                                <td class="fixed-column">{{$role->name}}</td>
                                                <!-- <td class="action-buttons">
                                                    <button wire:click="edit({{ $role->id }})"
                                                        class="btn btn-outline-info btn-sm  action-btn" title="Edit"
                                                        data-toggle="tooltip"><i class="fa fa-pencil"></i></button>
                                                </td> -->
                                            </tr>
                                        @endforeach
                                    @else
                                <tr>
                                    <td colspan="2" align="center">
                                        No Role Found.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                {{ $roles->links('cfms.livewire-pagination-links') }}
            </div>

            @include('cfms.modals.role-modal')
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
                notifyToUser('Role Updated', 'Success! Role is updated successfully!',
                    'primary');
            } else if (action == 'delete') {
                notifyToUser('Role Deleted', 'Success! Role is deleted successfully!',
                    'primary');
            } else if (action == 'create') {
                notifyToUser('Role Created', 'Success! Role is created successfully!',
                    'primary');
            } else if (action == 'store_duplicate_error') {
                notifyToUser('Role Duplicate Error',
                    'Error! Role is already created!',
                    'danger');
            }
        });
    </script>
@endsection
