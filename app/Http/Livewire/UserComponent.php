<?php

namespace App\Http\Livewire;

use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\BusinessUnit;
use App\Models\Project;
use App\Models\ProjectUser;
use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Livewire\WithPagination;

class UserComponent extends Component
{
    use WithPagination;
    public $name, $userId, $role_id, $email, $password, $confirmpassword, $phone, $address;
    public $isOpen = false;
    public $perPage = 10;
    public $search;
    public $sortDirectionBy='asc';
    public $sortColumnName= 'name';
    public $confirmingDelete = false;
    public $userIdToDelete , $selectedName;
    public $role;
    public $branches = [];
    public $projects = [];
    public $businessUnits;
    public $selectedBranch,$selectedProject;

    public function updatedRoleId($value)
    {
        // Clear branch and project selections when the role is changed
        $this->reset(['branches', 'projects','selectedBranch', 'selectedProject']);

        // Fetch branches and projects based on the selected role
        if ($value === '3') {
            $this->businessUnits = BusinessUnit::all();

            foreach ($this->businessUnits as $businessUnit) {
                $optgroupLabel = $businessUnit->name;
                $branchOptions = Branch::where('business_unit_id', $businessUnit->id)->pluck('name', 'id')->toArray();
                $this->branches[$optgroupLabel] = $branchOptions;
            }

            // $this->branches = Branch::all();
            $this->projects = [];
        } else {
            $this->branches = [];
            $this->projects = [];
        }
    }

    public function updatedSelectedBranch($value)
    {
        if ($value) {
            // Fetch projects based on the selected branch
            $this->projects = Project::where('branch_id', $value)->get();
        } else {
            $this->projects = [];
        }
    }

    /**
     * render the post data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $users = User::search(trim($this->search))
        ->orderBy($this->sortColumnName,$this->sortDirectionBy)
        ->paginate($this->perPage);
        $roles = Role::all();
        return view('livewire.user',compact('users', 'roles'));
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
        $this->role_id = '';
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->confirmpassword = '';
        $this->phone = '';
        $this->address = '';
        $this->userId = '';
    }

     /**
      * store the user inputted post data in the users table
      * @return void
      */
    public function store()
    {
        $toSave = null;
        if($this->userId){
            $this->validate([
                'role_id' => 'required',
                'name' => 'required',
                'email' => 'required|email',
            ]);

            $toSave = User::find($this->userId);
        }else{
            $this->validate([
                'role_id' => 'required',
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6',
                'confirmpassword' => 'required|same:password',
            ]);

            $toSave = new User();
            $toSave->password = Hash::make($this->password);
        }

        $toSave->role_id = $this->role_id;
        $toSave->name = $this->name;
        $toSave->email = $this->email;
        $toSave->phone = $this->phone;
        $toSave->address = $this->address;
        $toSave->save();

        // Handle user's association with the branch
        if ($this->role_id === '3' && !empty($this->selectedBranch)) {
            $branch = Branch::find($this->selectedBranch);
            if ($branch) {
                // Create or update the user's association with the branch in the branch_user table
                BranchUser::updateOrCreate(
                    ['user_id' => $toSave->id],
                    ['branch_id' => $this->selectedBranch]
                );
            }
        }

        // Handle user's association with the project
        if (!empty($this->selectedProject)) {
            $project = Project::find($this->selectedProject);
            if ($project) {
                // Create or update the user's association with the project in the project_user table
                ProjectUser::updateOrCreate(
                    ['user_id' => $toSave->id],
                    ['project_id' => $this->selectedProject]
                );
            }
        }
        // elseif ($toSave->projectUser) {
        //     // Remove the project association if the project select is removed during the update
        //     $toSave->projectUser->delete();
        // }

        isset($this->userId) ?  $this->emit('btnCreateOrUpdated','edit') : $this->emit('btnCreateOrUpdated','create');
        $this->closeModal();
        $this->resetInputFields();
    }

    /**
     * show existing user data in edit user form
     * @param mixed $id
     * @return void
     */
    public function edit($id){
        $user = User::findOrFail($id);
        $branch = BranchUser::findOrFail($user->id);
        $project = ProjectUser::findOrFail($user->id);
        $this->role_id = $user->role_id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->userId = $id;
        // $this->selectedBranch = $branch->user_id;
        // $this->selectedProject = $project->user_id;
        $this->openModal();
    }

    /**
     * delete specific post data from the users table
     * @param mixed $id
     * @return void
     */
    public function delete()
    {
        User::find($this->userIdToDelete)->delete();
        BranchUser::find($this->userIdToDelete)->delete();
        ProjectUser::find($this->userIdToDelete)->delete();
        $this->emit('btnCreateOrUpdated','delete');
        $this->confirmingDelete = false;
        $this->userIdToDelete = null;
        $this->selectedName = null;
        $this->dispatchBrowserEvent('closeConfirmModal');
    }

    public function confirmDelete($deleteID,$name)
    {
        $this->confirmingDelete = true;
        $this->userIdToDelete = $deleteID;
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
