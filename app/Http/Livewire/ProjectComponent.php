<?php

namespace App\Http\Livewire;

use App\Models\Branch;
use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectComponent extends Component
{
    use WithPagination;

    public $projectId,$branch_id,$name,$phone,$address,$brIdToDelete,$selectedName,$search,$projectIdToDelete;
    public $confirmingDelete = false;
    public $perPage = 10;
    public $sortDirectionBy='asc';
    public $sortColumnName= 'name';
    public $isOpen = false;

    public function render()
    {
        $projects = Project::with('branch')
        ->search(trim($this->search))
        ->orderBy($this->sortColumnName,$this->sortDirectionBy)
        ->paginate($this->perPage);
        $branches = Branch::all();

        return view('livewire.project', compact('projects','branches'));
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
        $this->projectId = '';
        $this->branch_id = '';
        $this->name = '';
        $this->phone = '';
        $this->address = '';
    }

    public function store()
    {
        $this->validate([
            'branch_id' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        Project::updateOrCreate(['id' => $this->projectId], [
            'branch_id' => $this->branch_id,
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        ($this->projectId) ?  $this->emit('btnCreateOrUpdated','edit') : $this->emit('btnCreateOrUpdated','create');
        $this->closeModal();
        $this->resetInputFields();
    }


    public function edit($id)
    {
        $project = Project::findOrFail($id);
        if(isset($project)){
            $this->projectId = $project->id;
            $this->branch_id = $project->branch_id;
            $this->name = $project->name;
            $this->phone = $project->phone;
            $this->address = $project->address;

            $this->openModal();
        }
    }

    public function delete()
    {
        Project::findOrFail($this->projectIdToDelete)->delete();
        $this->emit('btnCreateOrUpdated','delete');
        $this->confirmingDelete = false;
        $this->projectIdToDelete = null;
        $this->selectedName = null;
        $this->dispatchBrowserEvent('closeConfirmModal');
    }

    public function confirmDelete($deleteID,$name)
    {
        $this->confirmingDelete = true;
        $this->projectIdToDelete = $deleteID;
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
