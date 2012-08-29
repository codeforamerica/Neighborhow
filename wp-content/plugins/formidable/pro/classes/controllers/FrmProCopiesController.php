<?php
 
class FrmProCopiesController{
    function FrmProCopiesController(){
        $this->install();
        add_action('frm_after_install', array(&$this, 'install'), 20);
        add_action('frm_after_uninstall', array(&$this, 'uninstall'));
        add_action('frm_update_form', array(&$this, 'save_copied_form'), 20, 2);
        add_action('frm_create_display', array(&$this, 'save_copied_display'), 20, 2);
        add_action('frm_update_display', array(&$this, 'save_copied_display'), 20, 2);
        add_action('frm_destroy_display', array(&$this, 'destroy_copied_display'));
        add_action('frm_destroy_form', array(&$this, 'destroy_copied_form'));
        add_action('delete_blog', array(&$this, 'delete_copy_rows'), 20, 2 );
    }
    
    function install(){
        global $frmpro_copy;
        $frmpro_copy->install();
    }
    
    function uninstall(){
        global $frmpro_copy;
        $frmpro_copy->uninstall();
    }

    function save_copied_display($id, $values){
        if (isset($values['options']['copy'])){
            global $frmpro_copy;
            $created = $frmpro_copy->create(array('form_id' => $id, 'type' => 'display'));
        }
    }
        
    function save_copied_form($id, $values){
        if (isset($values['options']['copy'])){
            global $frmpro_copy;
            $created = $frmpro_copy->create(array('form_id' => $id, 'type' => 'form'));
        }
    }
    
    function destroy_copied_display($id){
        global $frmpro_copy, $blog_id;
        $copies = $frmpro_copy->getAll("blog_id='$blog_id' and form_id='$id' and type='display'");
        foreach ($copies as $copy)
            $frmpro_copy->destroy($copy->id);
    }
    
    function destroy_copied_form($id){
        global $frmpro_copy, $blog_id;
        $copies = $frmpro_copy->getAll("blog_id='$blog_id' and form_id='$id' and type='form'");
        foreach ($copies as $copy)
            $frmpro_copy->destroy($copy->id);
    }
    
    function delete_copy_rows($blog_id, $drop){
        $blog_id = (int)$blog_id;
        if(!$drop or !$blog_id)
            return;
            
        global $frmpro_copy;
        $copies = $frmpro_copy->getAll("blog_id='$blog_id'");
        foreach ($copies as $copy)
            $frmpro_copy->destroy($copy->id);
    }
        
}

?>