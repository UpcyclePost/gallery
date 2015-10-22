<?php

class ContactController extends ControllerBase
{
    public function indexAction()
    {
        $this->view->title = 'Contact | Upmod';
        $this->view->_mixpanel_page = 'Contact';

        if ($this->request->isPost())
        {
            $message = sprintf('Name: %s<br />Company: %s<br />Email: %s<br />Phone: %s<br /><br />Message:<br />%s',
	            $this->request->getPost('name'),
	            $this->request->getPost('company'),
	            $this->request->getPost('email'),
	            $this->request->getPost('phone'),
	            $this->request->getPost('message'));

            Helpers::sendEmail('info@upmod.com', 'Contact Request', $message);

            $this->flash->success('Your message has been sent.');

            return $this->response->redirect('contact');
        }
    }
}
