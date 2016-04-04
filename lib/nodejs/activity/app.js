var fs = require('fs');
var main_config = require("./config/main.js");
var config = main_config.main;
var db_helper = require("./db_helper.js");
//var redis_helper = require("./redis_helper.js");

var http = require('http').createServer();
//http.listen(config.PORT, config.HOST);
http.listen(config.PORT);

var io = require('socket.io').listen(http
		, { log: false } /* false: hide debug */		
);

/**MSG Realtime***/
var people = {};
var clients = {};
/**MSG Realtime***/

/**Notification by elasticsearch***/

var elastic_config = main_config.elastic;
/*
var ElasticClient = require(elastic_config.PATH);

var elastic = new ElasticClient({
  host:   elastic_config.HOST 
, port:   elastic_config.PORT
, index:  elastic_config.INDEX
});
*/

var elasticsearch = require(elastic_config.PATH);
var elastic = new elasticsearch.Client({
  host: elastic_config.HOST+':'+elastic_config.PORT,
//  log: 'trace'
});
/**Notification by elasticsearch***/

io.sockets.on('connection', function(client) {
	console.log('Client connected');
		
	/*
	client.on('get_newsfeed', function(data) {
		db_helper.select_newsfeed(data, function(activity) {
			client.volatile.emit('get_newsfeed', activity);
		});
	});
	
	client.on('get_alerts', function(data) {		
		db_helper.select_alerts(data, function(activity) {
			client.volatile.emit('get_alerts', activity);
		});
	});
	
	client.on('get_messages', function(data) {
		db_helper.select_messages(data, function(activity) {
			client.volatile.emit('get_messages', activity);
		});
	});
	
	client.on('get_friends', function(data) {
		db_helper.select_friends(data, function(activity) {
			client.volatile.emit('get_friends', activity);
		});
	});
	
	client.on('send_photo_request', function(data) {
		db_helper.send_photo_request(data, function(result) {
			client.volatile.emit('get_photo_alerts', result);
		});
	});
	 */
	client.on('update_last_activity', function(uid) {
		var unix = Math.round(+new Date().getTime()/1000);
		if(typeof(people[client.id]) !== "undefined" && typeof(people[client.id].uid) !== "undefined"){
			var current_country_id = people[client.id]._source.current_country_id;
			if(typeof(current_country_id) !== "undefined"){
				db_helper.getSearchIndex(current_country_id, function(result) {
					if(result){
						result.forEach(function(value, index) { 
							if(value.index_folder){
								elastic.update({ index: value.index_folder, type: value.index_folder, id: uid, body: { doc: { last_activity: unix } } }, function (error, response) {
								});					
							}
						});
					}
//					client.volatile.emit('get_online_list', result);
				});
			}
			elastic.update({ index: elastic_config.INDEX, type: elastic_config.INDEX, id: uid, body: { doc: { last_activity: unix } } }, function (error, response) {					
			});
		}
	});
	
	client.on('send_online_list', function(data) {
		
				
		/*
		db_helper.get_online_list(data, function(reply) {
			client.volatile.emit('get_online_list', reply);
		});
		*/
		
	});
	
	client.on('em_notifications', function(data) {
//		console.log(clients);
//		console.log(clients[people[client.id]]);
		if(typeof(people[client.id]) !== "undefined" && typeof(people[client.id].uid) !== "undefined"){
			var _username = people[client.id].username;
			people[client.id].update_activity_time = data.update_activity_time;
			Nodejs.elasticGetProfile(people[client.id].uid, client);
//			io.sockets.sockets[client.id].emit('get_messages', [{total_msg: people[client.id]._source.total_message}]);
//			io.sockets.sockets[client.id].emit('get_alerts', [{total_alert: people[client.id]._source.total_alert}]);
//			io.sockets.sockets[client.id].emit('get_friends', [{total_friend: people[client.id]._source.total_addfriend_request}]);
//			io.sockets.sockets[client.id].emit('get_photo_alerts', [{total_request: people[client.id]._source.total_photo_request}]);
			
//			io.sockets.sockets[client.id].emit('on_notifications', {
//					total_msg: people[client.id]._source.total_message,
//					total_alert: people[client.id]._source.total_alert,
//					total_friend: people[client.id]._source.total_addfriend_request,
//					total_request: people[client.id]._source.total_photo_request,
//			});
		}
	});
	
	setInterval(function() {	
		if(typeof(people[client.id]) !== "undefined" && typeof(people[client.id].uid) !== "undefined" && typeof(io.sockets.sockets[client.id]) !== "undefined"){
			var _username = people[client.id].username;
			io.sockets.sockets[client.id].emit('on_notifications', {
					total_msg: people[client.id]._source.total_message,
					total_alert: people[client.id]._source.total_alert,
					total_friend: people[client.id]._source.total_addfriend_request,
					total_request: people[client.id]._source.total_photo_request,
			});
			
			/** online list**/
			var unix = Math.round(+new Date().getTime()/1000);	
			var online_list, 
				return_data = {status:false}; 
			var fl = people[client.id]._source.friendlist;
			var arr = fl.split(",");		
			elastic.search({
			  index: elastic_config.INDEX,
			  body: {
			  	fields: ['user_id', 'last_activity', 'alias_name', 'avatar'],
			    query: {
//						      match: {
//						        last_activity: '1415779632'
//						      },
			      filtered : {
			            filter : {
					    	bool : {
					    		should: [
				    		         {terms : { user_id: arr}}
			    		         ],					            		
			                	must_not : {
			                        term : {user_id : people[client.id].uid} 
			                     }
			                }					                
			            }
			        },		
			        
			    },
			    filter: {					    	
			    	range: {
			    		last_activity: {
			    			gte: (unix - people[client.id].update_activity_time),
			    			lte: unix,
			    		}
			    	},
			    },
			    sort: { last_activity: { "order": "desc" }}
			  },				  
			  
			}, function (error, response) {
				if(typeof(response.hits) !== "undefined" && typeof(response.hits.hits) !== "undefined"){
					online_list = response.hits.hits;
				}
				if(typeof(online_list) !== "undefined"){
					var arrProfile = [];
					online_list.forEach(function(value, index) { 
						arrProfile.push(value.fields);
					});
					return_data = {list: arrProfile,status:true};							
					io.sockets.sockets[client.id].emit('get_online_list', return_data);
				}
			  // ...
			});
					
			/** online list**/
		}
 	}, 5000);
	

	/**MSG Realtime***/
	client.on("auth", function(token){
//		console.log(token);
		db_helper.getUserBySession(token, function(result) {
			if(result[0]){
				var expire = result[0].expire;
				var user_id = result[0].user_id;
				if(typeof user_id !== "undefined"){
					Nodejs.elasticGetProfile(user_id, client);
//					io.sockets.sockets[client.id].emit("gettoken", userName);
				}
				
			}
		});
    });
	
	client.on("join", function(userName){
//		console.log(clients[userName]);
		if(!clients[userName]){
			clients[userName] = [];
		}
		clients[userName].push({cid: client.id});
//		console.log('joined_____' + client.id + '__________' + Nodejs.generateUUID());
//		console.log(clients);
		people[client.id] = userName;
		io.sockets.emit("update-people", people);
	});

    client.on("send", function(msg, userName, group){
//    	console.log('send ' + userName + '__' + people[client.id] ); 
    	for(var index in clients[userName]) {
//    		console.log('1_____'+io.sockets.sockets[clients[userName][index].cid]);
    		if (typeof io.sockets.sockets[clients[userName][index].cid] !== "undefined") {
//    			console.log('2_____'+io.sockets.sockets[clients[userName][index].cid]);
    			io.sockets.sockets[clients[userName][index].cid].emit("chat", people[client.id], group, msg);    			
    		}
		}
    	return false;
    });
    /**MSG Realtime***/
	client.on('disconnect', function() {
		/**MSG Realtime***/
		io.sockets.emit("update", people[client.id] + " has left the server.");
        delete people[client.id];
        io.sockets.emit("update-people", people);
//        console.log('_______1111111111____');
        for(var index in clients) {
//        	console.log(clients[index]);
        	for(var indx in clients[index]) {
        		if(typeof clients[index][indx] !== "undefined" && clients[index][indx].cid == client.id){
//        			console.log('fuck!!!!!!');
//        			delete clients[index][indx];
        			clients[index].splice(indx, 1);
        		}
        	}
        }
//        console.log('disconnect____'+client.id);
//        console.log('_______222222222222____');
		/**MSG Realtime***/
	});
});

