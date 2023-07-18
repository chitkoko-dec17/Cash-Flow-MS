<form wire:submit.prevent="store">
    <div wire:ignore.self class="modal fade addDataManage" id="dataprocessModal" tabindex="-1"role="dialog"
        aria-labelledby="dataprocessModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="businessUnitModalLabel">
                        {{ $userId ? 'Edit' : 'Create' }} User
                    </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="role_id">Role</label>
                        <select wire:model="role_id" class="form-select" id="role_id">
                            <option value="">Select a role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_id')
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
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input wire:model="email" type="email" class="form-control" id="email"
                            placeholder="Enter email address">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    @php if(!$userId){ @endphp
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input wire:model="password" type="password" class="form-control" id="password"
                            placeholder="Enter password">
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="confirmpassword">Confirm Password</label>
                        <input wire:model="confirmpassword" type="password" class="form-control" id="confirmpassword"
                            placeholder="Enter confirm password">
                        @error('confirmpassword')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    @php } @endphp
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
                </div>
                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn btn-secondary" type="button"
                        data-bs-dismiss="modal">Close</button>
                    <button  class="btn btn-primary"
                        type="submit">{{ $userId ? 'Save Changes' : 'Create' }}</button>
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

