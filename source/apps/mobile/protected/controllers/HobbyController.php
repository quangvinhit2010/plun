<?php

/**
 * @author HuyDo
 * @desc My Controller
 */
class HobbyController extends MemberController {
    public function actionGethobbies(){
        $hobbies    =   new SysHobbies();
        echo json_encode($hobbies->getHobbiesName());
        exit;
    }
    
}