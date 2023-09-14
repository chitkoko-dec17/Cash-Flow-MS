<?php

namespace App\Http\Livewire;

use App\Models\BusinessUnit;
use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Psy\Readline\Hoa\Console;

class BusinessUnitComponent extends Component
{
    use WithFileUploads;
    use WithPagination;

    //public $businessUnits;
    public $manager_id;
    public $name;
    public $bu_image;
    public $bu_letter_image;
    public $phone;
    public $address;
    public $businessUnitId;
    public $isOpen = false;
    public $imageUrl;
    public $letterImageUrl;
    public $perPage = 10;
    public $search;
    public $byManager=null;
    public $sortDirectionBy='asc';
    public $sortColumnName= 'name';
    public $confirmingDelete = false;
    public $businessUnitIdToDelete , $selectedName;
    public $detailBusinessUnit, $editManager;
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
        $managersWithBusinessUnit = BusinessUnit::pluck('manager_id')->toArray();
        $managers = User::where('role_id', $role->id)
                    ->whereNotIn('id', $managersWithBusinessUnit)
                    ->pluck('name', 'id');

         // Fetch the current business unit being edited
        $businessUnit = BusinessUnit::find($this->businessUnitId);

        // Set the $businessUnitManagerId variable
        $businessUnitManagerId = $businessUnit ? $businessUnit->manager_id : null;

        return view('livewire.business-unit',compact('businessUnits','managers','businessUnitManagerId'));
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function detailModal($businessUnit){
        //dd($businessUnit);
        $this->detailBusinessUnit = $businessUnit;

        $this->dispatchBrowserEvent('openDetailModal');
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
        $this->dispatchBrowserEvent('closeConfirmModal');
        $this->dispatchBrowserEvent('closeDetailModal');
        $this->detailBusinessUnit = null;
        $this->resetValidation(); // Reset form validation errors
        $this->resetInputFields(); // Clear input fields
    }

    private function resetInputFields()
    {
        $this->manager_id = '';
        $this->name = '';
        $this->bu_image = '';
        $this->bu_letter_image = '';
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

    public function updatedBuLetterImage()
    {
        $this->validate([
            'bu_letter_image' => 'nullable|image|mimes:jpg,png,jpeg|max:3072', // Assuming bu_letter_image is an uploaded image field
        ]);

        if ($this->bu_letter_image) {
            $this->letterImageUrl = $this->bu_letter_image->temporaryUrl(); // Generate a temporary URL for the image preview
        } else {
            $this->letterImageUrl = null;
        }
    }

    public function store()
    {
        $this->validate([
            'manager_id' => 'required',
            'name' => 'required',
            'bu_image' => 'nullable|image|mimes:jpg,png,jpeg|max:3072', // Assuming bu_image is an uploaded image field
            'bu_letter_image' => 'nullable|image|mimes:jpg,png,jpeg|max:3072',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if ($this->bu_image) {
            $imagePath = $this->bu_image->store('bu_images', 'public');
        }

        if ($this->bu_letter_image) {
            $letterImagePath = $this->bu_letter_image->store('bu_letter_images', 'public');
        }

        BusinessUnit::updateOrCreate(['id' => $this->businessUnitId], [
            'manager_id' => $this->manager_id,
            'name' => $this->name,
            'bu_image' => $this->bu_image ? $imagePath : null,
            'bu_letter_image' => $this->bu_letter_image ? $letterImagePath : null,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        ($this->businessUnitId) ?  $this->emit('buCreateOrUpdated','edit') : $this->emit('buCreateOrUpdated','create');

        // session()->flash(
        //     'message',
        //     $this->businessUnitId ? 'Business Unit updated successfully.' : 'Business Unit created successfully.'
        // );

        $this->closeModal();
        $this->resetInputFields();
        $this->imageUrl = null;
        $this->letterImageUrl = null;
    }

    public function edit($id)
    {
        $businessUnit = BusinessUnit::findOrFail($id);
        $this->businessUnitId = $id;
        $this->editManager = $businessUnit->manager->name;
        $this->manager_id = $businessUnit->manager_id;
        $this->name = $businessUnit->name;
        $this->phone = $businessUnit->phone;
        $this->address = $businessUnit->address;

        $this->openModal();
    }

    public function delete()
    {
        // $bu = BusinessUnit::findOrFail($id);
        // Storage::delete($bu->bu_image);
        BusinessUnit::find($this->businessUnitIdToDelete)->delete();
        $this->emit('buCreateOrUpdated','delete');

        $this->confirmingDelete = false;
        $this->businessUnitIdToDelete = null;
        $this->selectedName = null;
        $this->dispatchBrowserEvent('closeConfirmModal');
        //session()->flash('message', 'Business Unit deleted successfully.');
    }

    public function confirmDelete($deleteID,$name)
    {
        $this->confirmingDelete = true;
        $this->businessUnitIdToDelete = $deleteID;
        $this->selectedName = $name;
        $this->dispatchBrowserEvent('openConfirmModal');
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
