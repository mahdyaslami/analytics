# Analytics

An simple application for analyzing database

## Nginx log_format

```
log_format analytics escape=json '{'
    '"request_id": "$request_id",'
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

## Apache LogFormat

We need enable unique_id module at the begining:

```
$ a2enmod unique_id
```

```
LogFormat analytics "{\"request_id\": \"%{uniqueid}i\",\"host\": \"%v\",\"port\": \"%p\",\"remote_addr\": \"%a\",\"remote_user\": \"%u\",\"time_local\": \"%t\",\"request\": \"%r\",\"status\": \"%s\",\"body_bytes_sent\": \"%0\",\"referer\": \"%{Referer}i\",\"user_agent\": \"%{User-agent}i\"}""
```

