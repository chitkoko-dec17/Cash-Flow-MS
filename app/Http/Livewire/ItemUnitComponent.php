<?php

namespace App\Http\Livewire;

use App\Models\ItemUnit;
use Livewire\Component;
use Livewire\WithPagination;
use Auth;

class ItemUnitComponent extends Component
{
    use WithPagination;
    public $name, $itemUnitID, $description;
    public $perPage = 10;
    public $search;
    public $sortDirectionBy='asc';
    public $sortColumnName= 'name';
    public $isOpen = false;

    public function render()
    {
        if(Auth::user()->user_role != "Admin"){
            abort(403);
        }

        $itemUnits = ItemUnit::search(trim($this->search))
        ->orderBy($this->sortColumnName,$this->sortDirectionBy)
        ->paginate($this->perPage);
        return view('livewire.item-unit', compact('itemUnits'));
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
        $this->name = '';
        $this->description = '';
        $this->itemUnitID = '';
    }

     /**
      * store the Role inputted post data in the roles table
      * @return void
      */
    public function store()
    {
        $this->validate([
            'name' => 'required',
        ]);

        ItemUnit::updateOrCreate(['id' => $this->itemUnitID], [
            'name' => $this->name,
            'description' => $this->description,
        ]);

        ($this->itemUnitID) ?  $this->emit('btnCreateOrUpdated','edit') : $this->emit('btnCreateOrUpdated','create');
        $this->closeModal();
        $this->resetInputFields();
    }

    /**
     * show existing Invoice Type data in edit Invoice Type form
     * @param mixed $id
     * @return void
     */
    public function edit($id){
        $itemUnits = ItemUnit::findOrFail($id);
        $this->name = $itemUnits->name;
        $this->description = $itemUnits->description;
        $this->itemUnitID = $itemUnits->id;
        $this->openModal();
    }

    /**
     * delete specific post data from the Roles table
     * @param mixed $id
     * @return void
     */
    public function delete($id)
    {
        ItemUnit::find($id)->delete();
        $this->emit('btnCreateOrUpdated','delete');
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
