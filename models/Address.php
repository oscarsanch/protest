<?php

class Address extends Model
{
    public function __construct(){
        $this->conn = parent::__construct();
    }

    public function getRegion(){
        $sql = 'SELECT ter_id, ter_pid, ter_name FROM t_koatuu_tree WHERE ter_pid IS NULL ORDER BY ter_id';
        foreach ($this->conn->query($sql) as $row) {
            $return_arr[] = [$row['ter_id']=> $row['ter_name']];
        }
        return $return_arr;
    }

    public function getDistrict($region){
        $sql = "SELECT ter_id, ter_pid, ter_name FROM t_koatuu_tree WHERE ter_level in (1,2) and ter_pid = '$region' ORDER BY ter_id";
        foreach ($this->conn->query($sql) as $row) {
            $return_arr[] = [$row['ter_id']=> $row['ter_name']];
        }
        return $return_arr;
    }

    public function getCity($district){
        $sql = "select ter_id, ter_pid, ter_name from t_koatuu_tree where ter_name like '%район%' and ter_pid  in (
                      select ter_id from t_koatuu_tree where ter_type_id=1 and ter_id ='$district')
                union
                select ter_id, ter_pid, ter_name from t_koatuu_tree where ter_pid  in (
                      select ter_id from t_koatuu_tree where ter_type_id!=1 and ter_id ='$district')";
        foreach ($this->conn->query($sql) as $row) {
            $return_arr[] = [$row['ter_id']=> $row['ter_name']];
        }
        return $return_arr;
    }

}