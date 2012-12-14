<?php
namespace Sonata\AdminBundle\Lists;

class RelatedListMapper
{
    protected $lists = array();

    public function add(RelatedList $list)
    {
        $this->lists[] = $list;
    }

    public function getAll()
    {
        return $this->lists;
    }
}