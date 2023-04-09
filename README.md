# Analytics

A simple application for analyzing server statistics.

## Installation

- Initially, you should update the log format of your webserver to one of the following formats (we prepared log formats for Nginx and Apache). After adding this log format to your webserver configuration, you should change your virtual host's `access.log` format or the default `access.log` format to `analytics` (the format's name) and restart the webserver process.

- Clone the repository on the server that you want to watch its statistics on. install PHP dependencies by running `composer install` and node dependencies by running `npm install`.

- Set the `WEBSERVER_LOG` and `WEBSERVER_PERIOD` env variable. `WEBSERVER_LOG` is path to webserver access.log for example `/var/log/nginx/access.log`. `WEBSERVER_PERIOD` is the sleep time between each watching time.

- Set the `WEBSERVER_TIMEOUT` env variable too. if the service doesn't send any logs after taking `WEBSERVER_TIMEOUT` seconds, the service will close itself to be restarted by the supervisor. it's important because sometimes may has unexpected problems that can only solve when we restart the service.

- Install supervisor in your server and add a config it to run `php artisan server:watch` in your repository directory.

A sample supervisor config:

```
# /etc/supervisor/conf.d/watch-server.conf
[program:watch-server]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/analytics/prod/artisan server:watch
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=root
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/analytics/prod/storage/logs/worker.log
stopwaitsecs=30
```

Its important to only run one process by supervisor (set `numprocs` to one).

## Log formats

This is the log format that should use in the webserver.

### Nginx log_format

```
log_format analytics escape=json '{'
    '"host": "$host",'
    '"port": "$server_port",'
    '"remote_addr": "$remote_addr",'
    '"remote_user": "$remote_user",'
    '"time_local": "$time_local",'
    '"request": "$request",'
    '"status": "$status",'
    '"body_bytes_sent": "$body_bytes_sent",'
    '"referer": "$http_referer",'
    '"user_agent": "$http_user_agent"'
    '}';
```

### Apache LogFormat

```
LogFormat analytics "{\"host\": \"%v\",\"port\": \"%p\",\"remote_addr\": \"%a\",\"remote_user\": \"%u\",\"time_local\": \"%t\",\"request\": \"%r\",\"status\": \"%s\",\"body_bytes_sent\": \"%0\",\"referer\": \"%{Referer}i\",\"user_agent\": \"%{User-agent}i\"}""
```
