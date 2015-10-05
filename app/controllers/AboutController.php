<?php

class AboutController extends ControllerBase
{

    public function indexAction()
    {
        $result = $this->__getCMSBlock(4);

	$this->assets->addJs('js/fixedHeight.js');
        $this->view->title = sprintf('%s | upmod', $result['title']);
        $this->view->content = $result['content'];
        $this->view->pageTitle = $result['title'];
    }

}

