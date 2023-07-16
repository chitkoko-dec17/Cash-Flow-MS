<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ItemCategory as ItemCategories;

class ItemCategoryComponent extends Component
{
    public $itemcategories, $name, $itemcategoryId ;
    public $updateItemCategory = false, $addItemCategory = false;
 
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
        $this->itemcategories = ItemCategories::all();
        return view('livewire.item-category');
    }
 
    /**
     * Open Add Post form
     * @return void
     */
    public function addItemCategory()
    {
        $this->resetFields();
        $this->addItemCategory = true;
        $this->updateItemCategory = false;
    }
     /**
      * store the Role inputted post data in the itemcategories table
      * @return void
      */
    public function storeItemCategory()
    {
        $this->validate();
        try {
            ItemCategories::create([
                'name' => $this->name,
            ]);
            session()->flash('success','Item Category Created Successfully!!');
            $this->resetFields();
            $this->render();
            $this->addItemCategory = false;
            return redirect(request()->header('Referer'));
        } catch (\Exception $ex) {
            session()->flash('error','Something goes wrong!!');
        }
    }
 
    /**
     * show existing ItemCategory data in edit ItemCategory form
     * @param mixed $id
     * @return void
     */
    public function editItemCategory($id){
        try {
            $role = ItemCategories::findOrFail($id);
            if( !$role) {
                session()->flash('error','Item Category not found');
            } else {
                $this->name = $role->name;
                $this->itemcategoryId = $role->id;
                $this->updateItemCategory = true;
                $this->addItemCategory = false;
            }
        } catch (\Exception $ex) {
            session()->flash('error','Something goes wrong!!');
        }
 
    }
 
    /**
     * update the post data
     * @return void
     */
    public function updateItemCategory()
    {
        $this->validate();
        try {
            ItemCategories::whereId($this->itemcategoryId)->update([
                'name' => $this->name,
            ]);
            session()->flash('success','Role Updated Successfully!!');
            $this->resetFields();
            $this->render();
            $this->updateItemCategory = false;
            return redirect(request()->header('Referer'));
        } catch (\Exception $ex) {
            session()->flash('success','Something goes wrong!!');
        }
    }
 
    /**
     * Cancel Add/Edit form and redirect to post listing page
     * @return void
     */
    public function cancelItemCategory()
    {
        $this->addItemCategory = false;
        $this->updateItemCategory = false;
        $this->resetFields();
    }
 
    /**
     * delete specific post data from the itemcategories table
     * @param mixed $id
     * @return void
     */
    public function deleteItemCategory($id)
    {
        try{
            ItemCategories::find($id)->delete();
            session()->flash('success',"Role Deleted Successfully!!");
        }catch(\Exception $e){
            session()->flash('error',"Something goes wrong!!");
        }
    }
}
