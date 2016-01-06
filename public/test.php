<?php
/**
 * Created by PhpStorm.
 * User: Copoc
 * Date: 2016/1/1
 * Time: 15:49
 */
session_start();
if(!empty($_SESSION['test'])){
    echo $_SESSION['test'];
}else{
    $_SESSION['test'] = 'ok';
    echo 'set';
}