<form wire:submit.prevent="store">
    <div wire:ignore.self class="modal fade addDataManage" id="dataprocessModal" tabindex="-1"role="dialog"
        aria-labelledby="dataprocessModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dataprocessModalLabel">
                        {{ $itemUnitID ? 'Edit' : 'Create' }} Item Unit
                    </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input wire:model="name" type="text" class="form-control" id="name"
                            placeholder="Enter unit name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input wire:model="description" type="text" class="form-control" id="description"
                            placeholder="Enter description">
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn btn-secondary" type="button"
                        data-bs-dismiss="modal">Close</button>
                    <button  class="btn btn-primary"
                        type="submit">{{ $itemUnitID ? 'Save Changes' : 'Create' }}</button>
                </div>
            </div>
        </div>
    </div>
</form>
