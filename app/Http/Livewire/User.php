<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User as Users;
use Illuminate\Support\Facades\Hash;

class User extends Component
{

    public $users, $name, $userId, $role_id, $email, $password, $phone, $address ;
    public $updateUser = false, $addUser = false;
 
    /**
     * delete action listener
     */
    protected $listeners = [
        'deleteUserListner'=>'deleteUser'
    ];
 
    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'role_id' => 'required',
        'name' => 'required',
        'email' => 'required',
        'password' => 'required',
    ];
 
    /**
     * Reseting all inputted fields
     * @return void
     */
    public function resetFields(){
        $this->role_id = '';
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->phone = '';
        $this->address = '';
    }
 
    /**
     * render the post data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $this->users = Users::all();
        return view('livewire.user');
    }
 
    /**
     * Open Add Post form
     * @return void
     */
    public function addUser()
    {
        $this->resetFields();
        $this->addUser = true;
        $this->updateUser = false;
    }
     /**
      * store the user inputted post data in the users table
      * @return void
      */
    public function storeUser()
    {
        $this->validate();
        try {
            Users::create([
                'role_id' => $this->role_id,
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'phone' => $this->phone,
                'address' => $this->address,
            ]);
            session()->flash('success','User Created Successfully!!');
            $this->resetFields();
            $this->addUser = false;
        } catch (\Exception $ex) {
            session()->flash('error','Something goes wrong!!');
        }
    }
 
    /**
     * show existing user data in edit user form
     * @param mixed $id
     * @return void
     */
    public function editUser($id){
        try {
            $user = Users::findOrFail($id);
            if( !$user) {
                session()->flash('error','User not found');
            } else {
                $this->role_id = $user->role_id;
                $this->name = $user->name;
                $this->email = $user->email;
                $this->phone = $user->phone;
                $this->address = $user->address;
                $this->userId = $user->id;
                $this->updateUser = true;
                $this->addUser = false;
            }
        } catch (\Exception $ex) {
            session()->flash('error','Something goes wrong!!');
        }
 
    }
 
    /**
     * update the post data
     * @return void
     */
    public function updateUser()
    {
        $validatedData = $this->validate([
            'role_id' => 'required',
            'name' => 'required',
            'email' => 'required',
        ]);

        try {
            Users::whereId($this->userId)->update([
                'role_id' => $this->role_id,
                'name' => $this->name,
                'phone' => $this->phone,
                'address' => $this->address,
            ]);
            session()->flash('success','User Updated Successfully!!');
            $this->resetFields();
            $this->updateUser = false;
        } catch (\Exception $ex) {
            session()->flash('success','Something goes wrong!!');
        }
    }
 
    /**
     * Cancel Add/Edit form and redirect to post listing page
     * @return void
     */
    public function cancelUser()
    {
        $this->addUser = false;
        $this->updateUser = false;
        $this->resetFields();
    }
 
    /**
     * delete specific post data from the users table
     * @param mixed $id
     * @return void
     */
    public function deleteUser($id)
    {
        try{
            Users::find($id)->delete();
            session()->flash('success',"User Deleted Successfully!!");
        }catch(\Exception $e){
            session()->flash('error',"Something goes wrong!!");
        }
    }
}
