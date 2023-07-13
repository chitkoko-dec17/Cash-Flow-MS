
	<div class="">
	    <div class="edit-profile">
	        <div class="">
				<div class="card">
					<div class="card-header pb-0">
						<h5>Create new item</h5>
					</div>
					<form class="form theme-form needs-validation" novalidate="">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label" for="category_id">Category</label>
                                        <div class="col-sm-9">
                                        	<select class="form-select @error('category_id') is-invalid @enderror" wire:model="category_id" id="category_id">
                                        		<option value="">Select Category</option>
                                        		@foreach ($itemcategories as $icate)
                                        			<option value="{{$icate->id}}">{{$icate->name}}</option>
                                        		@endforeach
                                        	</select>
                                        	@error('category_id')
                                            <div class="invalid-feedback">Please select category.</div>
                                            @enderror
                                    	</div>
									</div>
									<div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label" for="name">Item</label>
                                        <div class="col-sm-9">
                                            <input class="form-control @error('name') is-invalid @enderror" type="text" placeholder="Item Name" aria-describedby="inputGroupPrepend" required="" wire:model="name" id="name"/>
                                            @error('name')
                                            <div class="invalid-feedback">Please enter item name.</div>
                                            @enderror
                                        </div>
									</div>
									
								</div>
							</div>
						</div>
						<div class="card-footer text-end">
							<div class="col-sm-9 offset-sm-3">
								<button wire:click.prevent="storeItem()" class="btn btn-primary" type="submit">Create</button>
								<button wire:click.prevent="cancelItem()" class="btn btn-light btn-block">Cancel</button>
							</div>
						</div>
					</form>
				</div>
	        </div>
	    </div>
	</div>


	@push('scripts')
	<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
	@endpush

