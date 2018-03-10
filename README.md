# MsgPackPhp
This package tries to leverage the power of [Message Pack](https://msgpack.org/index.html) and [msgpack.php](https://github.com/rybakit/msgpack.php)

Mainly this uses https://github.com/msgpack-rpc/msgpack-rpc-php while updating the underlying code & upgrading to the above mentioned msgpack.php package.

### There are 3 possible use cases for this repository due to it's server & client perspective
 
 1. Client (my main use case)
 ```php
 $client = new Client('localhost', '1985');
 
 $messages =  $client->call("SyncJob", $job, $payload);
 
 echo array_pop($messages);

 ```
 aside from the obvious `host` and `port` arguments for the `Client` class, let's see the arguments for the call function:
 - first argument is a string naming the function to be called on the server side (see `tests/server.php`)
 - the `call` method is a variadic function, hence the following arguments are concatenated to an array on the server 

  2. Server (not thoroughly tested)
  ```php
  try {
      $server = new Server('1985', new App());
      echo 'Server is listening on port 1985...';
      $server->recv();
  } catch (Exception $e) {
      echo $e->getMessage();
  }
  ```
  see `tests/server.php` for full example
  
  3. Client and Server 
  
  this would be a good use case if you use multiple services or you need back and forth communication
  
   *if you need this between the same 2 services maybe best to take into account switching to http/2 where
     
   ```php
     $backChannel = new BackChannel();
   
     $client = new Client('localhost', '1986', $backChannel);    
     $messages =  $client->call("SyncJob", $job, $payload);    
     echo array_pop($messages);
     
     try {
         $server = new Server('1985', new App(), $backChannel);
         echo 'Server is listening on port 1985...';
         $server->recv();
     } catch (Exception $e) {
         echo $e->getMessage();
     }
   ```
   yes, in this case as it was the case for the original package, it makes sense to use the BackChannel combined
   
### Other options

The package provides necessary interfaces & Traits in order to have your own Server or Client enhancements without the need to modify the base classes whom one can mainly use as references rather than implementations as it makes only for the most basic use case. 
