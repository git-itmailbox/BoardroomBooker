<?php
require_once('models/boardrooms.php');
require_once('models/appointments.php');
class BoardroomController
{
    private $args;

    function __construct($args)
    {
        $this->args = $args;

    }

    public function index()
    {
        $boardrooms = Boardroom::all();
        require_once('views/boardrooms/index.php');
    }

    public function add()
    {
        $boardroom = new Boardroom();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $boardroom->name = $_POST['name'];

            if (empty($boardroom->name))
                $errors['required'] = "Please fill all fields!";
            
            if (empty($errors) && $boardroom->save($boardroom->name)) {
                header("Location:/boardrooms/index");
            }

        }

        $action = "/boardrooms/add";
        require_once('views/boardrooms/add.php');
    }

    public function edit()
    {
        $action = "/boardrooms/edit";
        $boardroom = new Boardroom();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $boardroom->name = $_POST['name'];
            $boardroom->is_default =isset($_POST['is_default'])?$_POST['is_default']:null;
            $boardroom->id = $_POST['id'];
            if (empty($boardroom->name))
                $errors['required'] = "Please fill all fields!";

            if (empty($errors) && $boardroom->update( $boardroom->id, $boardroom->name, $boardroom->is_default) > 0) {
                header("Location:/boardrooms/index");
            }

            require_once('views/boardrooms/edit.php');
        }


        // $args[3] - следующий параметр после action будет id boardroom
        if (!isset($this->args[3]))
            return call('mainpage', 'error');

        $boardroom = Boardroom::find($this->args[3]);
        if($boardroom->id==0)
        {
                header("Location:/boardrooms/index");

        }
        require_once('views/boardrooms/edit.php');
    }


    public function ajaxdelete()
    {
        $boardroom = new Boardroom();
        $appointment = new Appointments();
        if (isset($_POST['id']) && (int)$_POST['id'] > 0) {
            $id = $_POST['id'];

            if ($boardroom->delete($id) && $appointment->deleteByBoardroomId($id)) {
                echo json_encode(['message' => 'Successfully deleted', 'status' => 'ok']);
                return;
            }
        }

    }
}