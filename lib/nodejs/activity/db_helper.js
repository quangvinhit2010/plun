var mysql = require('mysql');
var db_config = require("./config/db_config.js");
var config_activity = db_config.db_activity;
var config_web = db_config.db_web;

var client_activity = mysql.createConnection({
	host : config_activity.MYSQL_HOST,
	user : config_activity.MYSQL_USERNAME,
	password : config_activity.MYSQL_PASSWORD,
});

var client_web = mysql.createConnection({
	host : config_web.MYSQL_HOST,
	user : config_web.MYSQL_USERNAME,
	password : config_web.MYSQL_PASSWORD,
});


function handleDisconnect(conn, config_db) {
	var new_conn = null;
    conn.on('error', function(err) {
        if (!err.fatal) {
            return;
        }

        if (err.code !== 'PROTOCOL_CONNECTION_LOST') {
            throw err;
        }
        console.log('Re-connecting lost connection: ' + err.stack);
        conn = mysql.createConnection({
        	host : config_db.MYSQL_HOST,
        	user : config_db.MYSQL_USERNAME,
        	password : config_db.MYSQL_PASSWORD,
        });
        handleDisconnect(conn);
//        conn.connect();
    });
}
handleDisconnect(client_activity, config_activity);
handleDisconnect(client_web, config_web);

client_web.debug = true;
// init;
// var client = mysql.createClient({
// host: MYSQL_HOST,
// user: MYSQL_USERNAME,
// password: MYSQL_PASSWORD,
// });

// destroy old db
// client.query('DROP DATABASE IF EXISTS mynode_db', function(err) {
// if (err) { throw err; }
// });

// create database
// client.query('CREATE DATABASE mynode_db', function(err) {
// if (err) { throw err; }
// });
client_activity.query("USE `" + config_activity.MYSQL_DBNAME + "`");
client_web.query("USE `" + config_web.MYSQL_DBNAME + "`");

// create table
/*
 * var sql = ""+ "create table employees("+ " id int unsigned not null
 * auto_increment,"+ " name varchar(50) not null default 'unknown',"+ " salary
 * dec(10,2) not null default 100000.00,"+ " primary key (id)"+ ");";
 * 
 * client.query(sql, function(err) { if (err) { throw err; } });
 *  // function to create employee exports.add_employee = function(data,
 * callback) { client.query("insert into employees (name, salary) values (?,?)",
 * [data.name, data.salary], function(err, info) { // callback function returns
 * last insert id callback(info.insertId); console.log('Employee '+data.name+'
 * has salary '+data.salary); }); }
 */

// function to get list of activities 
exports.select_newsfeed = function(data, callback) {0
	var time = new Date().getTime();
	var select = "SELECT CAST(GROUP_CONCAT(id) AS CHAR(10000) CHARACTER SET utf8) AS ids " +
			"FROM `activities`" +
			" WHERE (`timestamp` > " + data.timestamp + ") AND user_id IN (" + data.user_id + ") AND group_id = 0 AND status = 1"; 
//			+
//			" AND (CASE WHEN `action` IN ('1') THEN 1" +
//			" WHEN `action` = '2' THEN (SELECT IF(EXISTS(SELECT b.`status` FROM `"+ config_web.MYSQL_DBNAME +"`.`sys_photo` b WHERE (b.id IN (SELECT object_id FROM `activities`  WHERE group_id = t.id) OR b.id = t.object_id) AND `status` = 1), 1, 0))" +
//			" ELSE '0' END)";
	client_activity.query(select,
			function(err, results, fields) {
				// callback function returns employees array
				if(results){
					callback(results);
				} else {
					callback(err);
					
				}
			});
}

exports.select_alerts = function(data, callback) {
	var time = new Date().getTime();
	client_web.query("SELECT count(*) as total_alert, CAST(GROUP_CONCAT(id) AS CHAR(10000) CHARACTER SET utf8) AS ids FROM `"+ config_web.MYSQL_DBNAME +"`.`sys_notifications` WHERE status = 1 AND last_read = 0 and ownerid ='" + data.user_id + "'",
			function(err, results, fields) {
		// callback function returns employees array
		if(results){
			callback(results);
		} else {
			callback(err);
			
		}
	});
}

