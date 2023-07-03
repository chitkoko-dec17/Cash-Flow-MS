@extends('cfms.layouts.admin.master')

@section('title')Edit Profile
 {{ $title }}
@endsection

@push('css')
@endpush

@section('content')
	<div class="container-fluid">
	    <div class="edit-profile">
	        <div class="row">
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
											<div class="form-group m-t-15 m-checkbox-inline mb-0 custom-radio-ml">
                                                <div class="radio radio-primary">
                                                  <input id="radioinline2" type="radio" name="radio1" value="option1">
                                                  <label class="mb-0" for="radioinline2">Manager</label>
                                                </div>
                                                <div class="radio radio-primary">
                                                  <input id="radioinline3" type="radio" name="radio1" value="option1" checked="">
                                                  <label class="mb-0" for="radioinline3">User</label>
                                                </div>
                                              </div>
										</div>
									</div>
									<div class="mb-3 row">
                                        <label class="col-sm-3 col-form-label" for="validationCustomUsername">Username</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" id="validationCustomUsername" type="text" placeholder="Username" aria-describedby="inputGroupPrepend" required="" />
                                            <div class="invalid-feedback">Please choose a username.</div>
                                        </div>
									</div>
									<div class="mb-3 row">
										<label class="col-sm-3 col-form-label" for="validationCustomPassword">Password</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" id="validationCustomPassword" type="password" placeholder="Password" aria-describedby="inputGroupPrepend" required="" />
                                            <div class="invalid-feedback">Please choose a password.</div>
                                        </div>
									</div>
									<div class="mb-3 row">
										<label class="col-sm-3 col-form-label" for="validationCustomEmail">Email</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" id="validationCustomEmail" type="email" placeholder="Email" aria-describedby="inputGroupPrepend"  />
                                            <div class="invalid-feedback">Please enter email.</div>
                                        </div>
									</div>
									<div class="mb-3 row">
										<label class="col-sm-3 col-form-label" for="validationCustomPhoneNumber">Phone Number</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" id="validationCustomPhoneNumber" type="tel" value="" placeholder="Phone Number" aria-describedby="inputGroupPrepend"  />
                                            <div class="invalid-feedback">Please enter Phone Number.</div>
                                        </div>
									</div>
									<div class="mb-3 row">
										<label class="col-sm-3 col-form-label" for="validationCustomPhoneNumber">Address</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="validationCustomPhoneNumber" ows="5" cols="5" placeholder="Address" aria-describedby="inputGroupPrepend" ></textarea>
                                            <div class="invalid-feedback">Please enter Address.</div>
                                        </div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer text-end">
							<div class="col-sm-9 offset-sm-3">
								<button class="btn btn-primary" type="submit">Update</button>
								<input class="btn btn-light" type="reset" value="Cancel" />
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

@endsection
