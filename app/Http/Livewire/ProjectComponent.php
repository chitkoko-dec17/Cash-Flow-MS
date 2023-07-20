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
        $validatedData = $this->validate([
            'branch_id' => 'required',
            'name' => 'required',
            'phone' => 'nullable',
            'address' => 'nullable'
        ]);

        Project::create([
            'branch_id' => $validatedData['branch_id'],
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
        ]);

        $this->resetForm();
        $this->emit('btnCreateOrUpdated','create');
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);

        $this->projectId = $project->id;
        $this->branch_id = $project->branch_id;
        $this->name = $project->name;
        $this->phone = $project->phone;
        $this->address = $project->address;
    }

    public function update()
    {
        $validatedData = $this->validate([
            'branch_id' => 'required',
            'name' => 'required',
            'phone' => 'nullable',
            'address' => 'nullable'
        ]);

        Project::findOrFail($this->projectId)->update($validatedData);
        $this->resetForm();
        $this->emit('btnCreateOrUpdated','edit');
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

    public function resetForm()
    {
        $this->projectId = null;
        $this->branch_id = null;
        $this->name = '';
        $this->phone = '';
        $this->address = '';
    }

    public function confirmDelete($deleteID,$name)
    {
        $this->confirmingDelete = true;
        $this->projectIdToDelete = $deleteID;
        $this->selectedName = $name;
        $this->dispatchBrowserEvent('openConfirmModal');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->dispatchBrowserEvent('closeModal');
        $this->dispatchBrowserEvent('closeConfirmModal');
        $this->resetValidation(); // Reset form validation errors
        $this->resetForm(); // Clear input fields
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
