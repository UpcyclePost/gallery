<?php

class TermsController extends ControllerBase
{

    public function indexAction()
    {
	    $this->assets->addJs('js/fixedHeight.js');
        $this->view->title = 'Terms of Use | UpcyclePost';
    }

}

