<?php

use think\Model;
/**
 * 支付 逻辑定义
 * Class
 * @package Home\Payment
 */

class alipay extends Model
{
    function notify() {
        $aop = new AopClient;
        $aop->alipayrsaPublicKey = '请填写支付宝公钥，一行字符串';
        $flag = $aop->rsaCheckV1($_POST, NULL, "RSA");
        \think\Log::write('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
        \think\Log::write($flag);
        \think\Log::write($_POST);
        \think\Log::write('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
        if($flag) {

        }
    }
    function getApp($order, $notify_url)
    {
        $conf = C('alipay');
        $aop = new AopClient;
        $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $aop->appId = $conf['appId'];
        $aop->rsaPrivateKey = $conf['rsaPrivateKey'];
        $aop->format = "json";
        $aop->charset = "UTF-8";
        $aop->signType = "RSA";
        $aop->alipayrsaPublicKey = $conf['alipayrsaPublicKey'];
        $request = new AlipayTradeAppPayRequest();
        $bizcontent = "{\"body\":\"我是测试数据\","
            . "\"subject\": \"App支付测试\","
            . "\"out_trade_no\": \"20170125test01\","
            . "\"timeout_express\": \"30m\","
            . "\"total_amount\": \"0.01\","
            . "\"product_code\":\"QUICK_MSECURITY_PAY\""
            . "}";
        $request->setNotifyUrl($notify_url);
        $request->setBizContent($bizcontent);
        $response = $aop->sdkExecute($request);
        return array('orderInfo' => htmlspecialchars($response));
    }

    function  __construct() {
        /**
         * 定义常量开始
         * 在include("AopSdk.php")之前定义这些常量，不要直接修改本文件，以利于升级覆盖
         */
        /**
         * SDK工作目录
         * 存放日志，AOP缓存数据
         */
        if (!defined("AOP_SDK_WORK_DIR"))
        {
            define("AOP_SDK_WORK_DIR", "/tmp/");
        }
        /**
         * 是否处于开发模式
         * 在你自己电脑上开发程序的时候千万不要设为false，以免缓存造成你的代码修改了不生效
         * 部署到生产环境正式运营后，如果性能压力大，可以把此常量设定为false，能提高运行速度（对应的代价就是你下次升级程序时要清一下缓存）
         */
        if (!defined("AOP_SDK_DEV_MODE"))
        {
            define("AOP_SDK_DEV_MODE", true);
        }
        /**
         * 定义常量结束
         */

        /**
         * 找到lotusphp入口文件，并初始化lotusphp
         * lotusphp是一个第三方php框架，其主页在：lotusphp.googlecode.com
         */
        $lotusHome = dirname(__FILE__) . DIRECTORY_SEPARATOR . "lotusphp_runtime" . DIRECTORY_SEPARATOR;
        include($lotusHome . "Lotus.php");
        $lotus = new Lotus;
        $lotus->option["autoload_dir"] = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'aop';
        $lotus->devMode = AOP_SDK_DEV_MODE;
        $lotus->defaultStoreDir = AOP_SDK_WORK_DIR;
        $lotus->init();
    }

}
