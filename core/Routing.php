<?php
class Routing
{
    public static $route = [
       'address' => ['url' => '/protest/index/address', 'action' => 'getAddress'],
       'save' => ['url' => '/protest/index/save', 'action' => 'save'],
       'index' => ['url' => '/protest/index', 'action' => 'index']
    ];

    public static function execute()
    {

        $fileController = 'controllers/IndexController.php';
        if (file_exists($fileController))
        {
            include ($fileController);
            $cnt = 0;
            foreach(self::$route as $value)
            {
                if($_SERVER['REQUEST_URI'] == $value['url'])
                {
                    $cnt++;
                    $controller = new IndexController();
                    call_user_func(array($controller, $value['action']));
                }
            }
            if ($cnt == 0)
            {
                echo 'Такой страницы не существует!';
            }
        }
    }
}