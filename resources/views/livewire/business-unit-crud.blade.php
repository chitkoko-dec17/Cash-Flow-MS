<div>
    @if(session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="mb-3">
        <button wire:click="create" class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#businessUnitModal">Create New Business Unit</button>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Manager</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if (count($businessUnits) > 0)
                @foreach($businessUnits as $businessUnit)
                    <tr>
                        <td>
                            @if($businessUnit->bu_image)
                                <img src="{{ asset('storage/' . $businessUnit->bu_image) }}" alt="Business Unit Image" width="50">
                            @else
                                No Image
                            @endif
                        </td>
                        <td>{{ $businessUnit->name }}</td>
                        <td>{{ $businessUnit->phone }}</td>
                        <td>{{ $businessUnit->address }}</td>
                        <td>{{ $businessUnit->manager_id }}</td>
                        <td>
                            <button wire:click="edit({{ $businessUnit->id }})" class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#businessUnitModal">Edit</button>
                            <button wire:click="delete({{ $businessUnit->id }})" class="btn btn-danger">Delete</button>
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


    <div wire:ignore.self class="modal fade" id="businessUnitModal" tabindex="-1" role="dialog" aria-labelledby="businessUnitModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="businessUnitModalLabel">{{ $businessUnitId ? 'Edit Business Unit' : 'Create Business Unit' }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input wire:model="name" type="text" class="form-control" id="name" placeholder="Enter name">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="bu_image">Business Unit Image</label>
                            <input wire:model="bu_image" type="file" class="form-control" id="bu_image">
                            @error('bu_image') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input wire:model="phone" type="text" class="form-control" id="phone" placeholder="Enter phone number">
                            @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea wire:model="address" class="form-control" id="address" placeholder="Enter address"></textarea>
                            @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="manager">Manager Id</label>
                            <input wire:model="manager_id" type="number" class="form-control" id="manager" placeholder="Enter manager id">
                            @error('manager_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    <button wire:click="store" class="btn btn-primary" type="button">{{ $businessUnitId ? 'Save Changes' : 'Create' }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
