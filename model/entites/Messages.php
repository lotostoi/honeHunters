<?php

namespace app\model\entites;

use app\model\Model;

class Messages extends Model
{
    protected $id;
    protected $name;
    protected $email;
    protected $message;

    public function __construct($name = null, $email = null, $message = null)
    {
        parent::__construct();
        $this->name = $name;
        $this->email = $email;
        $this->message = $message;
    }
}