exports.select_messages = function(data, callback) {
	var time = new Date().getTime();
	client_web.query("SELECT count(*) as total_msg FROM `"+ config_web.MYSQL_DBNAME +"`.`usr_message` WHERE ((from_user_id ='" + data.user_id + "' AND message_read = 0 AND `status` != -1) OR (to_user_id ='" + data.user_id + "' AND message_read = 2 AND `status` != -2)) AND `status` != -3 AND answered = 0",
		function(err, results, fields) {
		// callback function returns employees array
		if(results){
			callback(results);
		} else {
			callback(err);
			
		}
	});
}

exports.select_friends = function(data, callback) {
	var time = new Date().getTime();
	client_web.query("SELECT count(*) as total_friend FROM `"+ config_web.MYSQL_DBNAME +"`.`usr_friendship` WHERE (friend_id ='" + data.user_id + "') AND status = 1",
		function(err, results, fields) {
		// callback function returns employees array
		if(results){
			callback(results);
		} else {
			callback(err);
			
		}
	});
}


exports.get_login_status = function(data, callback) { 
	client_activity.query("SELECT CAST(data AS CHAR(10000) CHARACTER SET utf8) AS data FROM activities_sessions WHERE id = '" + data + "' limit 1",
		function(err, results, fields) {
			if(results.length > 0){
				 var a = results[0].data;
				 var b = a.split('__');
				 if(b[4]){
					 callback(true);
				 } else {
					 callback(false); 
				 }
			} 
		});
}	


exports.get_online_list = function(data, callback) {
	var select = "tbl.user_id, u.username, u.avatar, u.alias_name, CONCAT(p.firstname, ' ', p.lastname) AS fullname, (SELECT CONCAT(path, '/thumb160x160/', `name`) FROM `"+ config_web.MYSQL_DBNAME +"`.`sys_photo` WHERE id = u.avatar AND status = 1) AS photo_avatar";
	client_activity.query("SELECT "+select+" FROM " +
	"(" +
			"SELECT inviter_id AS user_id  FROM `"+ config_web.MYSQL_DBNAME +"`.`usr_friendship` WHERE ((inviter_id = "+data.user_id+" OR friend_id="+data.user_id+") AND status = 2)" +
			"UNION ALL " +
			"SELECT friend_id AS user_id  FROM `"+ config_web.MYSQL_DBNAME +"`.`usr_friendship` WHERE ((inviter_id = "+data.user_id+" OR friend_id="+data.user_id+") AND status = 2)" +
			") AS tbl " +
			"INNER JOIN `"+ config_web.MYSQL_DBNAME +"`.`usr_user` u ON u.id = tbl.user_id " +
			"INNER JOIN `"+ config_web.MYSQL_DBNAME +"`.`usr_profile` p ON p.user_id = tbl.user_id " +
			"INNER JOIN `"+ config_activity.MYSQL_DBNAME +"`.`activities_sessions` s ON s.user_id = tbl.user_id " +
			"WHERE s.updated > (UNIX_TIMESTAMP() - 900) AND tbl.user_id NOT IN ("+data.user_id+") GROUP BY tbl.user_id",
			//"WHERE tbl.user_id NOT IN ("+data.user_id+") GROUP BY tbl.user_id",
			
		function(err, results, fields) {
			if(results){
				callback(results);
			} else {
				callback(false);
			}
		});
}	



exports.send_photo_request = function(data, callback) {
	client_web.query("SELECT count(*) as total_request FROM `"+ config_web.MYSQL_DBNAME +"`.`sys_photo_request` WHERE (request_user_id = '" + data.user_id + "' AND is_read = 4) OR (photo_user_id = '" + data.user_id + "' AND is_read = 0) OR (request_user_id = '" + data.user_id + "' AND is_read = 2)",
		function(err, results, fields) {
		// callback function returns employees array
		if(results){
			callback(results);
		} else {
			callback(err);
			
		}
	});
}


exports.getUserBySession = function(data, callback) {
	client_activity.query("SELECT s.user_id, s.expire FROM `"+ config_activity.MYSQL_DBNAME +"`.`activities_sessions` s WHERE (s.id = '" + data + "')",
		function(err, results, fields) {
		// callback function returns employees array
		if(results){
			callback(results);
		} else {
			callback(err);
			
		}
	});
}

exports.getSearchIndex = function(data, callback) { 
	client_web.query("SELECT index_folder FROM sys_search_index WHERE country_id = '" + data + "'",
		function(err, results, fields) {
			if(results){
				callback(results);
			} else {
				callback(err);
				
			}
		});
}	