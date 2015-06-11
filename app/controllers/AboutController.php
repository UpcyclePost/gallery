<?php

class AboutController extends ControllerBase
{

    public function indexAction()
    {
        $result = $this->__getCMSBlock(4);

        $this->view->title = sprintf('%s | UpcyclePost', $result['title']);
        $this->view->content = $result['content'];
        $this->view->pageTitle = $result['title'];
    }

}

