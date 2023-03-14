const Pusher = require('pusher-js');
const Echo = require("laravel-echo").default;

console.log(Pusher);

const options = {
	broadcaster: 'pusher',
	key: 'pusher-key',
	wsHost: '127.0.0.1',
	wsPort: 6001,
	forceTLS: false,
	disableStats: true,
	cluster: 'mt1',
}

const echo = new Echo({
    ...options,
    client: new Pusher(options.key, options)
});

const channel = echo.channel('webserver');
 
channel.listen('.log', function(data) {
   console.log(data);
});