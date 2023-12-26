<?php

namespace App\Http\Livewire;

use App\Models\BusinessUnit;
use App\Models\InvoiceType;
use Livewire\Component;
use App\Models\Item;
use App\Models\ItemCategory;
use Livewire\WithPagination;
use Auth;

class ItemComponent extends Component
{
    use WithPagination;
    public $name, $itemId, $category_id,$invoice_type_id,$businessUnit_id ;
    public $isOpen = false;
    public $perPage = 10;
    public $search;
    public $sortDirectionBy='asc';
    public $sortColumnName= 'name';
    public $confirmingDelete = false;
    public $itemIdToDelete , $selectedName;
    public $itemcategories = [];

    /**
     * render the post data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        if(Auth::user()->user_role != "Admin"){
            abort(403);
        }
        
        $items = Item::search(trim($this->search))
        ->orderBy($this->sortColumnName,$this->sortDirectionBy)
        ->paginate($this->perPage);
        $invoiceTypes = InvoiceType::all();
        $businessUnits = BusinessUnit::all();

        return view('livewire.item',compact('items','invoiceTypes','businessUnits'));
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
        $this->category_id = '';
        $this->itemId = '';
    }

    public function updatedBusinessUnitId($value)
    {
        if ($value) {
            // Fetch item_category based on the selected bu
            $this->itemcategories = ItemCategory::where('business_unit_id', $value)->get();
        } else {
            $this->itemcategories = [];
        }
    }

     /**
      * store the user inputted post data in the items table
      * @return void
      */
    public function store()
    {
        $this->validate([
            'category_id' => 'required',
            'invoice_type_id' => 'required',
            'businessUnit_id'=> 'required',
            'name' => 'required',
        ]);

        Item::updateOrCreate(['id' => $this->itemId], [
            'category_id' => $this->category_id,
            'invoice_type_id' => $this->invoice_type_id,
            'name' => $this->name,
        ]);

        ($this->itemId) ?  $this->emit('btnCreateOrUpdated','edit') : $this->emit('btnCreateOrUpdated','create');
        $this->closeModal();
        $this->resetInputFields();
    }

    /**
     * show existing item data in edit item form
     * @param mixed $id
     * @return void
     */
    public function edit($id)
    {
        $item = Item::findOrFail($id);
        if (isset($item)) {
            $this->category_id = $item->category_id;
            $this->invoice_type_id = $item->invoice_type_id;
            $this->businessUnit_id = $item->category->businessUnit->id;
            $this->name = $item->name;
            $this->itemId = $item->id;
            $this->updatedBusinessUnitId($item->category->businessUnit->id);
            $this->openModal();
        }
    }

    /**
     * delete specific post data from the items table
     * @param mixed $id
     * @return void
     */
    public function delete()
    {
        Item::find($this->itemIdToDelete)->delete();
        $this->emit('btnCreateOrUpdated','delete');
        $this->confirmingDelete = false;
        $this->itemIdToDelete = null;
        $this->selectedName = null;
        $this->dispatchBrowserEvent('closeConfirmModal');
    }

    public function confirmDelete($deleteID,$name)
    {
        $this->confirmingDelete = true;
        $this->itemIdToDelete = $deleteID;
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
