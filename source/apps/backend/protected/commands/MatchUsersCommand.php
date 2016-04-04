<?php
require_once('Zend/Search/Lucene.php');
class MatchUsersCommand extends CConsoleCommand {

    public function actionBuildAddFriend(){
        $user = new YumUser();
        $criteria_getUser = new CDbCriteria(array(
            'order' => 'id DESC'        
        ));
        
        $dataAllUser      =   $user->findAll($criteria_getUser);  
        
        foreach($dataAllUser AS $u_user){
            $criteria_getRandomUser = new CDbCriteria(array(
                'order' => 'RAND()',
                'limit' =>  '20'
            ));  
            $dataRandomUser   =   $user->findAll($criteria_getRandomUser);
            
            foreach ($dataRandomUser AS $r_user){
                $YumFriendship  =   new usrFriendship();
                $YumFriendship->setAttributes(array(
                    'inviter_id'    =>  $u_user->getAttribute('id'),
                    'friend_id'     =>  $r_user->getAttribute('id'),
                    'status'     =>  2,
                    'message'   =>  'message'
                ));
                $YumFriendship->validate();
                $YumFriendship->save();
            }
        }
    }
    public function actionRedis(){
        $redis  =   new CityonCache();
        $country   =   array(
            '1' => array( 'id' => '1', 'name' => 'VietNam','code' => 'VN'),
            '2' => array( 'id' => '2', 'name' => 'ThaiLand', 'code' =>'TL'),
             '3' => array( 'id' => '3', 'name' => 'Philippin', 'code' =>'PL'),
             '4' => array( 'id' => '4', 'name' => 'indo', 'code' =>'ID')
         );
        $city = array(
            '1' => array( 'id' => '1', 'name' => 'Hồ Chí Minh', 'country_id' => '1', 'code' => 'HCM'),
            '2' => array( 'id' => '2', 'name' => 'Hà Nội', 'country_id' => '1','code' => 'HN'),
            '3' => array( 'id' => '3', 'name' => 'Biên Hòa', 'country_id' => '1', 'code' => 'BH'),
            '4' => array( 'id' => '4', 'name' => 'Bangkok', 'country_id' => '2', 'code' => 'BK'),
             '5' => array( 'id' => '5', 'name' => 'Nonthaburi City', 'country_id' => '2'),
            '6' => array( 'id' => '6', 'name' => 'Pak Kret', 'country_id' => '2'),
            '7' => array( 'id' => '7', 'name' => 'Hat Yai', 'country_id' => '3'),
            '8' => array( 'id' => '8', 'name' => 'Las Piñas', 'country_id' => '3'),
            '9' => array( 'id' => '9', 'name' => 'Malabon', 'country_id' => '3'),
        );
         $district = array(
            '1' => array( 'id' => '1', 'name' => 'Quan 9', 'country_id' => '1', 'city_id' => '1'),
            '2' => array( 'id' => '2', 'name' => 'Quan 1', 'country_id' => '1', 'city_id' => '1'),
            '3' => array( 'id' => '3', 'name' => 'QUan 2', 'country_id' => '1', 'city_id' => '1'),
        );       
        print_r($redis->addCityList($city));
    }
    public function actionbuildAll(){
        $search     =   new LucenseSearch();
        $search->rebuildAllSearchIndex();
    }
    public function actionUpdateIndex(){
        $search     =   new LucenseSearch();
        $new_profile    =   array(
            'user_id'   =>  '1',
            'username'  =>  'admin',
            'lastname'  =>  'admin',
            'firstname'  =>  'admin',
            'alias_name'  =>  'admin',
            'avatar'  =>  'admin/ad/add',
            'location_id'  =>  '1',
        );
        $search->updateSearchIndex(1, $new_profile);
    }
    public function actionFindInIndex($keyword){
        /*
        $conditions =    array(
            'filter'   =>                  
                array(
                    'p.location_id' => array('=' => 1),
                ),
            'filter_cal' => array(
                'age' => array('<' => 36, '>' => 18)
            ),
            'sort_by' =>  array('pos_type' => '4'),
            'keyword'  =>  false
         );
        */
        $conditions =    array(
            'keyword'  =>  'admin'
         );
        $search     =   new LucenseSearch();
        $results    =   $search->querySearchIndex($conditions);  
        $i = 0;

        foreach($results['data'] AS $row){
            echo "\n--------------------\n";
            echo ($row->user_id) . "\n";
            echo ($row->status) . "\n";
            echo ($row->username) . "\n";
            echo ($row->avatar) . "\n";
            echo "\n--------------------\n";
            echo $i;
            $i++;
        }
    }
    
    public function buildSearchIndexName($search_conditions = array()){
        $search_index_name  =   'search_index';
            if(sizeof($search_conditions) > 0){
            foreach($search_conditions AS $key => $value){
                $search_index_name  .=  "_{$key}_{$value}";            
            }            
        }else{
            $search_index_name  =   'search_index_all';
        }
        return $search_index_name;
    }


    public function actionBuildSearchIndex(){
        $conditions =    array(
            'keyword'  =>  'admin'
         );
        $search     =   new LucenseSearch();
        $search->buildSearchIndex($conditions);
    }
    public function actionBuildUsers($limit) {
        for ($i = 0; $i < $limit; $i++) {
            $user = new YumUser();
            $user->username = sprintf('Demo_%d_%d', rand(1, 500000), $i);
            $user->salt = YumEncrypt::generateSalt();
            $user->password = YumEncrypt::encrypt('123456', $user->salt);
            $user->createtime = time();
            $user->status = 1;
            $user->alias_name = sprintf('Demo_%d_%d', rand(1, 500000), $i);
           
            $user->validate();

            if ($user->save()) {                
                    $profile = new YumProfile();
                    $profile->user_id = $user->id;
                    $profile->timestamp = time();
                    $profile->firstname = $user->username;
                    $profile->lastname = $user->username;
                    $profile->privacy = 'protected';
                    $profile->email = $user->alias_name . '@gmail.com';
                    $profile->validate();
                    $profile->save();
            }
        }
    }

    public function actionDeleteAll() {
        $user = new YumUser();
        $user->deleteAll();
    }

}