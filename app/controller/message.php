<?php

/**
 * 对外接口
 */
class message
{
    private const iv = '4567890abcdef123';
    private static $fns = ['login', 'list', 'create'];

    ############### 网站管理 #############

    function sitelist()
    {
        $uid = u::getUidAjax();
    }

    function siteupdate()
    { }

    function sitedelete()
    { }


    ############# 消息管理 ##################







    ################# 组件接口 ###################

    function ajax($action = null)
    {
        try {
            if (in_array($action, self::$fns)) {
                return call_user_func_array([$this, $action], func_get_args());
            } else {
                return json(['code' => -9, 'msg' => 'error action']);
            }
        } catch (Exception | Error $e) {
            app::json($e);
        }
    }

    /** 
     * jwt 派发,根据信息签发一个jwt匿名用户
     */
    private function login()
    {
        $rule = [
            'name' => ['require' => '昵称必填', 'maxlength=30' => '您的昵称也太长了吧~'],
            'email' => ['require' => '邮箱必填', 'maxlength=50' => '这邮箱也太长了吧', 'email' => '请填写正确的邮箱格式呦'],
            'url' => ['maxlength=100' => '网址太长啦', 'url' => '网址格式不正确'],
        ];
        $info = request::verify($rule, true, request::input());
        $data = self::jwtencode($info);
        return json(['code' => 0, 'msg' => 'ok', 'data' => $data]);
    }

    /**
     * 查询接口
     * 
     */
    private function list()
    {
        $rule = [
            'area' => ['default' => 'default', 'eq=default' => '区域类型错误'],
            'ns' => ['require' => '站点必填'],
            'thread' => ['require' => '关联ID必填'],
            'tree' => ['default' => 1, "tree值只能为0或1" => [0, 1, '0', '1']],
        ];
        list('area' => $table, 'ns' => $ns, 'thread' => $thread, 'tree' => $tree) = request::verify($rule, true, request::input());
        $list = Msg::getList($table, $ns, $thread);
        $count = count($list);
        if ($tree) {
            $list = self::tree($list);
        }
        return json(['code' => 0, 'count' => $count, 'msg' => 'ok', 'data' => $list]);
    }


    /** 
     * 实名,匿名,统一接口
     */
    private function create()
    {
        $rule = [
            'area' => ['default' => 'default', 'eq=default' => '区域类型错误'],
            'ns' => ['require' => '站点必填'],
            'thread' => ['require' => '关联ID必填'],
            'content' => ['require' => '内容必填', 'maxlength=2000' => '如此长篇大论可不好哦!'],
            'pid' => ['require' => '父级必填'],
            'jwt' => ['require' => '认证信息必填', 'minlength=20' => '认证信息错误', 'maxlength=200' => '认证信息错误']
        ];
        $data = request::verify($rule, true, request::input());
        $info = self::jwtdecode($data['jwt']);
        $data['name'] = $info['name'];
        $data['email'] = $info['email'];
        $data['url'] = $info['url'] ?? '';
        $ret = self::doCreate($data);
        if ($ret) {
            return json(['code' => 0, 'msg' => '评论已提交']);
        }
        return json(['code' => -2, 'msg' => '评论失败']);
    }

    private static function doCreate(array $data)
    {
        $area = $data['area'];
        unset($data['area'], $data['jwt']);
        $data['avatar'] = md5($data['email']);
        $data['ip'] = request::ip();
        $data['ua'] = request::ua();
        $data['time'] = time();
        return Msg::create($area, $data);
    }

    private static function tree(array $items)
    {
        $items = array_column($items, null, 'id');
        $tree = [];
        foreach ($items as $item) {
            if (isset($items[$item['pid']])) {
                $items[$item['pid']]['child'][] = &$items[$item['id']];
            } else {
                $tree[] = &$items[$item['id']];
            }
        }
        return ['num' => count($tree), 'data' => $tree];
    }

    private static function jwtencode(array $data)
    {
        $data['expire'] = time() + 604800;
        $str = base64_encode(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $domain = 'message';
        $signInfo = md5($str) . '|' . $domain;
        $sign = self::aesencode($signInfo);
        return $str . '.' . $sign;
    }

    private static function jwtdecode(string $jwt)
    {
        $arr = explode('.', $jwt);
        if (count($arr) !== 2) {
            throw new Exception("invalid jwt token", -4);
        }
        list($infostr, $aesdata) = $arr;
        $info = json_decode($infostr, true);
        $signarr = explode('|', self::aesdecode($aesdata));
        if (count($signarr) !== 2) {
            throw new Exception("invalid jwt token", -5);
        }
        list($sign, $domain) = $signarr;
        if ($domain !== 'message') {
            throw new Exception("invalid jwt token", -6);
        }
        if (md5($infostr) !== $sign) {
            throw new Exception("invalid jwt token", -7);
        }
        if (empty($info['expire']) || $info['expire'] < time()) {
            throw new Exception("expired jwt token", -8);
        }
        return $info;
    }

    private static function aesencode(string $str)
    {
        $data = openssl_encrypt($str, "AES-128-CFB", self::iv, OPENSSL_RAW_DATA, self::iv);
        return base64_encode($data);
    }


    private static function aesdecode(string $encrypt)
    {
        $str = base64_decode($encrypt);
        $data = openssl_decrypt($str, "AES-128-CFB", self::iv, OPENSSL_RAW_DATA, self::iv);
        return $data;
    }
}
