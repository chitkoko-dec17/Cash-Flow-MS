<?php

namespace App\Http\Livewire;

use App\Models\Branch;
use App\Models\BusinessUnit;
use App\Models\EstimateBudget;
use App\Models\OrgStructure;
use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class EstimateBudgetComponent extends Component
{
    use WithPagination;
    public $name,$est_budget_id,$org_id,$project_id,$branch_id,$business_unit_id,$total_amount,$start_date,$end_date;
    public $isOpen = false;
    public $perPage = 10;
    public $search;
    public $sortDirectionBy='asc';
    public $sortColumnName= 'name';
    public $confirmingDelete = false;
    public $idToDelete , $selectedName;
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
        $orgs = OrgStructure::all();

        return view('livewire.estimate-budget',compact('budgets','businessUnits','orgs'));
    }

    public function updatedOrgId($value){
        $this->reset(['branches', 'projects','branch_id', 'project_id','business_unit_id']);
    }

    public function updatedBusinessUnitId($value){
        $this->reset(['branches', 'projects','branch_id', 'project_id']);
        if ($value) {
            // Fetch item_category based on the selected bu
            $this->branches = Branch::where('business_unit_id', $value)->get();
        } else {
            $this->branches = [];
        }
    }

    public function updatedBranchId($value){
        $this->reset(['projects', 'project_id']);
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
        $this->name = '';
        $this->est_budget_id = '';
        $this->org_id = '';
        $this->project_id = '';
        $this->branch_id = '';
        $this->business_unit_id = '';
        $this->total_amount = '';
        $this->start_date = '';
        $this->end_date = '';
    }

     /**
      * store the user inputted post data in the items table
      * @return void
      */
    public function store()
    {
        $this->validate([
            'org_id' => 'required',
            'business_unit_id' => 'required',
            'branch_id'=> 'required',
            'project_id'=> 'required',
            'name' => 'required',
            'total_amount' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        // EstimateBudget::updateOrCreate(['id' => $this->est_budget_id], [
        //     'org_id' => $this->org_id,
        //     'business_unit_id' => $this->business_unit_id,
        //     'branch_id' => $this->branch_id,
        //     'project_id' => $this->project_id,
        //     'name' => $this->name,
        //     'total_amount' => $this->total_amount,
        //     'start_date' => $this->start_date,
        //     'end_date' => $this->end_date,
        // ]);

        var_dump([
            'org_id' => $this->org_id,
            'business_unit_id' => $this->business_unit_id,
            'branch_id' => $this->branch_id,
            'project_id' => $this->project_id,
            'name' => $this->name,
            'total_amount' => $this->total_amount,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);
        exit();

        ($this->est_budget_id) ?  $this->emit('btnCreateOrUpdated','edit') : $this->emit('btnCreateOrUpdated','create');
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
        $est_budget = EstimateBudget::findOrFail($id);
        if (isset($est_budget)) {
            $this->org_id = $est_budget->org_id;
            $this->business_unit_id = $est_budget->business_unit_id;
            $this->branch_id = $est_budget->branch_id;
            $this->project_id = $est_budget->project_id;
            $this->name = $est_budget->name;
            $this->total_amount = $est_budget->total_amount;
            $this->start_date = $est_budget->start_date;
            $this->end_date = $est_budget->end_date;
            $this->est_budget_id = $est_budget->id;
            $this->updatedOrgId($est_budget->org_id);
            $this->updatedBusinessUnitId($est_budget->business_unit_id);
            $this->updatedBranchId($est_budget->branch_id);
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
        EstimateBudget::find($this->idToDelete)->delete();
        $this->emit('btnCreateOrUpdated','delete');
        $this->confirmingDelete = false;
        $this->idToDelete = null;
        $this->selectedName = null;
        $this->dispatchBrowserEvent('closeConfirmModal');
    }

    public function confirmDelete($deleteID,$name)
    {
        $this->confirmingDelete = true;
        $this->idToDelete = $deleteID;
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
