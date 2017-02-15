<?php

class Boardroom
{
    public $id;
    public $name;
    public $is_default;

    private $db;

    public function __construct( $name="",$is_default=0,  $id=0) {
        $this->id      = $id;
        $this->name  = $name;
        $this->is_default  = $is_default;
        $this->db = Db::getInstance();
    }
    //get all Boardrooms
    public static function all() {
        $list = [];
        $db = Db::getInstance();
        $req = $db->query('SELECT * FROM boardrooms');

        foreach($req->fetchAll() as $boardroom) {
            $list[] = new Boardroom($boardroom['name'],$boardroom['is_default'], $boardroom['id']);
        }

        return $list;
    }

    public static function find($id) {
        $db = Db::getInstance();
        // we make sure $id is an integer
        $id = intval($id);
        $req = $db->prepare('SELECT * FROM boardrooms WHERE id = :id');
        // the query was prepared, now we replace :id with our actual $id value
        $req->execute(array(':id' => $id));
        $boardroom = $req->fetch();

        return new Boardroom($boardroom['name'], $boardroom['is_default'], $boardroom['id']);
    }

    public function save($name, $default=NULL)
    {
        $db = Db::getInstance();
        $sql = "INSERT INTO boardrooms(name, is_default) Value (:name, :is_default)";
                                        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':name',$name);
        $stmt->bindValue(':is_default',$default);
        $stmt->execute();
        return $db->lastInsertId();
    }

    
    public static function getDefault()
    {
        $db = Db::getInstance();
         $req = $db->prepare('SELECT * FROM boardrooms WHERE is_default = 1');
                                      
        $req->execute();
        $boardroom = $req->fetch();

        return new Boardroom($boardroom['name'], $boardroom['id'], $boardroom['is_default']);
    }

    
    public function getBoardroomByName($name)
    {
        $db = Db::getInstance();
         $req = $db->prepare('SELECT * FROM boardrooms WHERE name = :name');
                                      
        $req->execute(array(':name' => $name));
        $boardroom = $req->fetch();

        return new Boardroom($boardroom['name'], $boardroom['id']);
    }

    public function clearDefault(){
     $db = Db::getInstance();
       
        $sql = "UPDATE boardrooms SET is_default = null WHERE is_default = 1 ";
          $req = $db->query($sql);
    }
    
    
    
    public function update($id, $name, $is_default)
    {
    var_dump( $is_default);
         if($is_default==='1') self::clearDefault();
        $db = Db::getInstance();
       
        $sql = "UPDATE boardrooms SET name = :name, is_default =:is_default WHERE id = :id ";
                                        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':name',$name);
        $stmt->bindValue(':is_default', $is_default);
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }
    public function delete($id)
    {
        $db = Db::getInstance();
        $sql = "DELETE FROM boardrooms WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id',$id);

        return $stmt->execute();
    }
    
}