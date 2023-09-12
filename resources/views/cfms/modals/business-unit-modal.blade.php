<form wire:submit.prevent="store">
    <div wire:ignore.self class="modal fade addBusinessUnit" id="businessUnitModal" tabindex="-1"role="dialog"
        aria-labelledby="businessUnitModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="businessUnitModalLabel">
                        {{ $businessUnitId ? 'Edit Business Unit' : 'Create Business Unit' }}
                    </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input wire:model="name" type="text" class="form-control" id="name"
                            placeholder="Enter name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="bu_image">Business Unit Image</label>
                        <input wire:model="bu_image" type="file" class="form-control" id="bu_image">
                        @error('bu_image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="bu_letter_image">Letter Head Image</label>
                        <input wire:model="bu_letter_image" type="file" class="form-control" id="bu_letter_image">
                        @error('bu_letter_image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input wire:model="phone" type="text" class="form-control" id="phone"
                            placeholder="Enter phone number">
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
                    @if ($businessUnitId)
                        <label for="editManager">Manager</label>
                        <input id="editManager" type="text" class="form-control" value="{{ $editManager }}" disabled>
                    @else
                        <div class="form-group">
                            <label for="manager">Manager</label>
                            <select wire:model="manager_id" class="form-control" id="manager_id">
                                <option value="">Select a manager</option>
                                @foreach ($managers as $managerId => $managerName)
                                    <option value="{{ $managerId }}">{{ $managerName }}</option>
                                @endforeach
                            </select>
                            @error('manager_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn btn-secondary" type="button"
                        data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary"
                        type="submit">{{ $businessUnitId ? 'Save Changes' : 'Create' }}</button>
                </div>
            </div>
        </div>
    </div>
</form>

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
