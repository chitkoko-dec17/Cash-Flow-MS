<form wire:submit.prevent="store">
    <div wire:ignore.self class="modal fade addDataManage" id="dataprocessModal" tabindex="-1"role="dialog"
        aria-labelledby="dataprocessModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="businessUnitModalLabel">
                        {{ $itemId ? 'Edit' : 'Create' }} Item
                    </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select wire:model="category_id" class="form-select" id="category_id">
                            <option value="">Select a item category</option>
                            @foreach ($itemcategories as $itemcategorie)
                                <option value="{{ $itemcategorie->id }}">{{ $itemcategorie->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input wire:model="name" type="text" class="form-control" id="name"
                            placeholder="Enter name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn btn-secondary" type="button"
                        data-bs-dismiss="modal">Close</button>
                    <button  class="btn btn-primary"
                        type="submit">{{ $itemId ? 'Save Changes' : 'Create' }}</button>
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
