<?php
include_once('models/Address.php');
include_once('models/User.php');

class IndexController extends Controller
{
    public function __construct(){
        parent::__construct();
    }

    public function index(){

        if (empty($_POST['region']) and empty($_POST['district'])){
            $model = new Address();
            $data = $model->getRegion();
        }
        $view = 'index';
        $this->view->render($view, $data);
    }

    public function getAddress(){

        if (isset($_POST['region']) and empty($_POST['district'])){
            $region = filter_input(INPUT_POST, 'region');
            $model = new Address();
            $data = $model->getDistrict($region);
            echo json_encode($data);
        } elseif (isset($_POST['district'])) {
            $district = filter_input(INPUT_POST, 'district');
            $model = new Address();
            $data = $model->getCity($district);
            echo json_encode($data);
        }
    }

    public function save(){

        if (!empty($_POST['name']) and !empty($_POST['email']) and !empty($_POST['region'])){
            if (!empty($_POST['city'])){
                $city = strip_tags($_POST['city']);
            } elseif (!empty($_POST['district'])) {
                $city = strip_tags($_POST['district']);
            } else {
                $city = strip_tags($_POST['region']);
            }
            $name = strip_tags($_POST['name']);
            $email = strip_tags($_POST['email']);
            $user = new User($name,$email,$city);
            $data = $user->save();
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'region']);
        }

    }
}