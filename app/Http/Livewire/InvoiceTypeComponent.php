<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\InvoiceType;
use App\Models\ItemCategory;
use Livewire\WithPagination;

class InvoiceTypeComponent extends Component
{
    use WithPagination;
    public $name, $invtypeId;
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
        $invoicetypes = InvoiceType::search(trim($this->search))
        ->orderBy($this->sortColumnName,$this->sortDirectionBy)
        ->paginate($this->perPage);
        return view('livewire.invoice-type',compact('invoicetypes'));
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
        $this->invtypeId = '';
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

        InvoiceType::updateOrCreate(['id' => $this->invtypeId], [
            'name' => $this->name,
        ]);

        ($this->invtypeId) ?  $this->emit('btnCreateOrUpdated','create') : $this->emit('btnCreateOrUpdated','edit');
        $this->closeModal();
        $this->resetInputFields();
    }

    /**
     * show existing Invoice Type data in edit Invoice Type form
     * @param mixed $id
     * @return void
     */
    public function edit($id){
        $invtype = InvoiceType::findOrFail($id);
        $this->name = $invtype->name;
        $this->invtypeId = $invtype->id;
        $this->openModal();
    }

    /**
     * delete specific post data from the Roles table
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
