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
    public $branches = [];
    public $projects = [];

    public function render()
    {
        $budgets = EstimateBudget::search(trim($this->search))
        ->orderBy($this->sortColumnName,$this->sortDirectionBy)
        ->paginate($this->perPage);

        $businessUnits = BusinessUnit::all();
        $orgs = OrgStructure::all();
        $businessUnits = BusinessUnit::all();

        return view('livewire.estimate-budget',compact('budgets','orgs','businessUnits'));
    }

    public function updatedOrgId($value){
        $this->reset(['branches', 'projects','branch_id', 'project_id','business_unit_id']);
    }

    public function updatedBusinessUnitId($value){
        if ($value) {
            // Fetch item_category based on the selected bu
            $this->branches = Branch::where('business_unit_id', $value)->get();
        } else {
            $this->branches = [];
        }
    }

    public function updatedBranchId($value){
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
        // Determine the name based on the selected budget type
        $name = '';
        if ($this->org_id == 1) {
            $businessUnit = BusinessUnit::find($this->business_unit_id);
            if ($businessUnit) {
                $name .= $businessUnit->name;
            }
            // For Business Unit type, only 'business_unit_id' is required
            $this->validate([
                'org_id' => 'required',
                'business_unit_id' => 'required',
                'total_amount' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]);
        } elseif ($this->org_id == 2) {
            $businessUnit = BusinessUnit::find($this->business_unit_id);
            $branch = Branch::find($this->branch_id);
            if ($businessUnit && $branch) {
                $name .= $businessUnit->name . ' > ' . $branch->name;
            }
            // For Branch type, 'business_unit_id' and 'branch_id' are required
            $this->validate([
                'org_id' => 'required',
                'business_unit_id' => 'required',
                'branch_id' => 'required',
                'total_amount' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]);
        } elseif ($this->org_id == 3) {
            $businessUnit = BusinessUnit::find($this->business_unit_id);
            $branch = Branch::find($this->branch_id);
            $project = Project::find($this->project_id);
            if ($businessUnit && $branch && $project) {
                $name .= $businessUnit->name . ' > ' . $branch->name . ' > ' . $project->name;
            }
            // For Project type, 'business_unit_id', 'branch_id', and 'project_id' are required
            $this->validate([
                'org_id' => 'required',
                'business_unit_id' => 'required',
                'branch_id' => 'required',
                'project_id' => 'required',
                'total_amount' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]);
        } else {
            // For unknown budget types, 'org_id' is required
            $this->validate([
                'org_id' => 'required',
                'total_amount' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]);
        }

        EstimateBudget::updateOrCreate(['id' => $this->est_budget_id], [
            'org_id' => $this->org_id,
            'business_unit_id' => $this->business_unit_id,
            'branch_id' => $this->branch_id,
            'project_id' => $this->project_id,
            'name' => $name,
            'total_amount' => $this->total_amount,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

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
