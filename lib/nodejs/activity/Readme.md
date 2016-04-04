Description:
Nodejs - Realtime activity and login status

Tags: nodejs, socketio, mysql, redis

### installation:
Run command: npm install socket.io mysql redis


### run this command in your terminal:
```
node app.js
```

### open in your browser:
```
  http://localhost:3000/
```



Create folder config:

Create file: main.js:
exports.main = {
		HOST: 'localhost',
		PORT: '3000',
		
};


###

Create file: db_config.js:

exports.db_activity = {
  MYSQL_HOST: 'localhost',
  MYSQL_USERNAME: 'root',
  MYSQL_PASSWORD: 'root',
  MYSQL_DBNAME: 'plun.asia_activity',
};

exports.db_web = {
	MYSQL_HOST: 'localhost',
	MYSQL_USERNAME: 'root',
	MYSQL_PASSWORD: 'root',
	MYSQL_DBNAME: 'plun.asia',
};


exports.redis = {
  REDIS_HOST: 'localhost',
  REDIS_PORT: 6379,
  REDIS_DB: 0,
};
