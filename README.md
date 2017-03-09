# Pushover adapter for Zend Logger ~2.5

(ZF Logger 2.5 is used in Zend Framework 2.*)

Send application log messages to [Pushover](https://pushover.net)

Example (send only CRIT errors and above to Pushover):


    $pushoverWriter = new \ZendLogPushover\Writer\Pushover();
    $pushoverWriter->setUser(new \Pushy\User("userKey"));
    $pushoverWriter->setClient(new \Pushy\Client("clientKey"));
    
    $pushoverWriter->addFilter(\Zend\Log\Logger::CRIT);
    
    // Add writer to zf logger
    $logger = new \Zend\Log\Logger();
    $logger->addWriter($pushoverWriter);

## TODO

- Adopt a defer mechanism (queue messages and send them all in a single batch on script shutdown)
- Better default message title

