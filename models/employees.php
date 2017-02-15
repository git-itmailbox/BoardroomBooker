<?php

/**
 * Created by PhpStorm.
 * User: yura
 * Date: 06.02.17
 * Time: 23:17
 */
class Employee
{
    public $id;
    public $fullname;
    public $email;
    private $db;

    public function __construct( $fullname="", $email="", $id=0) {
        $this->id      = $id;
        $this->fullname  = $fullname;
        $this->email = $email;
        $this->db = Db::getInstance();
    }
    //get all employees
    public static function all() {
        $list = [];
        $db = Db::getInstance();
        $req = $db->query('SELECT * FROM employees');

        foreach($req->fetchAll() as $employee) {
            $list[] = new Employee($employee['fullname'], $employee['email'], $employee['id']);
        }

        return $list;
    }

    public static function find($id) {
        $db = Db::getInstance();
        // we make sure $id is an integer
        $id = intval($id);
        $req = $db->prepare('SELECT * FROM employees WHERE id = :id');
        // the query was prepared, now we replace :id with our actual $id value
        $req->execute(array('id' => $id));
        $employee = $req->fetch();

        return new Employee($employee['fullname'], $employee['email'], $employee['id']);
    }

    public function save($fullname, $email)
    {
        $db = Db::getInstance();
        $sql = "INSERT INTO employees(fullname,email) Value (:fullname, :email)";
                                        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':fullname',$fullname);
        $stmt->bindValue(':email',$email);
        $stmt->execute();
        return $db->lastInsertId();
    }

 public function update($id, $fullname, $email)
    {
        $db = Db::getInstance();
        $sql = "UPDATE employees SET fullname = :fullname, email = :email WHERE id = :id ";
                                        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':fullname',$fullname);
        $stmt->bindValue(':email',$email);
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }


    public function delete($id)
    {
        $db = Db::getInstance();
        $sql = "DELETE FROM employees WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id',$id);

        return $stmt->execute();
    }
}