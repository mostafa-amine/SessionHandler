<?php

define('SESSION_SAVE_PATH' , dirname(realpath(__FILE__)) . DIRECTORY_SEPARATOR . "sessions");

class AppSessionHandler extends SessionHandler
{
    private string $sessionName = "MABCODESESS";
    private int $sessionMaxLifetime = 0;
    private bool $sessionSSL = False;
    private bool $sessionHTTPOnly = True;
    private string $sessionPath = '/';
    private string $sessionDomain = 'sessionhandler.test';
    private string $sessionSavePath = SESSION_SAVE_PATH;

    private string $sessionCipherAlgo = "aes-128-cbc";
    private string $sessionCipherKey = "m0staFa*2mInE@2004";
    private int $sessionCipherIV = 1234567891011121;
    private int $sessionCipherOptions = 0;

    // set ttl = time to live
    private int $ttl = 1;
    private int $sessionStartTime;

    public function __construct()
    {
        ini_set('session.use_cookies' , 1);
        ini_set('session.use_only_cookies' , 1);


        session_Name($this->sessionName);
        session_save_path($this->sessionSavePath);
        session_set_cookie_params($this->sessionMaxLifetime , $this->sessionPath , $this->sessionDomain , $this->sessionSSL , $this->sessionHTTPOnly);


        session_set_save_handler($this , true);
    }

    public function read(string $id): string
    {
        $session_data = openssl_decrypt(parent::read($id) , $this->sessionCipherAlgo , $this->sessionCipherKey , $this->sessionCipherOptions , $this->sessionCipherIV);
        return is_null($session_data) ? "" : $session_data;
    }


    public function write(string $id, string $data): bool
    {
        return parent::write($id , openssl_encrypt($data , $this->sessionCipherAlgo , $this->sessionCipherKey , $this->sessionCipherOptions , $this->sessionCipherIV));
    }

    public function __get(string $key)
    {
        return $_SESSION[$key] ?? false;
    }

    public function __set($key , $value)
    {
        $_SESSION[$key] = $value;
    }

    public function StartSession()
    {
        if(empty(session_id()))
        {
            echo "Test session!";
            session_start();
        }
        else
        {
            echo "Not yet";
        }
    }
}

$session = new AppSessionHandler();

$session->StartSession();
$session->name = "mostafa";
$session->age = 18;




