<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Item as Items;
use App\Models\ItemCategory as ItemCategories;

class ItemComponent extends Component
{
    public $itemcategories, $items, $name, $itemId, $category_id ;
    public $updateItem = false, $addItem = false;
 
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
        'category_id' => 'required',
        'name' => 'required',
    ];
 
    /**
     * Reseting all inputted fields
     * @return void
     */
    public function resetFields(){
        $this->category_id = '';
        $this->name = '';
    }
 
    /**
     * render the post data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $this->items = Items::all();
        $this->itemcategories = ItemCategories::all();
        return view('livewire.item');
    }
 
    /**
     * Open Add Post form
     * @return void
     */
    public function addItem()
    {
        $this->resetFields();
        $this->addItem = true;
        $this->updateItem = false;
    }
     /**
      * store the user inputted post data in the items table
      * @return void
      */
    public function storeItem()
    {
        $this->validate();
        try {
            Items::create([
                'category_id' => $this->category_id,
                'name' => $this->name,
            ]);
            session()->flash('success','Item Created Successfully!!');
            $this->resetFields();
            $this->render();
            $this->addItem = false;
        } catch (\Exception $ex) {
            session()->flash('error','Something goes wrong!!');
        }
    }
 
    /**
     * show existing item data in edit item form
     * @param mixed $id
     * @return void
     */
    public function editItem($id){
        try {
            $item = Items::findOrFail($id);
            if( !$item) {
                session()->flash('error','Item not found');
            } else {
                $this->category_id = $item->category_id;
                $this->name = $item->name;
                $this->itemId = $item->id;
                $this->updateItem = true;
                $this->addItem = false;
            }
        } catch (\Exception $ex) {
            session()->flash('error','Something goes wrong!!');
        }
 
    }
 
    /**
     * update the post data
     * @return void
     */
    public function updateItem()
    {
        $this->validate();

        try {
            Items::whereId($this->itemId)->update([
                'category_id' => $this->category_id,
                'name' => $this->name,
            ]);
            session()->flash('success','Item Updated Successfully!!');
            $this->resetFields();
            $this->render();
            $this->updateItem = false;
        } catch (\Exception $ex) {
            session()->flash('success','Something goes wrong!!');
        }
    }
 
    /**
     * Cancel Add/Edit form and redirect to post listing page
     * @return void
     */
    public function cancelItem()
    {
        $this->addItem = false;
        $this->updateItem = false;
        $this->resetFields();
    }
 
    /**
     * delete specific post data from the items table
     * @param mixed $id
     * @return void
     */
    public function deleteItem($id)
    {
        try{
            Items::find($id)->delete();
            session()->flash('success',"Item Deleted Successfully!!");
        }catch(\Exception $e){
            session()->flash('error',"Something goes wrong!!");
        }
    }
}
