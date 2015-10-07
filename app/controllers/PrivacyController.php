<?php

class PrivacyController extends ControllerBase
{

    public function indexAction()
    {
        $result = $this->__getCMSBlock(7);

        $this->assets->addJs('js/fixedHeight.js');

        $this->view->title = sprintf('%s | UpcyclePost', $result['title']);
        $this->view->content = $result['content'];
        $this->view->pageTitle = $result['title'];
        $this->view->_mixpanel_page = 'Privacy Policy';
    }

}

