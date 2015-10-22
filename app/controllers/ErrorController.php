<?php

class ErrorController extends ControllerBase
{
    public function indexAction()
    {
        //$this->view->setMainView('layouts/error');
        $this->tag->setTitle('Error');
        $this->response->setHeader(404, 'Not Found');
        $this->view->pick('error/404');
        $this->view->title = 'Error | Upmod';
        $this->view->_mixpanel_page = 'Error';
    }

    public function show404Action()
    {
        $this->view->disable();

        return $this->response->redirect('error');
    }
}
