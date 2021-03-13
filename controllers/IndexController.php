<?php

namespace app\controllers;

use app\engine\App;
use app\model\entites\Messages;

class IndexController extends Controller
{

    public function actionMain()
    {
        $this->params['messages'] = App::call()->MessagesRepository->getAllRevert();
        echo $this->render('index', $this->params);
    }
    public function actionAll()
    {
        echo json_encode(App::call()->MessagesRepository->getAllRevert());
    }
    public function actionAdd()
    {
        $name = App::call()->Request->getParams()['name'];
        $email = App::call()->Request->getParams()['email'];
        $message = App::call()->Request->getParams()['message'];

        $objMessage = new Messages($name, $email, $message);
        App::call()->MessagesRepository->save($objMessage);
        if ($objMessage->id) {
            echo json_encode([
                'result' => 'ok',
                'id' => $objMessage->id,
                'name' => $objMessage->name,
                'email' => $objMessage->email,
                'message' => $objMessage->message,
            ]);
            die();
        }
    }
    public function actionDel()
    {
        $id = App::call()->Request->getParams()['id'];
        $message = App::call()->MessagesRepository->getOne($id);
        $objMessage = new Messages($message->name, $message->email, $message->message);
        $objMessage->id = $id;
        App::call()->MessagesRepository->delete($objMessage);
        echo json_encode(['result' => 'ok']);
        die();
    }
}
