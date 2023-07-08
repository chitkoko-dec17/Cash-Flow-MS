
	<div class="">
	    <div class="edit-profile">
	        <div class="">
				<div class="card">
					<div class="card-header pb-0">
						<h5>Edit user</h5>
					</div>
					<form class="form theme-form needs-validation" novalidate="">
						<div class="card-body">
							<div class="row">
								<div class="col">
									<div class="mb-3 row">
										<label class="col-sm-3 col-form-label">User Role</label>
										<div class="col-sm-9">
											<div class="form-group m-t-15 m-checkbox-inline mb-0 custom-radio-ml @error('role_id') is-invalid @enderror">
                                                <div class="radio radio-primary ">
                                                  <input type="radio" wire:model="role_id" value="2" checked="" id="manager">
                                                  <label class="mb-0" for="manager">Manager</label>
                                                </div>
                                                <div class="radio radio-primary">
                                                  <input type="radio" wire:model="role_id" value="3" id="user">
                                                  <label class="mb-0" for="user">User</label>
                                                </div>
                                          	</div>
                                          	@error('role_id')
                                            <div class="invalid-feedback">Please choose a role.</div>
                                        	@enderror
										</div>
									</div>
									<div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label" for="name">Username</label>
                                        <div class="col-sm-9">
                                            <input class="form-control @error('name') is-invalid @enderror" type="text" placeholder="Username" aria-describedby="inputGroupPrepend" required="" wire:model="name" id="name"/>
                                            @error('name')
                                            <div class="invalid-feedback">Please enter username.</div>
                                            @enderror
                                        </div>
									</div>
									<div class="mb-3 row">
										<label class="col-sm-3 col-form-label" for="email">Email</label>
                                        <div class="col-sm-9">
                                            <input class="form-control @error('email') is-invalid @enderror" type="email" placeholder="Email" aria-describedby="inputGroupPrepend"  wire:model="email" id="email" readonly />
                                            @error('email')
                                            <div class="invalid-feedback">Please enter email.</div>
                                            @enderror
                                        </div>
									</div>
									<div class="mb-3 row">
										<label class="col-sm-3 col-form-label" for="phone">Phone Number</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="tel" value="" placeholder="Phone Number" aria-describedby="inputGroupPrepend"  wire:model="phone" id="phone"/>
                                        </div>
									</div>
									<div class="mb-3 row">
										<label class="col-sm-3 col-form-label" for="address">Address</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" ows="5" cols="5" placeholder="Address" aria-describedby="inputGroupPrepend" wire:model="address" id="address"></textarea>
                                        </div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer text-end">
							<div class="col-sm-9 offset-sm-3">
								<button wire:click.prevent="updateUser()" class="btn btn-primary">Update</button>
								<button wire:click.prevent="cancelUser()" class="btn btn-light" />Cancel</button>
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
