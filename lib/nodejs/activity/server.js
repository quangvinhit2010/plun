var fs = require('fs');
var main_config = require("./config/main.js");
var config = main_config.main;
var db_helper = require("./db_helper.js");
//var redis_helper = require("./redis_helper.js");

var http = require('http').createServer();
//http.listen(config.PORT, config.HOST);
http.listen(config.PORT);

var io = require("socket.io");  
var socket = io.listen(http
		, { log: false } /* false: hide debug */		
);
var people = {};
var clients = {};

socket.sockets.on('connection', function(client) {  
	console.log('connected!');
    client.on("join", function(userName){
    	console.log(clients[userName]);
    	if(!clients[userName]){
    		clients[userName] = [];
    	}
    	clients[userName].push({cid: client.id});
    	console.log('joined_____' + client.id);
    	console.log(clients);
        people[client.id] = userName;
        client.emit("update", "You have connected to the server.");
        socket.sockets.emit("update", userName + " has joined the server.")
        socket.sockets.emit("update-people", people);
    });

    client.on("send", function(msg, userName){
    	console.log('send ' + userName + '__' + people[client.id] + '____' + clients[userName]); 
    	for(var index in clients[userName]) { 
		   socket.sockets.sockets[clients[userName][index].cid].emit("chat", people[client.id], msg);
		}
    	return false;
    });

    client.on("disconnect", function(){
    	socket.sockets.emit("update", people[client.id] + " has left the server.");
        delete people[client.id];
        socket.sockets.emit("update-people", people);
    });
});
