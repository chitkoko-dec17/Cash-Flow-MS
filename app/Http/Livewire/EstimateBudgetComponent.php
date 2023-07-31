<?php

namespace App\Http\Livewire;

use App\Models\Branch;
use App\Models\BusinessUnit;
use App\Models\EstimateBudget;
use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class EstimateBudgetComponent extends Component
{
    use WithPagination;
    public $name,$est_budget_id,$project_id,$branch_id,$business_unit_id,$total_amount,$start_date,$end_date;
    public $isOpen = false;
    public $perPage = 10;
    public $search;
    public $sortDirectionBy='asc';
    public $sortColumnName= 'name';
    public $confirmingDelete = false;
    public $itemIdToDelete , $selectedName;
    public $budget_type = ['Business Unit','Branch','Project'];
    public $selected_budget_type;
    public $selectedBusinessUnit,$selectedBranch,$selectedProject;
    public $branches = [];
    public $projects = [];

    public function render()
    {
        $budgets = EstimateBudget::search(trim($this->search))
        ->orderBy($this->sortColumnName,$this->sortDirectionBy)
        ->paginate($this->perPage);

        $businessUnits = BusinessUnit::all();

        return view('livewire.estimate-budget',compact('budgets','businessUnits'));
    }

    public function updatedSelectedBudgetType($value){
        $this->reset(['branches', 'projects','selectedBranch', 'selectedProject','selectedBusinessUnit']);
    }

    public function updatedSelectedBusinessUnit($value){
        $this->reset(['branches', 'projects','selectedBranch', 'selectedProject']);
        if ($value) {
            // Fetch item_category based on the selected bu
            $this->branches = Branch::where('business_unit_id', $value)->get();
        } else {
            $this->branches = [];
        }
    }

    public function updatedSelectedBranch($value){
        $this->reset(['projects', 'selectedProject']);
        if ($value) {
            // Fetch item_category based on the selected bu
            $this->projects = Project::where('branch_id', $value)->get();
        } else {
            $this->projects = [];
        }
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
        // $this->name = '';
        // $this->category_id = '';
        // $this->itemId = '';
    }

    public function updatedBusinessUnitId($value)
    {
        // if ($value) {
        //     // Fetch item_category based on the selected bu
        //     $this->itemcategories = ItemCategory::where('business_unit_id', $value)->get();
        // } else {
        //     $this->itemcategories = [];
        // }
    }

     /**
      * store the user inputted post data in the items table
      * @return void
      */
    public function store()
    {
        // $this->validate([
        //     'category_id' => 'required',
        //     'invoice_type_id' => 'required',
        //     'businessUnit_id'=> 'required',
        //     'name' => 'required',
        // ]);

        // Item::updateOrCreate(['id' => $this->itemId], [
        //     'category_id' => $this->category_id,
        //     'invoice_type_id' => $this->invoice_type_id,
        //     'name' => $this->name,
        // ]);

        // ($this->itemId) ?  $this->emit('btnCreateOrUpdated','edit') : $this->emit('btnCreateOrUpdated','create');
        // $this->closeModal();
        // $this->resetInputFields();
    }

    /**
     * show existing item data in edit item form
     * @param mixed $id
     * @return void
     */
    public function edit($id)
    {
        // $item = Item::findOrFail($id);
        // if (isset($item)) {
        //     $this->category_id = $item->category_id;
        //     $this->invoice_type_id = $item->invoice_type_id;
        //     $this->businessUnit_id = $item->category->businessUnit->id;
        //     $this->name = $item->name;
        //     $this->itemId = $item->id;
        //     $this->updatedBusinessUnitId($item->category->businessUnit->id);
        //     $this->openModal();
        // }
    }

    /**
     * delete specific post data from the items table
     * @param mixed $id
     * @return void
     */
    public function delete()
    {
        // Item::find($this->itemIdToDelete)->delete();
        // $this->emit('btnCreateOrUpdated','delete');
        // $this->confirmingDelete = false;
        // $this->itemIdToDelete = null;
        // $this->selectedName = null;
        // $this->dispatchBrowserEvent('closeConfirmModal');
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