var Nodejs = {
	generateUUID: function () {
		var d = new Date().getTime();
		var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
			var r = (d + Math.random()*16)%16 | 0;
			d = Math.floor(d/16);
			return (c=='x' ? r : (r&0x7|0x8)).toString(16);
		});
		return uuid;
	},
	elasticGetProfile: function (user_id, client) {		
		if(typeof user_id !== "undefined"){
			elastic.get({index: elastic_config.INDEX,type: elastic_config.INDEX,id: user_id}, function (error, response) {
				if(typeof response !== "undefined"){
					if(!people[client.id]){	
						var userName = response._source.username;
						if(!clients[userName]){
							clients[userName] = [];
						}
						clients[userName].push({cid: client.id});
						people[client.id] = {'username': userName, 'uid': user_id, '_source': {
							'total_message': response._source.total_message,
							'total_alert': response._source.total_alert,
							'total_addfriend_request': response._source.total_addfriend_request,
							'total_photo_request': response._source.total_photo_request,
							'friendlist': response._source.friendlist,
							'current_country_id': response._source.current_country_id,
						}};
					}else{
						people[client.id]._source = response._source;
					}
					if(typeof(io.sockets.sockets[client.id]) !== "undefined"){						
						io.sockets.sockets[client.id].emit('on_setuser', {'uid': people[client.id].uid, 'lastactivity': people[client.id]._source.last_activity});
					}
				}
			});
		}
	},
}
