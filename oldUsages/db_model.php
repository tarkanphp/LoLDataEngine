<?php

class Db_model extends CI_Model
{
    
    public function SelectAll($table)
    {
        $this->db->select('*')->from($table);
        
        $query=$this->db->get();
        
        return $query;
    }
    
    public function SelectById($table,$id)
    {
        $this->db->select('*')->from($table)->where('id'.$table,$id);
        
        $query=$this->db->get();
        
        return $query;
    }
    
    public function Select($table,$fields,$where='',$or_where='',$order_by='')
    {
        $this->db->select($fields)->from($table);
        
        if(is_array($where))
        {
            $this->db->where($where);
        }
        if(is_array($or_where))
        {
            $this->db->or_where($or_where);
        }
        if(is_array($order_by))
        {
            $this->db->order_by($order_by[0], $order_by[1]);
        }
        
        $query=$this->db->get();
        
        return $query;
    }
    
    public function Add($table,$dataArray)
    {
        $this->db->insert($table, $dataArray);
        
        return $this->db->insert_id();
    }
    
    public function Update($table,$id,$dataArray)
    {
        $this->db->where('id'.$table, $id);
        $this->db->update($table, $dataArray);
        
        return $this->db->affected_rows() > 0;
    }
    
    public function UpdateByField($table,$where,$dataArray)
    {
        
        $this->db->where($where);
        $this->db->update($table, $dataArray);
        
        return $this->db->affected_rows() > 0;
    }
    
    
    public function Delete($table,$id)
    {
        $this->Update($table,$id, array($table.'_status'=>0));
        
        return $this->db->affected_rows() > 0;
    }

    //$this->data["CheckPageData"]= $this->db_model->SelectWithJoin("page","*",array("thema"=>"thema.idthema=page.idthema"),array('lang'=>"$lang",'seo_link'=>$url, 'status'=>1, 'publish'=>1))->result();
    public function SelectWithJoin($main_table,$fields,$joined_tables,$where='',$or_where='',$order_by='')
    {
        
        $this->db->select($fields);
        $this->db->from($main_table);
        if(is_array($joined_tables))
        {
            foreach ($joined_tables as $key => $value)
            {
                $this->db->join($key, $value);
            }
        }
        if(is_array($where))
        {
            $this->db->where($where);
        }
        if(is_array($or_where))
        {
            $this->db->or_where($or_where);
        }
        if(is_array($order_by))
        {
            $this->db->order_by($order_by[0], $order_by[1]);
        }
//        echo "<pre>";
//        var_dump($this->db);
//        echo "</pre>";
//        exit();
        
        $query = $this->db->get();
        
        return $query;
    }
    
}