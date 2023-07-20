<?php

namespace App\Http\Livewire;

use App\Models\Branch;
use App\Models\BusinessUnit;
use Livewire\Component;
use Livewire\WithPagination;

class BranchComponent extends Component
{
    use WithPagination;

    public $branchId,$business_unit_id,$name,$phone,$address,$selectedName,$search,$branchIdToDelete;
    public $confirmingDelete = false;
    public $perPage = 10;
    public $sortDirectionBy='asc';
    public $sortColumnName= 'name';
    public $isOpen = false;

    public function render()
    {
        $branches = Branch::with('businessUnit')
        ->search(trim($this->search))
        ->orderBy($this->sortColumnName,$this->sortDirectionBy)
        ->paginate($this->perPage);
        $businessUnits = BusinessUnit::all();

        return view('livewire.branch', compact('businessUnits','branches'));
    }

    public function create()
    {
        $validatedData = $this->validate([
            'business_unit_id' => 'required',
            'name' => 'required',
            'phone' => 'nullable',
            'address' => 'nullable'
        ]);

        Branch::create([
            'business_unit_id' => $validatedData['business_unit_id'],
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
        ]);

        $this->resetForm();
        $this->emit('btnCreateOrUpdated','create');
    }

    public function edit($id)
    {
        $branch = Branch::findOrFail($id);

        $this->branchId = $branch->id;
        $this->business_unit_id = $branch->business_unit_id;
        $this->name = $branch->name;
        $this->phone = $branch->phone;
        $this->address = $branch->address;
    }

    public function update()
    {
        $validatedData = $this->validate([
            'business_unit_id' => 'required',
            'name' => 'required',
            'phone' => 'nullable',
            'address' => 'nullable'
        ]);

        Branch::findOrFail($this->branchId)->update($validatedData);
        $this->resetForm();
        $this->emit('btnCreateOrUpdated','edit');
    }

    public function delete()
    {
        Branch::findOrFail($this->branchIdToDelete)->delete();
        $this->emit('btnCreateOrUpdated','delete');
        $this->confirmingDelete = false;
        $this->branchIdToDelete = null;
        $this->selectedName = null;
        $this->dispatchBrowserEvent('closeConfirmModal');
    }

    public function resetForm()
    {
        $this->branchId = null;
        $this->business_unit_id = null;
        $this->name = '';
        $this->phone = '';
        $this->address = '';
    }

    public function confirmDelete($deleteID,$name)
    {
        $this->confirmingDelete = true;
        $this->branchIdToDelete = $deleteID;
        $this->selectedName = $name;
        $this->dispatchBrowserEvent('openConfirmModal');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->dispatchBrowserEvent('closeModal');
        $this->dispatchBrowserEvent('closeConfirmModal');
        $this->resetValidation(); // Reset form validation errors
        $this->resetForm(); // Clear input fields
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
