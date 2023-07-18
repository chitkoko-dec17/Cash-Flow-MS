<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Models\ItemCategory;
use Livewire\WithPagination;

class ItemComponent extends Component
{
    use WithPagination;
    public $name, $itemId, $category_id ;
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
        $items = Item::search(trim($this->search))
        ->orderBy($this->sortColumnName,$this->sortDirectionBy)
        ->paginate($this->perPage);
        $itemcategories = ItemCategory::all();
        return view('livewire.item',compact('items', 'itemcategories'));
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
        $this->category_id = '';
        $this->itemId = '';
    }
 
     /**
      * store the user inputted post data in the items table
      * @return void
      */
    public function store()
    {
        $this->validate([
            'category_id' => 'required',
            'name' => 'required',
        ]);

        Item::updateOrCreate(['id' => $this->itemId], [
            'category_id' => $this->category_id,
            'name' => $this->name,
        ]);

        isset($this->itemId) ?  $this->emit('btnCreateOrUpdated','create') : $this->emit('btnCreateOrUpdated','edit');
        $this->closeModal();
        $this->resetInputFields();
    }
 
    /**
     * show existing item data in edit item form
     * @param mixed $id
     * @return void
     */
    public function edit($id){
        $item = Item::findOrFail($id);
        $this->category_id = $item->category_id;
        $this->name = $item->name;
        $this->itemId = $item->id;
        $this->openModal();  
    }
 
    /**
     * delete specific post data from the items table
     * @param mixed $id
     * @return void
     */
    public function delete($id)
    {
        Item::find($id)->delete();
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
