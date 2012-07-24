<?php

namespace Entities;

/** @Entity @Table(name="users") */
class User
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;
    /** @Column(type="string", length=150) */
    private $name;
    /** @Column(type="string", length=32, nullable=false) */
    private $test;
    public function getTest() {
        return $this->test;
    }

    public function setTest($test) {
        $this->test = $test;
    }

        public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }



}