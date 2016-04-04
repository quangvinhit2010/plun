<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('Zend/Search/Lucene.php');

class LucenseSearch {
	/**
	 * 
	 * search friend by a email list
	 * @param a email or array email $email_list
	 * @param int $offset
	 * @param int $limit
	 */
	public function findFriendByEmail($email_list, $user_id_except = false, $offset = 0, $limit = 20){
		if(!is_array($email_list)){
			$email_list	=	array($email_list);
		}
	    setlocale(LC_ALL, "en_US.UTF-8");
    	Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
        Zend_Search_Lucene_Analysis_Analyzer::setDefault(
                new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive()
        );
        $index_file = Yii::app()->params->Lucene['index_file_dir'] . DS .
                $this->buildSearchIndexName();
        
        if (is_dir($index_file)) {
            $index = new Zend_Search_Lucene($index_file);
        } else {
            //rebuild search index files
            $index = $this->buildSearchIndex();
        }		
        
        $query = new Zend_Search_Lucene_Search_Query_Boolean();
		Zend_Search_Lucene_Search_Query_Wildcard::setMinPrefixLength(0);

		foreach($email_list AS $email){
			$pattern = new Zend_Search_Lucene_Index_Term($email, 'email');
			$subquery = new Zend_Search_Lucene_Search_Query_Term($pattern);
        	$query->addSubquery($subquery, null);   	
		}	        
		
        if ($user_id_except) {
		    //except user
			$subquery_status = new Zend_Search_Lucene_Search_Query_MultiTerm();
            foreach ($user_id_except AS $user_id) {
                $subquery_status->addTerm(new Zend_Search_Lucene_Index_Term($user_id, 'user_id'), false);
            }
            $query->addSubquery($subquery_status, null); 
        }
        
		$results = $index->find($query);
		$email_friends	=	array();
        $data = array();

        foreach ($results AS $row) {
            $data[$row->user_id] = array(
                'user_id' => $row->user_id,
                'current_city_id' => $row->current_city_id,
                'current_state_id' => $row->current_state_id,
                'current_district_id' => $row->current_district_id,
                'current_country_id' => $row->current_country_id,
                'sex_role' => $row->sex_role,
                'birthday_year' => $row->birthday_year,
                'total_friends' =>  $row->total_friends,
                'alias_name' => $row->alias_name
            );
            $email_friends[]	=	$row->email;
        }
        //end merge

        $results_search    =   array();
        $i  =   0;
        $total_data_merge   =   sizeof($data);
        $total_item_show    =   0;
        foreach($data AS $user_id   =>  $user){
           
            if( ($i >= $offset && $i < $offset + $limit) && ($i < $total_data_merge)){
                $total_item_show++;
                $results_search[$user_id] = $user;    
            }
             $i++;
        }
        if(($total_item_show < $limit) || ($total_item_show + $offset == $total_data_merge)){
            $show_more  =   false;
        }else{
            $show_more  =   true;
        }

        return array('data' => $results_search,'email_friends' => $email_friends, 'total' => $total_data_merge, 'show_more' => $show_more);
	}
    public function querySearchIndex($conditions = array(), $user_id_except = false, $data_merge = array(), $offset = 0, $limit = 20) {
        setlocale(LC_ALL, "en_US.UTF-8");
    	Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
        Zend_Search_Lucene_Analysis_Analyzer::setDefault(
                new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive()
        );
        $index_file = Yii::app()->params->Lucene['index_file_dir'] . DS .
                $this->buildSearchIndexName($conditions);
        
        if (is_dir($index_file)) {
            $index = new Zend_Search_Lucene($index_file);
        } else {
            //rebuild search index files
            $index = $this->buildSearchIndex($conditions);
        }

        $query = new Zend_Search_Lucene_Search_Query_Boolean();

        $subquery_status = new Zend_Search_Lucene_Search_Query_MultiTerm();
        $subquery_status->addTerm(new Zend_Search_Lucene_Index_Term('1', 'status'));
        if ($user_id_except) {
            foreach ($user_id_except AS $user_id) {
                $subquery_status->addTerm(new Zend_Search_Lucene_Index_Term($user_id, 'user_id'), false);
            }
        }

        //search by keyword
        if (!empty($conditions['keyword'])) {
        	
			Zend_Search_Lucene_Search_Query_Wildcard::setMinPrefixLength(0);
			
			$pattern = new Zend_Search_Lucene_Index_Term("*{$conditions['keyword']}*", 'username');
			$subquery = new Zend_Search_Lucene_Search_Query_Wildcard($pattern);
			
            $query->addSubquery($subquery, true);            	
        }

        $query->addSubquery($subquery_status, true /* prohibited */);

        $results = $index->find($query, 'ordering', SORT_NUMERIC,  SORT_ASC);
        $data = array();

        //merge
        if($data_merge){
            foreach ($data_merge AS $row) {
                $data[$row['user_id']] = array(
                    'user_id' => $row['user_id'],
                    'current_city_id' => $row['current_city_id'],
                	'current_state_id' => $row['current_state_id'],
                    'current_district_id' => $row['current_district_id'],
                    'current_country_id' => $row['current_country_id'],
                    'sex_role' => $row['sex_role'],
                    'birthday_year' => $row['birthday_year'],
                    'alias_name' => $row['alias_name']
                );
            }
        }
        foreach ($results AS $row) {
            $data[$row->user_id] = array(
                'user_id' => $row->user_id,
                'current_district_id' => $row->current_district_id,
                'current_city_id' => $row->current_city_id,
            	'current_state_id' => $row->current_state_id,
                'current_country_id' => $row->current_country_id,
                'sex_role' => $row->sex_role,
                'birthday_year' => $row->birthday_year,
                'total_friends' =>  $row->total_friends,
                'alias_name' => $row->alias_name
            );
        }
        //end merge

        $results_search    =   array();
        $i  =   0;
        $total_data_merge   =   sizeof($data);
        $total_item_show    =   0;
        foreach($data AS $user_id   =>  $user){
           
            if( ($i >= $offset && $i < $offset + $limit) && ($i < $total_data_merge)){
                $total_item_show++;
                $results_search[$user_id] = $user;    
            }
             $i++;
        }
        if(($total_item_show < $limit) || ($total_item_show + $offset == $total_data_merge)){
            $show_more  =   false;
        }else{
            $show_more  =   true;
        }

        return array('data' => $results_search, 'total' => $total_data_merge, 'show_more' => $show_more);
    }

