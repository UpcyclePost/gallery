<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class Marketing extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $ik;

    /**
     *
     * @var string
     */
    public $firstName;

    /**
     *
     * @var string
     */
    public $lastName;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $organization;

    /**
     *
     * @var string
     */
    public $ip;

    /**
     *
     * @var string
     */
    public $forwarded_ip;

    /**
     *
     * @var string
     */
    public $subscribed;

    /**
     * Validations and business logic
     */
    public function validation()
    {

        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
                    'required' => true,
                )
            )
        );
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setReadConnectionService('readDb');
        $this->setWriteConnectionService('writeDb');

        $this->setSource('Marketing');
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'ik' => 'ik', 
            'firstName' => 'firstName', 
            'lastName' => 'lastName', 
            'email' => 'email', 
            'organization' => 'organization', 
            'ip' => 'ip', 
            'forwarded_ip' => 'forwarded_ip', 
            'subscribed' => 'subscribed'
        );
    }

}
