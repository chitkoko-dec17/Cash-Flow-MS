<div wire:ignore.self class="modal fade" id="businessUnitDetailModal" tabindex="-1"role="dialog"
    aria-labelledby="businessUnitDetailModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="businessUnitDetailModalLabel">Detail Information</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card custom-card">
	                <div class="card-header"><img class="img-fluid" src="{{isset($detailBusinessUnit['bu_letter_image']) ? asset('storage/'.$detailBusinessUnit['bu_letter_image']) : asset('assets/images/user-card/1.jpg') }}" alt="" /></div>
	                <div class="card-profile"><img class="rounded-circle" src="{{isset($detailBusinessUnit['bu_image']) ? asset('storage/'.$detailBusinessUnit['bu_image']) : asset('assets/images/logo/profile.png') }}" alt="" /></div>

	                <div class="text-center profile-details">
	                    <a href="#"><h4>{{ isset($detailBusinessUnit) ? $detailBusinessUnit['name'] : '' }}</h4></a>
                        <h6 class="m-0">Short Code: {{ isset($detailBusinessUnit) ? $detailBusinessUnit['shorten_code'] : '' }}</h6>
	                    <h6>{{ isset($detailBusinessUnit) ? (isset($detailBusinessUnit['manager']['name']) ? $detailBusinessUnit['manager']['name']: 'No manager assgined yet!') : '' }}</h6>
	                </div>
	                <div class="card-footer row">
	                    <div class="col-6 col-sm-6">
	                        <h6>Phone Number</h6>
	                        <h6>{{ isset($detailBusinessUnit) ? $detailBusinessUnit['phone'] : '' }}</h6>
	                    </div>
	                    <div class="col-6 col-sm-6">
	                        <h6>Address</h6>
	                        <h6>{{ isset($detailBusinessUnit) ? $detailBusinessUnit['address'] : '' }}</h6>
	                    </div>
	                </div>
	            </div>
            </div>
            <div class="modal-footer">
                <button wire:click="closeModal" class="btn btn-secondary" type="button"
                    data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
