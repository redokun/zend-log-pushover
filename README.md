# Pushover adapter for Zend Logger ~2.5

(ZF Logger 2.5 is used in Zend Framework 2.*)

This library is in early stage and requires a bit of work to be 'production-ready', but I'm releasing it anyway.

## TODO

- Adopt a defer mechanism (queue messages and send them all in a single batch on script shutdown)
- Use Factory pattern to create the Message object, so it's easier to customize the message
- Better default message title
- Add some tests maybe

