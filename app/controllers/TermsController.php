<?php

class TermsController extends ControllerBase
{

    public function indexAction()
    {
        $result = $this->__getCMSBlock(3);

	    $this->assets->addJs('js/fixedHeight.js');

        $this->view->title = sprintf('%s | UpcyclePost', $result['title']);
        $this->view->content = $result['content'];
        $this->view->pageTitle = $result['title'];
    }

}

