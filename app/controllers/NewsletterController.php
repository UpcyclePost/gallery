<?php

class NewsletterController extends \Phalcon\Mvc\Controller
{
    public function subscribeAction() {
        $this->view->disable();

        $success = false;
        if ($this->request->isPost() && $this->request->isAjax()) {

            $marketing = new Marketing();
            $marketing->email = $this->request->getPost('email');
            $marketing->ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '';
            $marketing->forwarded_ip = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';
            $marketing->subscribed = date('Y-m-d H:i:s');

            $success = $marketing->save();
        }

        echo json_encode(Array('success' => $success, 'data' => []));
    }
}

