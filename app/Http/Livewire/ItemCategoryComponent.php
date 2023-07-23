<?php

namespace App\Http\Livewire;

use App\Models\BusinessUnit;
use Livewire\Component;
use App\Models\ItemCategory;
use Livewire\WithPagination;

class ItemCategoryComponent extends Component
{
    use WithPagination;
    public $name, $itemcategoryId , $business_unit_id;
    public $isOpen = false;
    public $perPage = 10;
    public $search;
    public $sortDirectionBy='asc';
    public $sortColumnName= 'name';
    public $confirmingDelete = false;
    public $itemCategoryIdToDelete , $selectedName;

    /**
     * render the post data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $itemcategories = ItemCategory::with('businessUnit')
        ->search(trim($this->search))
        ->orderBy($this->sortColumnName,$this->sortDirectionBy)
        ->paginate($this->perPage);
        $businessUnits = BusinessUnit::all();
        return view('livewire.item-category',compact('itemcategories','businessUnits'));
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

    private function resetInputFields()
    {
        $this->name = '';
        $this->itemcategoryId = '';
    }

     /**
      * store the ItemCategory inputted post data in the ItemCategory table
      * @return void
      */
    public function store()
    {
        $this->validate([
            'business_unit_id' => 'required',
            'name' => 'required',
        ]);

        ItemCategory::updateOrCreate(['id' => $this->itemcategoryId], [
            'business_unit_id' => $this->business_unit_id,
            'name' => $this->name,
        ]);

        isset($this->itemcategoryId) ?  $this->emit('btnCreateOrUpdated','edit') : $this->emit('btnCreateOrUpdated','create');
        $this->closeModal();
        $this->resetInputFields();
    }

    /**
     * show existing ItemCategory data in edit ItemCategory form
     * @param mixed $id
     * @return void
     */
    public function edit($id){
        $itemcategory = ItemCategory::findOrFail($id);
        $this->business_unit_id = $itemcategory->business_unit_id;
        $this->name = $itemcategory->name;
        $this->itemcategoryId = $itemcategory->id;
        $this->openModal();
    }

    /**
     * delete specific post data from the itemcategories table
     * @param mixed $id
     * @return void
     */
    public function delete()
    {
        ItemCategory::find($this->itemCategoryIdToDelete)->delete();
        $this->emit('btnCreateOrUpdated','delete');
        $this->confirmingDelete = false;
        $this->itemCategoryIdToDelete = null;
        $this->selectedName = null;
        $this->dispatchBrowserEvent('closeConfirmModal');
    }

    public function confirmDelete($deleteID,$name)
    {
        $this->confirmingDelete = true;
        $this->itemCategoryIdToDelete = $deleteID;
        $this->selectedName = $name;
        $this->dispatchBrowserEvent('openConfirmModal');
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