    public function updateSearchIndex($user_id, $new_profile) {

        $index_rows = SysSearchIndex::model()->findAll();
        foreach ($index_rows AS $row) {
            $index_file = Yii::app()->params->Lucene['index_file_dir'] . DS .
                    $row->index_folder;

            if (is_dir($index_file)) {
                $index = new Zend_Search_Lucene($index_file);
                //delete old row 
                $doc_need_delete = $index->find("user_id:{$user_id}");
                foreach ($doc_need_delete AS $doc) {
                    $index->delete($doc->id);
                }
                //add new
                $doc = new Zend_Search_Lucene_Document();
                $doc->addField(Zend_Search_Lucene_Field::Keyword('user_id', $new_profile['user_id']));
                $doc->addField(Zend_Search_Lucene_Field::Keyword('username', $new_profile['username']));
                $doc->addField(Zend_Search_Lucene_Field::Keyword('status', '1'));

                // add job to the index
                $index->addDocument($doc);
                $index->commit();
            }
        }
    }

    /**
     * rebuild all search index
     */
    public function rebuildAllSearchIndex() {
        $index_rows = SysSearchIndex::model()->findAll();
        foreach ($index_rows AS $row) {
            $conditions = json_decode($row->search_conditions, true);
            $this->buildSearchIndex($conditions);
            $row->date_last_updated = time();
            $row->save();
        }
    }

