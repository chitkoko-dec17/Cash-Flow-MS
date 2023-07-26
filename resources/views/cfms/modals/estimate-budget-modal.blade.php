<form wire:submit.prevent="store">
    <div wire:ignore.self class="modal fade addDataManage" id="dataprocessModal" tabindex="-1"role="dialog"
        aria-labelledby="dataprocessModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="businessUnitModalLabel">
                        {{ $est_budget_id ? 'Edit' : 'Create' }} Budget
                    </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="selected_budget_type">Set Budget for</label>
                        <select wire:model="selected_budget_type" class="form-select" id="selected_budget_type">
                            <option value="">Select budget type</option>
                            @foreach ($budget_type as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('budget_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    @if ($selected_budget_type == 'Business Unit')
                        <div class="form-group">
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
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input wire:model="start_date" type="date" class="form-control" id="start_date"
                                placeholder="Enter start date">
                            @error('start_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input wire:model="end_date" type="date" class="form-control" id="end_date"
                                placeholder="Enter end date">
                            @error('end_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="total_amount">Estimated Budget Amount</label>
                            <input wire:model="total_amount" type="number" class="form-control" id="total_amount"
                                placeholder="Enter total amount">
                            @error('total_amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                    @if ($selected_budget_type == 'Branch')

                    @endif
                    @if ($selected_budget_type == 'Project')

                    @endif
                    {{-- <div class="form-group">
                        <label for="businessUnit_id">Business Unit</label>
                        <select wire:model="businessUnit_id" class="form-select" id="businessUnit_id">
                            <option value="">Select a business unit</option>
                            @foreach ($businessUnits as $businessUnit)
                                <option value="{{ $businessUnit->id }}">{{ $businessUnit->name }}</option>
                            @endforeach
                        </select>
                        @error('businessUnit_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select wire:model="category_id" class="form-select" id="category_id">
                            <option value="">Select a item category</option>
                            @foreach ($itemcategories as $itemcategory)
                                <option value="{{ $itemcategory->id }}">{{ $itemcategory->name }}</option>
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
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button wire:click="closeModal" class="btn btn-secondary" type="button"
                        data-bs-dismiss="modal">Close</button>
                    <button  class="btn btn-primary"
                        type="submit">{{ $est_budget_id ? 'Save Changes' : 'Create' }}</button>
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
