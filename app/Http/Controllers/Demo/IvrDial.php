<?php
namespace App\Http\Controllers\Demo;
/*
 *  Copyright (c) 2014 The CCP project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a Beijing Speedtong Information Technology Co.,Ltd license
 *  that can be found in the LICENSE file in the root of the web site.
 *
 *   http://www.yuntongxun.com
 *
 *  An additional intellectual property rights grant can be found
 *  in the file PATENTS.  All contributing project authors may
 *  be found in the AUTHORS file in the root of the source tree.
 */

use App\Http\Controllers\SDK\REST;

class ivrDial
{

//主帐号
public  $accountSid = '8a216da86bb6838f016bcf0cb2000dba';

//主帐号Token
    public  $accountToken = '8a15411472fc412990cf188c842226b9';

//应用Id
    public $appId = '8a216da86bb6838f016bcf0cb2560dc1';

//请求地址，格式如下，不需要写https://
    public $serverIP = 'localhost:8085';

//请求端口
    public $serverPort = '8883';

//REST版本号
    public $softVersion = '2013-12-26';

    /**
     * IVR外呼
     * @param number   待呼叫号码，为Dial节点的属性
     * @param userdata 用户数据，在<startservice>通知中返回，只允许填写数字字符，为Dial节点的属性
     * @param record   是否录音，可填项为true和false，默认值为false不录音，为Dial节点的属性
     */
  public  function ivrDial($number, $userdata, $record)
    {
        // 初始化REST SDK
        global $accountSid, $accountToken, $appId, $serverIP, $serverPort, $softVersion;
        $rest = new REST($serverIP, $serverPort, $softVersion);
        $rest->setAccount($accountSid, $accountToken);
        $rest->setAppId($appId);

        // 调用IVR外呼接口
        $result = $rest->ivrDial($number, $userdata, $record);
        $data=[];
        if ($result == NULL) {
            $data['status']='1';
            $data['content'] = "result error!";
        }
        if ($result->statusCode != 0) {
            $data['status']='2';
            $data['content']=[
                'ErrorCode'=>$result->statusCode,
                'ErrorMsg'=>$result->statusMsg
            ];
            //TODO 添加错误处理逻辑
        } else {
            $data['status']='3';
            //获取返回信息
            $data['content']= $result->callSid;
            //TODO 添加成功处理逻辑
        }
        return $data;
    }
}
//Demo调用,参数填入正确后，放开注释可以调用
//ivrDial("待呼叫号码","用户数据","是否录音");
?>
