<?php

namespace App\Http\Livewire;

use App\Models\Branch;
use App\Models\BusinessUnit;
use Livewire\Component;
use Livewire\WithPagination;
use Auth;

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
        if(Auth::user()->user_role != "Admin"){
            abort(403);
        }
        
        $branches = Branch::with('businessUnit')
        ->search(trim($this->search))
        ->orderBy($this->sortColumnName,$this->sortDirectionBy)
        ->paginate($this->perPage);
        $businessUnits = BusinessUnit::all();

        return view('livewire.branch', compact('businessUnits','branches'));
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
        $this->dispatchBrowserEvent('closeConfirmModal');
        $this->resetValidation(); // Reset form validation errors
        $this->resetInputFields(); // Clear input fields
    }

    public function resetInputFields()
    {
        $this->branchId = '';
        $this->business_unit_id = '';
        $this->name = '';
        $this->phone = '';
        $this->address = '';
    }

    public function store()
    {
        $this->validate([
            'business_unit_id' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        Branch::updateOrCreate(['id' => $this->branchId], [
            'business_unit_id' => $this->business_unit_id,
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        ($this->branchId) ?  $this->emit('btnCreateOrUpdated','edit') : $this->emit('btnCreateOrUpdated','create');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        if(isset($branch)){
            $this->branchId = $branch->id;
            $this->business_unit_id = $branch->business_unit_id;
            $this->name = $branch->name;
            $this->phone = $branch->phone;
            $this->address = $branch->address;

            $this->openModal();
        }
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

    public function confirmDelete($deleteID,$name)
    {
        $this->confirmingDelete = true;
        $this->branchIdToDelete = $deleteID;
        $this->selectedName = $name;
        $this->dispatchBrowserEvent('openConfirmModal');
    }

    public function sortBy($columnName)
    {
        if($this->sortColumnName === $columnName){
            $this->sortDirectionBy = $this->swapSortDirection();
        } else {
            $this->sortDirectionBy = 'desc';
        }
        $this->sortColumnName = $columnName;
    }

    public function swapSortDirection()
    {
        return $this->sortDirectionBy === 'desc' ? 'asc' : 'desc';
    }

}
