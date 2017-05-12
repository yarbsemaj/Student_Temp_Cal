<?php

/**
 * Created by PhpStorm.
 * User: yarbs
 * Date: 11/05/2017
 * Time: 04:47 PM
 */

require_once (__DIR__ . '/../DAO.php');
class SQLModel
{
    protected $link;
    public function __construct()
    {
        $dao = new DAO();
        $this->link = $dao ->getLink();
    }

}