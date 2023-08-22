@extends('layouts.normalapp')

@section('title')Edit Profile
 {{ $title }}
@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
			<h3>Edit Profile</h3>
		@endslot
		<li class="breadcrumb-item active">Edit Profile</li>
	@endcomponent
	
	<div class="container-fluid">
	    <div class="edit-profile">
	        <div class="row">
	            <div class="col-xl-5">
	                <div class="card">
	                    <div class="card-header pb-0">
	                        <h4 class="card-title mb-0">Change Password</h4>
	                        <div class="card-options">
	                            <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
	                        </div>
	                    </div>
	                    <div class="card-body">
	                        <form action="{{ route('update-password') }}" method="POST">
          						@csrf
          						<div class="mb-3">
	                                <label class="form-label" for="old_password">Old Password</label>
	                                <input class="form-control" type="password" placeholder="Enter Old Password" id="old_password" name="old_password"/>
	                                @error('old_password')
						                <span class="text-danger">{{ $message }}</span>
					              	@enderror
	                            </div>

	                            <div class="mb-3">
	                                <label class="form-label" for="new_password">New Password</label>
	                                <input class="form-control" type="password" placeholder="Enter New Password" id="new_password" name="new_password"/>
	                                @error('new_password')
						                <span class="text-danger">{{ $message }}</span>
					              	@enderror
	                            </div>
	                            <div class="mb-3">
	                                <label class="form-label" for="confirmpassword">Confirm Password</label>
	                                <input class="form-control" type="password" placeholder="Enter Confirm Password" id="confirmpassword" name="confirmpassword" />
	                                @error('confirmpassword')
						                <span class="text-danger">{{ $message }}</span>
					              	@enderror
	                            </div>
	                            <div class="form-footer">
	                                <button class="btn btn-primary btn-block">Update</button>
	                            </div>
	                        </form>
	                    </div>
	                </div>
	            </div>
	            <div class="col-xl-7">
	                <form class="card" action="{{ route('profile.update') }}" method="POST">
          				@csrf
	                    <div class="card-header pb-0">
	                        <h4 class="card-title mb-0">Edit Profile</h4>
	                        <div class="card-options">
	                            <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
	                        </div>
	                    </div>
	                    <div class="card-body">
	                        <div class="row">
	                            <div class="col-sm-6 col-md-6">
	                                <div class="mb-3">
	                                    <label class="form-label">Username</label>
	                                    <input class="form-control" type="text" placeholder="Username" value="{{$user->name }}" disabled/>
	                                </div>
	                            </div>
	                            <div class="col-sm-6 col-md-6">
	                                <div class="mb-3">
	                                    <label class="form-label">Email address</label>
	                                    <input class="form-control" type="email" placeholder="Email" value="{{$user->email }}" disabled />
	                                </div>
	                            </div>
	                            <div class="col-sm-6 col-md-6">
	                                <div class="mb-3">
	                                    <label class="form-label">Role</label>
	                                    <input class="form-control" type="text" value="{{ Auth::user()->user_role }}" disabled />
	                                </div>
	                            </div>
	                            @if(Auth::user()->user_role != "Admin")
	                            <div class="col-sm-6 col-md-6">
	                                <div class="mb-3">
	                                    <label class="form-label">Business Unit</label>
	                                    <input class="form-control" type="text" value="{{ Session::get('user_business_unit_name') }}" disabled />
	                                </div>
	                            </div>
	                            @endif
	                            <div class="col-md-12">
	                                <div class="mb-3">
	                                    <label class="form-label" for="phone">Phone</label>
	                                    <input class="form-control" type="text" value="{{$user->phone }}" id="phone" name="phone"/>
	                                    @error('phone')
							                <span class="text-danger">{{ $message }}</span>
						              	@enderror
	                                </div>
	                            </div>
	                            <div class="col-md-12">
	                                <div>
	                                    <label class="form-label" for="address">Address</label>
	                                    <textarea class="form-control" rows="5" name="address">{{$user->address }}</textarea>
	                                    @error('address')
							                <span class="text-danger">{{ $message }}</span>
						              	@enderror
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="card-footer text-end">
	                        <button class="btn btn-primary" type="submit">Update Profile</button>
	                    </div>
	                </form>
	            </div>
	        </div>
	    </div>
	</div>
@endsection

@section('customJs')
    <script type="text/javascript">
        @if ($message = Session::get('success'))
            notifyToUser('Profile Info', '{{ $message }}',
                'primary');
        @endif

        @if ($message = Session::get('error'))
            notifyToUser('Profile Info',
                'Error! {{ $message }}',
                'danger');
        @endif
    </script>
@endsection