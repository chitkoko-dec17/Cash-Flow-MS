<div class="row">
    <div class="card">
        <div class="card-header pb-10">
            <span class="float-start">
                <h6 class="mb-2">Business Unit </h6>
                <code>လုပ်ငန်းစာရင်းများ</code>
            </span>
            <button wire:click="createModal" class="btn btn-primary float-end" type="button" data-bs-toggle="modal"
                data-bs-target="#businessUnitModal"><i class="fa fa-edit"></i> Create New Business Unit</button>
            {{-- <button wire:click="detailModal" class="btn btn-primary float-end" type="button" data-bs-toggle="modal"
                data-bs-target="#businessUnitDetailModal"><i class="fa fa-eye"></i> View Business Unit</button> --}}
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
                                <th scope="col">Image</th>
                                <th class="fixed-column" scope="col">
                                    Name
                                    <span wire:click="sortBy('name')" class="float-end" style="cursor: pointer;">
                                        <i class="fa fa-sort text-muted"></i>
                                    </span>
                                </th>
                                <th scope="col">Phone</th>
                                <th scope="col">Address</th>
                                <th scope="col">Manager</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($businessUnits) > 0)
                                @foreach ($businessUnits as $businessUnit)
                                    <tr>
                                        <td>
                                            @if ($businessUnit->bu_image)
                                                <img src="{{ asset('storage/' . $businessUnit->bu_image) }}"
                                                    alt="{{ $businessUnit->bu_image }}" width="50">
                                            @else
                                                No Image
                                            @endif
                                        </td>
                                        <td class="fixed-column">{{ $businessUnit->name }} <b>({{$businessUnit->shorten_code }})</b></td>
                                        <td>{{ $businessUnit->phone }}</td>
                                        <td>{{ $businessUnit->address }}</td>
                                        <td><span class="badge badge-primary">{{ isset( $businessUnit->manager->name) ? $businessUnit->manager->name : '' }}</span></td>
                                        <td class="action-buttons">

                                            <button wire:click="detailModal({{ $businessUnit }})" class="btn btn-outline-success btn-sm action-btn"
                                                title="View" data-toggle="tooltip"><i class="fa fa-eye"></i></button>
                                            <button wire:click="edit({{ $businessUnit->id }})"
                                                class="btn btn-outline-info btn-sm  action-btn" title="Edit"
                                                data-toggle="tooltip"><i class="fa fa-pencil"></i></button>
                                            <button wire:click="confirmDelete({{ $businessUnit->id }}, '{{ $businessUnit->name }}')"
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
                {{ $businessUnits->links('cfms.livewire-pagination-links')}}

            </div>

            @include('cfms.modals.business-unit-detail-modal')
            @include('cfms.modals.business-unit-modal')
        </div>
    </div>
</div>

@section('customJs')
    <script type="text/javascript">
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
        window.addEventListener('openDetailModal', function() {
            $('#businessUnitDetailModal').modal('show');
        });
        window.addEventListener('closeDetailModal', function() {
            $('#businessUnitDetailModal').modal('hide');
        });
        window.addEventListener('openModal', function() {
            $('.addBusinessUnit').modal('show');
        });
        window.addEventListener('closeModal', function() {
            $('.addBusinessUnit').modal('hide');
        });
        window.addEventListener('openConfirmModal', function() {
            $('#confirmationModal').modal('show');
        });
        window.addEventListener('closeConfirmModal', function() {
            $('#confirmationModal').modal('hide');
        });

        Livewire.on('buCreateOrUpdated', action => {
            if (action == 'edit') {
                notifyToUser('Business Unit Updated', 'Success! Your Business Unit is updated successfully!',
                    'primary');
            } else if (action == 'delete') {
                notifyToUser('Business Unit Deleted', 'Success! Your Business Unit is deleted successfully!',
                    'primary');
            } else if (action == 'create') {
                notifyToUser('Business Unit Created', 'Success! Your Business Unit is created successfully!',
                    'primary');
            } else if (action == 'store_duplicate_error') {
                notifyToUser('Business Unit Duplicate Error',
                    'Error! The Business Unit code is already created! Please provide different Region code!',
                    'danger');
            }
        });
    </script>
@endsection
