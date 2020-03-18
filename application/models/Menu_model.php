<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Menu_model extends CI_Model
{
    public function getSubMenu()
    {
        $this->db->select('user_sub_menu.*,user_menu.menu,user_menu.id')->join('user_menu', 'user_menu.id = user_sub_menu.menu_id');
        return $this->db->get('user_sub_menu');
    }
}