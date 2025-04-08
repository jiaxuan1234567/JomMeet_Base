<?php
require_once __DIR__ . '/../../../BusinessLogic/model/homeModel/homeModel.php';
require_once __DIR__ . '/../../../Presentation/view/homeView/homeView.php';

class homeController
{
    public function index()
    {
        // session_start();

        $view = new homeView();

        $view->header();
        if (!isset($_SESSION['userId'])) {
            $view->render();
        } else {
            $userId = $_SESSION['userId'];
            $model = new homeModel();
            $userSummary = $model->getUserSummary($userId);
            $notifications = $model->getNotifications($userId);
            $view->render();
        }
        $view->footer();
    }
}
