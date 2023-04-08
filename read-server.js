require('dotenv').config()

const Pusher = require('pusher-js')
const Echo = require('laravel-echo').default

const options = {
    broadcaster: 'pusher',
    key: process.env.PUSHER_APP_KEY,
    wsHost: process.argv[2],
    wsPort: process.env.PUSHER_PORT,
    forceTLS: process.env.PUSHER_SCHEME == 'https',
    disableStats: true,
    cluster: process.env.PUSHER_APP_CLUSTER,
}

const echo = new Echo({
    ...options,
    client: new Pusher(options.key, options),
})

const channel = echo.channel('webserver')

channel.listen('.log', (data) => {
    console.info(data.message)
})

echo.connector.pusher.connection.bind('disconnected', () => echo.disconnect());
echo.connector.pusher.connection.bind('unavailable', () => echo.disconnect());