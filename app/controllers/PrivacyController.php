<?php

class PrivacyController extends ControllerBase
{

    public function indexAction()
    {
	    $this->assets->addJs('js/fixedHeight.js');
        $this->view->title = 'Privacy Policy | UpcyclePost';
    }

}

