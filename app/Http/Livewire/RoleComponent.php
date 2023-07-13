<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Role as Roles;

class RoleComponent extends Component
{
    public $roles, $name, $roleId ;
    public $updateRole = false, $addRole = false;
 
    /**
     * delete action listener
     */
    protected $listeners = [
        'deleteRoleListner'=>'deleteRole'
    ];
 
    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'name' => 'required',
    ];
 
    /**
     * Reseting all inputted fields
     * @return void
     */
    public function resetFields(){
        $this->name = '';
    }
 
    /**
     * render the post data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $this->roles = Roles::all();
        return view('livewire.role');
    }
 
    /**
     * Open Add Post form
     * @return void
     */
    public function addRole()
    {
        $this->resetFields();
        $this->addRole = true;
        $this->updateRole = false;
    }
     /**
      * store the Role inputted post data in the roles table
      * @return void
      */
    public function storeRole()
    {
        $this->validate();
        try {
            Roles::create([
                'name' => $this->name,
            ]);
            session()->flash('success','Role Created Successfully!!');
            $this->resetFields();
            $this->render();
            $this->addRole = false;
        } catch (\Exception $ex) {
            session()->flash('error','Something goes wrong!!');
        }
    }
 
    /**
     * show existing Role data in edit Role form
     * @param mixed $id
     * @return void
     */
    public function editRole($id){
        try {
            $role = Roles::findOrFail($id);
            if( !$role) {
                session()->flash('error','Role not found');
            } else {
                $this->name = $role->name;
                $this->roleId = $role->id;
                $this->updateRole = true;
                $this->addRole = false;
            }
        } catch (\Exception $ex) {
            session()->flash('error','Something goes wrong!!');
        }
 
    }
 
    /**
     * update the post data
     * @return void
     */
    public function updateRole()
    {
        $this->validate();
        try {
            Roles::whereId($this->roleId)->update([
                'name' => $this->name,
            ]);
            session()->flash('success','Role Updated Successfully!!');
            $this->resetFields();
            $this->render();
            $this->updateRole = false;
        } catch (\Exception $ex) {
            session()->flash('success','Something goes wrong!!');
        }
    }
 
    /**
     * Cancel Add/Edit form and redirect to post listing page
     * @return void
     */
    public function cancelRole()
    {
        $this->addRole = false;
        $this->updateRole = false;
        $this->resetFields();
    }
 
    /**
     * delete specific post data from the Roles table
     * @param mixed $id
     * @return void
     */
    public function deleteRole($id)
    {
        try{
            Roles::find($id)->delete();
            session()->flash('success',"Role Deleted Successfully!!");
        }catch(\Exception $e){
            session()->flash('error',"Something goes wrong!!");
        }
    }
}
