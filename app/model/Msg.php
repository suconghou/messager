<?php


class Msg extends db
{


    static function t(string $t)
    {
        return "message_{$t}";
    }

    static function create(string $t, array $data)
    {
        $table = self::t($t);
        return self::insert($data, $table);
    }

    static function getList(string $table, string $ns, string $thread)
    {
        $where = [
            'ns' => $ns,
            'thread' => $thread,
        ];
        $table = self::t($table);
        $orderLimit = [];
        $col = 'id,pid,name,email,url,avatar,content,time';
        $data = self::find($where, $table, $col, $orderLimit);
        return $data;
    }
}
