
	<div class="">
	    <div class="edit-profile">
	        <div class="">
				<div class="card">
					<div class="card-header pb-0">
						<h5>Edit item category</h5>
					</div>
					<form class="form theme-form needs-validation" novalidate="">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label" for="name">Name</label>
                                        <div class="col-sm-9">
                                            <input class="form-control @error('name') is-invalid @enderror" type="text" placeholder="Item Category" aria-describedby="inputGroupPrepend" required="" wire:model="name" id="name"/>
                                            @error('name')
                                            <div class="invalid-feedback">Please enter item category name.</div>
                                            @enderror
                                        </div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer text-end">
							<div class="col-sm-9 offset-sm-3">
								<button wire:click.prevent="updateItemCategory()" class="btn btn-primary">Update</button>
								<button wire:click.prevent="cancelItemCategory()" class="btn btn-light" />Cancel</button>
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
