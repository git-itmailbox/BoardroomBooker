<?php
//model users

class Users{
    public $user_id,
           $user_login,
           $user_hash,
           $user_passwd;
    private $db;

    public function __construct($data = ['user_login'=>"", 'user_hash'=>"", 'user_passwd'=>"", 'user_id'=>0 ]) {
        $this->user_id      = $data['user_id'];
        $this->user_login   = $data['user_login'];
        $this->user_hash    = $data['user_hash'];
        $this->user_passwd  = $data['user_passwd'];
        $this->time_format  = $data['time_format'];
        $this->first_day_week  = $data['first_day_week'];

        $this->db = Db::getInstance();
    }
   
    public static function findById($id) {
        $db = Db::getInstance();
        // we make sure $id is an integer
        $id = intval($id);
        $req = $db->prepare('SELECT * FROM users WHERE id = :id');
        // the query was prepared, now we replace :id with our actual $id value
        $req->execute(array('id' => $id));
        $employee = $req->fetch();

        return new Employee($employee['fullname'], $employee['email'], $employee['id']);
    }
  
    public static function findByLogin($login) {
        $db = Db::getInstance();
        $req = $db->prepare("SELECT * FROM users where user_login = :login");
        // the query was prepared, now we replace :login with our actual $login value
        $req->execute([':login' => $login]);
        $user = $req->fetch();

        return new Users([
          'user_id'     =>$user['user_id'],
          'user_login'  =>$user['user_login'],
          'user_hash'   =>$user['user_hash'],
          'user_passwd' =>$user['user_passwd'],
          'time_format' =>$user['time_format'],
          'first_day_week' =>$user['first_day_week'],
        ]);
    }

  public function updateHash($hash, $id=0)
    {
        $id=($id==0)?$this->user_id:$id;
        $db = Db::getInstance();
        $sql = "UPDATE users SET user_hash = :hash WHERE user_id = :id";          
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':hash',$hash);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

  public function updateFirstDayWeek($mode, $id=0)
    {
        $id=($id==0)?$this->user_id:$id;
        $db = Db::getInstance();
        $sql = "UPDATE users SET first_day_week = :mode WHERE user_id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':mode',$mode);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

  public function updateTimeMode($mode, $id=0)
    {
        $id=($id==0)?$this->user_id:$id;
        $db = Db::getInstance();
        $sql = "UPDATE users SET time_format = :mode WHERE user_id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':mode',$mode);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

}