<?php

namespace app\model\repositories;

use app\model\Repository;
use app\model\entites\Hashes;


class MessagesRepository extends Repository
{
    public function getEntityClass()
    {
        return Messages::class;
    }

    public  function getTableName()
    {
        return 'messages';
    }
}
