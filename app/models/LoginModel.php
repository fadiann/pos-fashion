<?php
class Login {
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }   
    public function userLogin($userName, $password){
        $userName = addslashes(trim($userName));
        $password = addslashes(trim($password));
        $sql   = "SELECT count(*) from tbl_users where username = '$userName' and password = '$password'";
        $res   = $this->db->query($sql);
        $data  = ($res && $row = $res->FetchRow()) ? (int)$row[0] : 0;
        return $data;
    }

    public function userSession($userName, $password){
        $userName = addslashes(trim($userName));
        $password = addslashes(trim($password));
        $sql   = "SELECT * from tbl_users where username = '$userName' and password = '$password'";
        $res   = $this->db->query($sql);
        $data  = $res ? $res->FetchRow() : null;
        return $data;
    }
    public function userLogout(){
        $_SESSION['userId']    = false;
        session_destroy();
    }
}
?>