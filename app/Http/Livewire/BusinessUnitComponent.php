<?php

namespace App\Http\Livewire;

use App\Models\BusinessUnit;
use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class BusinessUnitComponent extends Component
{
    use WithFileUploads;
    use WithPagination;

    //public $businessUnits;
    public $manager_id;
    public $name;
    public $bu_image;
    public $phone;
    public $address;
    public $businessUnitId;
    public $isOpen = false;
    public $imageUrl;
    public $perPage = 10;
    public $search;
    public $byManager=null;
    public $sortDirectionBy='asc';
    public $sortColumnName= 'name';
    /**
     * render the post data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $businessUnits = BusinessUnit::when($this->byManager, function($query){
            $query->where('manager_id', $this->byManager);
        })
        ->search(trim($this->search))
        ->orderBy($this->sortColumnName,$this->sortDirectionBy)
        ->paginate($this->perPage);

        $role = Role::where('name', 'Manager')->first();
        $managers = User::where('role_id', $role->id)->pluck('name', 'id');


        return view('livewire.business-unit',compact('businessUnits','managers'));
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->dispatchBrowserEvent('openModal');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->dispatchBrowserEvent('closeModal');
        $this->resetValidation(); // Reset form validation errors
        $this->resetInputFields(); // Clear input fields
    }

    private function resetInputFields()
    {
        $this->manager_id = '';
        $this->name = '';
        $this->bu_image = '';
        $this->phone = '';
        $this->address = '';
        $this->businessUnitId = '';
    }

    public function updatedBuImage()
    {
        $this->validate([
            'bu_image' => 'nullable|image|mimes:jpg,png,jpeg|max:3072', // Assuming bu_image is an uploaded image field
        ]);

        if ($this->bu_image) {
            $this->imageUrl = $this->bu_image->temporaryUrl(); // Generate a temporary URL for the image preview
        } else {
            $this->imageUrl = null;
        }
    }

    public function store()
    {
        $this->validate([
            'manager_id' => 'required',
            'name' => 'required',
            'bu_image' => 'nullable|image|mimes:jpg,png,jpeg|max:3072', // Assuming bu_image is an uploaded image field
            'phone' => 'required',
            'address' => 'required',
        ]);

        if ($this->bu_image) {
            $imagePath = $this->bu_image->store('bu_images', 'public');
        }

        BusinessUnit::updateOrCreate(['id' => $this->businessUnitId], [
            'manager_id' => $this->manager_id,
            'name' => $this->name,
            'bu_image' => $this->bu_image ? $imagePath : null,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        isset($this->businessUnitId) ?  $this->emit('buCreateOrUpdated','create') : $this->emit('buCreateOrUpdated','edit');

        // session()->flash(
        //     'message',
        //     $this->businessUnitId ? 'Business Unit updated successfully.' : 'Business Unit created successfully.'
        // );

        $this->closeModal();
        $this->resetInputFields();
        $this->imageUrl = null;
    }

    public function edit($id)
    {
        $businessUnit = BusinessUnit::findOrFail($id);
        $this->businessUnitId = $businessUnit->$id;
        $this->manager_id = $businessUnit->manager_id;
        $this->name = $businessUnit->name;
        $this->phone = $businessUnit->phone;
        $this->address = $businessUnit->address;

        $this->openModal();
    }

    public function delete($id)
    {
        // $bu = BusinessUnit::findOrFail($id);
        // Storage::delete($bu->bu_image);
        BusinessUnit::find($id)->delete();
        $this->emit('buCreateOrUpdated','delete');
        //session()->flash('message', 'Business Unit deleted successfully.');
    }

    public function checkPic(){
        // $book = Book::find($id);
        // if($request->hasfile('cover_image')) {
        //     $old_book_cover = $book->cover_image;

        //     if(file_exists(public_path($old_book_cover))){
        //         unlink(public_path($old_book_cover));
        //     }
        //     $file_name = time() . '.' . request()->cover_image->getClientOriginalExtension();

        //     request()->cover_image->move(public_path('images/bookcover'), $file_name);
        //     $book->cover_image = $image_path.$file_name;
        // }
    }

    public function sortBy($columnName){

        if($this->sortColumnName === $columnName){
            $this->sortDirectionBy = $this->swapSortDirection();
        } else {
            $this->sortDirectionBy = 'desc';
        }
        $this->sortColumnName = $columnName;
    }

    public function swapSortDirection()  {
        return $this->sortDirectionBy === 'desc' ? 'asc' : 'desc';
    }
}
