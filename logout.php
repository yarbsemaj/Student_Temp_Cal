<?php
/**
 * Created by PhpStorm.
 * User: yarbs
 * Date: 12/05/2017
 * Time: 12:37 AM
 */
session_start();
session_destroy();

header("Location: /");