<?php

namespace App\Http\Livewire;

use App\Models\BusinessUnit;
use Livewire\Component;
use Livewire\WithFileUploads;

class BusinessUnitCrud extends Component
{
    use WithFileUploads;

    public $businessUnits;
    public $manager_id;
    public $name;
    public $bu_image;
    public $phone;
    public $address;
    public $businessUnitId;
    public $isOpen = false;
    public $imageUrl;

    /**
     * render the post data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $this->businessUnits = BusinessUnit::all();
        return view('livewire.business-unit-crud');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
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
            'bu_image' => 'nullable|image|max:1024', // Assuming bu_image is an uploaded image field
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
            'bu_image' => 'nullable|image|max:1024', // Assuming bu_image is an uploaded image field
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

        session()->flash(
            'message',
            $this->businessUnitId ? 'Business Unit updated successfully.' : 'Business Unit created successfully.'
        );

        $this->closeModal();
        $this->resetInputFields();
        $this->imageUrl = null;
    }

    public function edit($id)
    {
        $businessUnit = BusinessUnit::findOrFail($id);
        $this->businessUnitId = $id;
        $this->manager_id = $businessUnit->manager_id;
        $this->name = $businessUnit->name;
        $this->phone = $businessUnit->phone;
        $this->address = $businessUnit->address;

        $this->openModal();
    }

    public function delete($id)
    {
        BusinessUnit::find($id)->delete();
        session()->flash('message', 'Business Unit deleted successfully.');
    }

    public function getBuImageUrlAttribute()
    {
        if ($this->bu_image) {
            return asset('storage/bu_images/' . $this->bu_image);
        }

        return null;
    }
}
