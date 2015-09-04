<?php

class Category extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $ik;

    /**
     *
     * @var integer
     */
    public $parent_ik;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $slug;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'ik' => 'ik', 
            'parent_ik' => 'parent_ik', 
            'title' => 'title', 
            'slug' => 'slug'
        );
    }
}
