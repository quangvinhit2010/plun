<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class JsController extends Controller {
    public function actionIndex(){
        header('Content-Type: application/javascript');
        
        exit;
    }
}
?>
