<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\InvoiceType as InvoiceTypes;

class InvoiceTypeComponent extends Component
{
    public $invoicetypes, $name, $invtypeId ;
    public $updateInvType = false, $addInvType = false;
 
    /**
     * delete action listener
     */
    protected $listeners = [
        'deleteInvTypeListner'=>'deleteInvType'
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
        $this->invoicetypes = InvoiceTypes::all();
        return view('livewire.invoice-type');
    }
 
    /**
     * Open Add Post form
     * @return void
     */
    public function addInvType()
    {
        $this->resetFields();
        $this->addInvType = true;
        $this->updateInvType = false;
    }
     /**
      * store the Role inputted post data in the roles table
      * @return void
      */
    public function storeInvType()
    {
        $this->validate();
        try {
            InvoiceTypes::create([
                'name' => $this->name,
            ]);
            session()->flash('success','Invoice Type Created Successfully!!');
            $this->resetFields();
            $this->render();
            $this->addInvType = false;
            return redirect(request()->header('Referer'));
        } catch (\Exception $ex) {
            session()->flash('error','Something goes wrong!!');
        }
    }
 
    /**
     * show existing Invoice Type data in edit Invoice Type form
     * @param mixed $id
     * @return void
     */
    public function editInvType($id){
        try {
            $invtype = InvoiceTypes::findOrFail($id);
            if( !$invtype) {
                session()->flash('error','Invoice Type not found');
            } else {
                $this->name = $invtype->name;
                $this->invtypeId = $invtype->id;
                $this->addInvType = false;
                $this->updateInvType = true;
            }
        } catch (\Exception $ex) {
            session()->flash('error','Something goes wrong!!');
        }
 
    }
 
    /**
     * update the post data
     * @return void
     */
    public function updateInvType()
    {
        $this->validate();
        try {
            InvoiceTypes::whereId($this->invtypeId)->update([
                'name' => $this->name,
            ]);
            session()->flash('success','Invoice Type Updated Successfully!!');
            $this->resetFields();
            $this->render();
            $this->updateInvType = false;
            return redirect(request()->header('Referer'));
        } catch (\Exception $ex) {
            session()->flash('success','Something goes wrong!!');
        }
    }
 
    /**
     * Cancel Add/Edit form and redirect to post listing page
     * @return void
     */
    public function cancelInvType()
    {
        $this->addInvType = false;
        $this->updateInvType = false;
        $this->resetFields();
    }
 
    /**
     * delete specific post data from the Roles table
     * @param mixed $id
     * @return void
     */
    public function deleteInvType($id)
    {
        try{
            Roles::find($id)->delete();
            session()->flash('success',"Invoice Type Deleted Successfully!!");
        }catch(\Exception $e){
            session()->flash('error',"Something goes wrong!!");
        }
    }
}
