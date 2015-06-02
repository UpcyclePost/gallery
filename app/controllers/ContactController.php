<?php

class ContactController extends ControllerBase
{
    public function indexAction()
    {
        $this->view->title = 'Contact | UpcyclePost';

        if ($this->request->isPost())
        {
            $message = sprintf('Name: %s<br />Company: %s<br />Email: %s<br />Phone: %s<br /><br />Message:<br />%s',
	            $this->request->getPost('name'),
	            $this->request->getPost('company'),
	            $this->request->getPost('email'),
	            $this->request->getPost('phone'),
	            $this->request->getPost('message'));

            Helpers::sendEmail('info@upcyclepost.com', 'Contact Request', $message);

            $this->flash->success('Your message has been sent.');

            return $this->response->redirect('contact');
        }
    }
}