    /**
     * build index search data
     * @param array $conditions
     * @return \Zend_Search_Lucene
     */
    public function buildSearchIndex($conditions = array()) {
        $conditions = is_array($conditions) ? $conditions : array();

        $cmd = Yii::app()->db->createCommand();
        $cmd->select("(YEAR(CURDATE())-`ps`.`birthday_year`) AS age, ps.*, pl.*, p.email,
            COUNT(f.inviter_id) AS total_friends, u.username, u.alias_name, u.avatar, p.user_id, p.online_lookingfor");
        $cmd->from('usr_profile p')
                ->leftJoin('sys_votes v', 'v.user_id = p.user_id')
                ->leftJoin('usr_friendship f', '(p.user_id = f.inviter_id OR p.user_id = f.friend_id) AND (f.status = ' . YumFriendship::FRIENDSHIP_ACCEPTED . ')')
                ->join('usr_profile_settings ps', 'ps.user_id = p.user_id')
                ->join('usr_profile_location pl', 'pl.user_id = p.user_id')
                ->join('usr_user u', 'u.id = p.user_id');

        $list_users_notshow	=	isset(Yii::app()->params->list_users_notshow)	?	Yii::app()->params->list_users_notshow	:	'';
        if(!empty($list_users_notshow)){
        	$cmd->andWhere("u.status=1 AND u.id NOT IN($list_users_notshow)");
        }else{
        	$cmd->andWhere("u.status=1");
        }
        $cmd->limit =   Yii::app()->params->search_result['limit_query'];
        $cmd->setGroup('p.id');
        
        if (sizeof($conditions) > 0) {
            if (isset($conditions['filter'])) {
                foreach ($conditions['filter'] AS $fieldname => $field_value) {
                    foreach ($field_value AS $k => $v) {
                        switch ($k) {
                            case 'in':
                                $cmd->andWhere("{$fieldname} IN (" . implode(',', array_map('add_str_queryin', $v)) . ")");
                                break;
                            case 'in_set':
                            	$in_set	=	array();
                            	foreach($v AS $lv){
                            		$in_set[]	=	"FIND_IN_SET( {$lv}, `{$fieldname}` )";
                            	}
                            	$cmd->andWhere(implode(' OR ', $in_set));
                                break;                                
                            case '=':
                                $cmd->andWhere("{$fieldname} = $v");
                                break;
                            case '<':
                                $cmd->andWhere("{$fieldname} < $v");
                                break;
                            case '<=':
                                $cmd->andWhere("{$fieldname} <= $v");
                                break;
                            case '>':
                                $cmd->andWhere("{$fieldname} > $v");
                                break;
                            case '>=':
                                $cmd->andWhere("{$fieldname} >= $v");
                                break;
                            default:
                                $cmd->andWhere("{$fieldname} IN (" . implode(',', $v) . ")");
                                break;
                        }
                    }
                }
            }
            if (isset($conditions['filter_cal'])) {
                $filter_cal = array();
                foreach ($conditions['filter_cal'] AS $fieldname => $field_value) {
                    foreach ($field_value AS $k => $v) {
                        switch ($k) {
                            case 'in':
                                $filter_cal[] = "{$fieldname} IN(" . implode(',', $v) . ')';
                                break;
                            case '=':
                                $filter_cal[] = "{$fieldname}='{$v}'";
                                break;
                            case '<':
                                $filter_cal[] = "{$fieldname}<'{$v}'";
                                break;
                            case '<=':
                                $filter_cal[] = "{$fieldname}<='{$v}'";
                                break;
                            case '>':
                                $filter_cal[] = "{$fieldname}>'{$v}'";
                                break;
                            case '>=':
                                $filter_cal[] = "{$fieldname}>='{$v}'";
                                break;
                        }
                    }
                }
                $cmd->having(implode(' AND ', $filter_cal));
            }
            if (isset($conditions['sort_by'])) {
                $sort_by = array();
                foreach ($conditions['sort_by'] AS $key => $value) {
                    $sort_by[] = "FIELD({$key}, {$value} ) DESC";
                }
                $cmd->order(implode(',', $sort_by));
            }
            
        }else{
            $cmd->order('u.id DESC');
        }
        
        $dataUser = $cmd->queryAll();
        
        
        $index_file = Yii::app()->params->Lucene['index_file_dir'] . DS . $this->buildSearchIndexName($conditions);
        $index = new Zend_Search_Lucene($index_file, true);

        $i = 1;
        foreach ($dataUser AS $row) {
            $doc = new Zend_Search_Lucene_Document();
            $doc->addField(Zend_Search_Lucene_Field::Keyword('user_id', $row['user_id']));
            $doc->addField(Zend_Search_Lucene_Field::Keyword('username', $row['username']));
            $doc->addField(Zend_Search_Lucene_Field::text('total_friends', $row['total_friends']));
            $doc->addField(Zend_Search_Lucene_Field::Keyword('hobbies', $row['hobbies']));
            $doc->addField(Zend_Search_Lucene_Field::Keyword('email', $row['email']));
            $doc->addField(Zend_Search_Lucene_Field::text('current_city_id', $row['current_city_id']));
            $doc->addField(Zend_Search_Lucene_Field::text('current_district_id', $row['current_district_id']));
            $doc->addField(Zend_Search_Lucene_Field::text('current_state_id', $row['current_state_id']));
            $doc->addField(Zend_Search_Lucene_Field::text('current_country_id', $row['current_country_id']));
            $doc->addField(Zend_Search_Lucene_Field::text('birthday_year', $row['birthday_year']));
            $doc->addField(Zend_Search_Lucene_Field::text('sex_role', $row['sex_role']));
            $doc->addField(Zend_Search_Lucene_Field::Keyword('status', '1'));
            $doc->addField(Zend_Search_Lucene_Field::text('alias_name', $row['alias_name']));
            $doc->addField(Zend_Search_Lucene_Field::text('avatar', $row['avatar']));
            $doc->addField(Zend_Search_Lucene_Field::text('ordering', $i));
            $i++;
            $index->addDocument($doc);
            unset($doc);
        }
        
        $index->commit();
        return $index;
    }

    /**
     * general index search filename
     * @param array $search_conditions
     * @return string filename
     */
    public function buildSearchIndexName($search_conditions = array(), $save = true) {
        $search_conditions = is_array($search_conditions) ? $search_conditions : array();
        $search_conditions_json = '';
        if (sizeof($search_conditions) > 1) {
            $search_conditions_json = json_encode($search_conditions);
            unset($search_conditions['keyword']);

            $search_index_name = md5(json_encode($search_conditions));
        } else {
            $search_index_name = 'search_index_all';
        }

        if ($save) {
            $CDbCriteria = new CDbCriteria();
            $CDbCriteria->addCondition("index_folder='{$search_index_name}'");
            $row = SysSearchIndex::model()->find($CDbCriteria);
            if (!$row) {
                $search_index = new SysSearchIndex();
                $search_index->index_folder = $search_index_name;
                $search_index->search_conditions = $search_conditions_json;
                $search_index->date_created = time();
                $search_index->save();
            }
        }

        return $search_index_name;
    }

}

function add_str_queryin($str) {
    if($str != ''){
        return "'{$str}'";
    }
}

?>