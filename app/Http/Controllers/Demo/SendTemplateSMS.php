<?php
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
namespace App\Http\Controllers\Demo;
use App\Http\Controllers\SDK\REST;
header('');
class SendTemplateSMS
{
//主帐号
public $accountSid = '8a216da86bb6838f016bcf0cb2000dba';

//主帐号Token
public $accountToken = '8a15411472fc412990cf188c842226b9';

//应用Id
public $appId = '8a216da86bb6838f016bcf0cb2560dc1';

//请求地址，格式如下，不需要写https://
public $serverIP ='app.cloopen.com';

//请求端口
public $serverPort = '8883';

//REST版本号
public $softVersion = '2013-12-26';


    /**
     * 发送模板短信
     * @param to 手机号码集合,用英文逗号分开
     * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
     * @param $tempId 模板Id
     */
   public function sendTemplateSMS($to, $datas, $tempId)
    {
        // 初始化REST SDK
        $rest = new REST($this->serverIP, $this->serverPort, $this->softVersion);
        $rest->setAccount($this->accountSid, $this->accountToken);
        $rest->setAppId($this->appId);

        // 发送模板短信
        $result = $rest->sendTemplateSMS($to, $datas, $tempId);
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
            // 获取返回信息
            $data['status']='3';
            //获取返回信息
            $data['content']= "Sendind TemplateSMS success!";
            //TODO 添加成功处理逻辑
        }
        return $data;
    }
}

//Demo调用,参数填入正确后，放开注释可以调用
//sendTemplateSMS("手机号码","内容数据","模板Id");
?>
