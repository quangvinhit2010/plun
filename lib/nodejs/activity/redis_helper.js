var redis = require('redis');
var db_config = require("./config/db_config.js");
var config = db_config.redis;
var client = redis.createClient();


exports.get_login_status = function(data, callback) { 
	client.get(data, function (err, reply) {
		  if(reply) {
		      //console.log('I live: ' + reply.toString());
		      //callback(reply.toString());
			  var a = reply.toString();
			  var b = a.split('__');
			  if(b[4]){
				  callback(true);
			  } else {
				  callback(false);
			  }
		  } 
		  /*else {
			  console.log('I expired');
	          client.quit();
	      }*/
	  }
	)
}	
