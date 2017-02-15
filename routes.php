<?php
/**
 * Created by PhpStorm.
 * User: yura
 * Date: 06.02.17
 * Time: 22:03
 */
function call($controller, $action, $args=[])
{

    require_once('controllers/' . $controller . '_Controller.php');

    // создаем нужный контроллер
    switch ($controller) {
        case 'mainpage':
            $controller = new MainPageController($args);
            break;
        case 'employees':
            $controller = new EmployeesController($args);
            break;

        case 'boardrooms':
            $controller = new BoardroomController($args);
            break;

    }

    // $action будут нашими методами
    $controller->{$action}();
}


//перечисляем наши контроллеры и их $action
$controllers = array('mainpage' => ['home', 'error','addmeeting', 'ajaxupdate','getdetail','ajaxdelete', 'config', 'logout'],
                        'employees' => ['index', 'listall','add','edit', 'ajaxdelete'],
                        'boardrooms' => ['index', 'listall','add','edit', 'ajaxdelete'],
                    );

// Если контроллер существует - включаем его, иначе переходим на страницу ошибки
if (array_key_exists($controller, $controllers)) {
    if (in_array($action, $controllers[$controller])) {
        call($controller, $action,$args);
    } else {
        call('mainpage', 'error');
    }
} else {
    call('mainpage', 'error');
}
