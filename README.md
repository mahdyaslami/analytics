# Analytics

An simple application for analyzing database

## Nginx log_format

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

## Apache LogFormat

```
LogFormat analytics "{\"host\": \"%v\",\"port\": \"%p\",\"remote_addr\": \"%a\",\"remote_user\": \"%u\",\"time_local\": \"%t\",\"request\": \"%r\",\"status\": \"%s\",\"body_bytes_sent\": \"%0\",\"referer\": \"%{Referer}i\",\"user_agent\": \"%{User-agent}i\"}""
```
