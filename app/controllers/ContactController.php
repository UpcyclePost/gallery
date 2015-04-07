<?php

class ContactController extends ControllerBase
{

    public function indexAction()
    {
        $this->view->title = 'Contact | UpcyclePost';

        if ($this->request->isPost()) {
            $message = 'Name: ' . $this->request->getPost('name') . '<br />' .
                'Company: ' . $this->request->getPost('company') . '<br />' .
                'Email: ' . $this->request->getPost('email') . '<br />' .
                'Phone: ' . $this->request->getPost('phone') . '<br /><br />' .
                'Message:<br />' . $this->request->getPost('message');

            Helpers::sendEmail('info@upcyclepost.com', 'Contact Request', $message);

            $this->flash->success('Your message has been sent.');
            return $this->response->redirect('contact');
        }
    }

}

