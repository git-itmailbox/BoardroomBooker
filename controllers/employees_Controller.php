<?php
require_once('models/employees.php');

require_once('models/boardrooms.php');


/**
 * Created by PhpStorm.
 * User: yura
 * Date: 06.02.17
 * Time: 23:04
 */
class EmployeesController
{
    private $args;

    function __construct($args)
    {
        $this->args = $args;

    }

    public function index()
    {


        // we store all the employee in a variable
        $employees = Employee::all();
        require_once('views/employees/index.php');
    }

    public function add()
    {
        $boardrooms = Boardroom::all();

        $employee = new Employee();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $employee->fullname = $_POST['fullname'];
            $employee->email = $_POST['email'];
            if (empty($employee->fullname) || empty($employee->email))
                $errors['required'] = "Please fill all fields!";
            if (!filter_var($employee->email, FILTER_VALIDATE_EMAIL))
                $errors['not_valid_email'] = "Please enter valid email!";


            if (empty($errors) && $employee->save($employee->fullname, $employee->email) > 0) {
                header("Location:/employees/index");
            }

        }

        $action = "/employees/add";
        require_once('views/employees/add.php');
    }

    public function edit()
    {
        $action = "/employees/edit";
        $employee = new Employee();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $employee->fullname = $_POST['fullname'];
            $employee->email = $_POST['email'];
            $employee->id = $_POST['id'];
            if (empty($employee->fullname) || empty($employee->email))
                $errors['required'] = "Please fill all fields!";
            if (!filter_var($employee->email, FILTER_VALIDATE_EMAIL))
                $errors['not_valid_email'] = "Please enter valid email!";


            if (empty($errors) && $employee->update($employee->id,$employee->fullname, $employee->email) > 0) {
                header("Location:/employees/index");
            }

            require_once('views/employees/edit.php');
        }


        // $args[3] - следующий параметр после action будет id employee
        if (!isset($this->args[3]))
            return call('mainpage', 'error');

        $employee = Employee::find($this->args[3]);

        require_once('views/employees/edit.php');
    }


    public function ajaxdelete()
    {
        $employee = new Employee();

        if (isset($_POST['id']) && (int)$_POST['id'] > 0) {
            $id = $_POST['id'];

            if ($employee->delete($id)) {
                echo json_encode(['message' => 'Successfully deleted', 'status' => 'ok']);
                return;
            }
        }

    }
}