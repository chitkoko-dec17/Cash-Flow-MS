<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Role;
use Livewire\WithPagination;

class RoleComponent extends Component
{
    use WithPagination;
    public $name, $roleId ;
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
        $roles = Role::search(trim($this->search))
        ->orderBy($this->sortColumnName,$this->sortDirectionBy)
        ->paginate($this->perPage);

        return view('livewire.role',compact('roles'));
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
        $this->roleId = '';
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

        Role::updateOrCreate(['id' => $this->roleId], [
            'name' => $this->name,
        ]);

        isset($this->roleId) ?  $this->emit('btnCreateOrUpdated','create') : $this->emit('btnCreateOrUpdated','edit');
        $this->closeModal();
        $this->resetInputFields();
    }

    /**
     * show existing Role data in edit Role form
     * @param mixed $id
     * @return void
     */
    public function edit($id){
        $role = Role::findOrFail($id);
        $this->name = $role->name;
        $this->roleId = $role->id;
        $this->openModal();
    }

    /**
     * delete specific post data from the Role table
     * @param mixed $id
     * @return void
     */
    public function delete($id)
    {
        Role::find($id)->delete();
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
