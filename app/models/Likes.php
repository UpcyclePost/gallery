<?php
use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class Likes extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $post_ik;

    /**
     *
     * @var integer
     */
    public $user_ik;

    /**
     *
     * @var string
     */
    public $ts;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('Likes');
        $this->hasOne('user_ik', 'User', 'ik');
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'post_ik' => 'post_ik', 
            'user_ik' => 'user_ik', 
            'ts' => 'ts'
        );
    }

    public function validation() {
        $this->validate(new Uniqueness(
            array(
                "field"   => ['post_ik','user_ik'],
                "message" => "User already liked this post."
            )
        ));

        if ($this->validationHasFailed() === true) {
            return false;
        }
    }

}
