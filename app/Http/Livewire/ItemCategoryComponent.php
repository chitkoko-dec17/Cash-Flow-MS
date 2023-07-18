<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ItemCategory;
use Livewire\WithPagination;

class ItemCategoryComponent extends Component
{
    use WithPagination;
    public $name, $itemcategoryId ;
    public $isOpen = false;
    public $perPage = 10;
    public $search;
    public $sortDirectionBy='asc';
    public $sortColumnName= 'name';
 
    /**
     * render the post data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $itemcategories = ItemCategory::search(trim($this->search))
        ->orderBy($this->sortColumnName,$this->sortDirectionBy)
        ->paginate($this->perPage);
        return view('livewire.item-category',compact('itemcategories'));
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
        $this->itemcategoryId = '';
    }
 
     /**
      * store the ItemCategory inputted post data in the ItemCategory table
      * @return void
      */
    public function store()
    {
        $this->validate([
            'name' => 'required',
        ]);

        ItemCategory::updateOrCreate(['id' => $this->itemcategoryId], [
            'name' => $this->name,
        ]);

        isset($this->itemcategoryId) ?  $this->emit('btnCreateOrUpdated','create') : $this->emit('btnCreateOrUpdated','edit');
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
        $this->name = $itemcategory->name;
        $this->itemcategoryId = $itemcategory->id;
        $this->openModal(); 
    }
 
    /**
     * delete specific post data from the itemcategories table
     * @param mixed $id
     * @return void
     */
    public function delete($id)
    {
        ItemCategory::find($id)->delete();
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
