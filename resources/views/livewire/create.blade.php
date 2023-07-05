<div class="card">
    <div class="card-body">
        <form>
            <div class="form-group mb-3">
                <label for="name">Title:</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter Title" wire:model="name">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="d-grid gap-2">
                <button wire:click.prevent="storePost()" class="btn btn-success btn-block">Save</button>
                <button wire:click.prevent="cancelPost()" class="btn btn-secondary btn-block">Cancel</button>
            </div>
        </form>
    </div>
</div>