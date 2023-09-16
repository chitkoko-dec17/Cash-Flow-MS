<form wire:submit.prevent="store">
    <div wire:ignore.self class="modal fade addDataManage" id="dataprocessModal" tabindex="-1"role="dialog"
        aria-labelledby="dataprocessModal" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="businessUnitModalLabel">
                        {{ $userId ? 'Edit' : 'Create' }} User
                    </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-sm-4">
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
                        @if ($role_id == '3')
                            <div class="mb-3 col-sm-4">
                                <label for="business_unit_id">Business Unit</label>
                                <select wire:model="business_unit_id" class="form-select" id="business_unit_id">
                                    <option value="">Select a business unit</option>
                                    @foreach ($businessUnits as $businessUnit)
                                        <option value="{{ $businessUnit->id }}">{{ $businessUnit->name }}</option>
                                    @endforeach
                                </select>
                                @error('business_unit_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-sm-4">
                                <label for="branch_id">Branch</label>
                                <select wire:model="branch_id" class="form-select" id="branch_id">
                                    <option value="">Select a branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- <div class="mb-3 col-sm-4">
                                <label for="branch" class="form-label">Select Branch</label>
                                <select wire:model="selectedBranch" class="form-select" id="branch" name="branch">
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $optgroupLabel => $branchOptions)
                                    <optgroup label="{{ $optgroupLabel }}">
                                        @foreach($branchOptions as $branchId => $branchName)
                                        <option value="{{ $branchId }}">{{ $branchName }}</option>
                                        @endforeach
                                    </optgroup>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="mb-3 col-sm-4">
                                <label for="project_id">Project</label>
                                <select wire:model="project_id" class="form-select" id="project_id">
                                    <option value="">Select a project</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                        <div class="mb-3 col-sm-4">
                            <label for="name">Name</label>
                            <input wire:model="name" type="text" class="form-control" id="name"
                                placeholder="Enter name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-sm-4">
                            <label for="phone">Phone</label>
                            <input wire:model="phone" type="text" class="form-control" id="phone"
                                placeholder="Enter phone number">
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-sm">
                            <label for="email">Email</label>
                            <input wire:model="email" type="email" class="form-control" id="email"
                                placeholder="Enter email address">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        @php if(!$userId){ @endphp
                        <div class="mb-3 col-sm-6">
                            <label for="password">Password</label>
                            <input wire:model="password" type="password" class="form-control" id="password"
                                placeholder="Enter password">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="confirmpassword">Confirm Password</label>
                            <input wire:model="confirmpassword" type="password" class="form-control" id="confirmpassword"
                                placeholder="Enter confirm password">
                            @error('confirmpassword')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @php } @endphp
                    </div>
                    <div class="mb-3 col-sm-12">
                        <label for="address">Address</label>
                        <textarea wire:model="address" class="form-control" id="address" placeholder="Enter address" rows="4"></textarea>
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

