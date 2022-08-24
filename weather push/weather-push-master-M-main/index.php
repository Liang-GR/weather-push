<?php
    /**
     * 
     */
    class start
    {
        
        public function yanzheng(){
            $nonce = $_GET['nonce'];
            $token = '你的token';
            $timestamp=$_GET['timestamp'];
            $echostr = $_GET['echostr'];
            $signature = $_GET['signature'];
            $array = array($nonce,$timestamp,$token);
            sort($array);
    
            $str = sha1(implode($array));
            if($str == $signature && $echostr){
                //第一次接入weixin api 接口的时候
                echo $echostr;
                exit;
            }else{
                
            }
        }
        public function do_sth(){
            $config = [
                # 微信公众号配置
                "app_id"=>"你的appid",
                "app_secret"=>"你的app密钥",
                "template_id"=> "模板id",
                "user"=> ["用户id"], # 接收消息的微信号，多个微信用英文逗号间隔，例如["wx1", "wx2"]
                # 信息配置
                "weather_key"=>"天气key",
                "region"=>"101040100", # 所在地区，可为省，城市，区，县，同时支持国外城市，例如伦敦
                "region_name"=>'重庆市',
                "birthday1"=> ["name"=> "名字1", "birthday"=>"2002-06-06"], # 生日1
                "birthday2"=> ["name"=> "名字2", "birthday"=> "2002-08-09"], # 生日2，同上
                "love_date"=> "2020-07-09", # 在一起的日期
                "cusinfo"=> "今天也是元气满满的一天啊！！！",#自定义语句，可以放一些你想说的话
                "note_ch"=> "", # 金句中文，如果设置了，则会显示这里的，如果为空，默认会读取 一言Api内的文字
            ];

            //获取今天日期
            $weekarray=array("日","一","二","三","四","五","六");
            $day= time();
            $date = date("Y-m-d").' '."星期".$weekarray[date("w",$day)];

            $access_token = $this->get_access_token($config['app_id'],$config['app_secret']);
            $weathers = $this->get_weather($config['region'],$config['weather_key']);
            if($config['note_ch']==''){
              $note_ch = $this->get_note();
            }else{
              $note_ch = $config['note_ch'];
            }


            $love_days = $this->get_days($config['love_date']);
            $birthday1 = $this->get_birthday($config['birthday1']['birthday']);
            $birthday2 = $this->get_birthday($config['birthday2']['birthday']);

            for($i=0;$i<count($config['user']);$i++){
                $this->send_vx($config['user'][$i],$config,$date,$love_days,$birthday1,$birthday2,$access_token,$weathers,$note_ch);
            }
        }
        function send_vx($to_user,$config,$date,$love_days,$birthday1,$birthday2,$access_token,$weathers,$note_ch){
            $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
            $datas = [
                "touser"=> $to_user,
                "template_id"=> $config['template_id'],
                "url" =>"http://154.221.18.154/reward-master/index.html",
                "topcolor"=> "#FF0000",
                "data"=> [
                    "date"=> [
                        "value"=> $date,
                        "color"=> '#565A80'
                    ],
                    "cusinfo"=> [
                        "value"=> $config['cusinfo'],
                        "color"=> '#F08080'
                    ],
                    "region"=> [
                        "value"=> $config['region_name'],
                        "color"=> '#2A7A53'
                    ],
                    "weather"=> [
                        "value"=> $weathers['weather'],
                        "color"=> '#835A44'
                    ],
                    "temp"=> [
                        "value"=>$weathers['temp'],
                        "color"=> '#8DAD61'
                    ],
                    "wind_dir"=> [
                        "value"=> $weathers['wind_dir'],
                        "color"=> '#A2B866'
                    ],
                    "love_day"=> [
                        "value"=> $love_days,
                        "color"=> '#EA8591'
                    ],
                    "birthday1"=>[
                        "value"=> $this->format_birthday($birthday1,$config['birthday1']),
                        "color"=> '#66CF47'
                    ],
                   "birthday2"=>[
                   "value"=> $this->format_birthday($birthday2,$config['birthday2']),
                   "color"=> '#7626E9'
                    ],
                    "note_ch"=> [
                        "value"=> $note_ch,
                        "color"=> '#53AD77'
                    ]
                ]
            ];
            $json_data = json_encode($datas);
            $is_ok = json_decode($this->post($url,$json_data),true);
            var_dump($is_ok);
        }
        function format_birthday($days,$infos){
            if($days=='365'||$days=='366'){
                return '今天是 '.$infos['name'].' 的生日哦~祝 '.$infos['name'].' 生日快乐！';
            }else{
                return '距离 '.$infos['name'].' 的生日还有 '.$days.'天！';
            }
        }
        //获取天数
        function get_days($oldtime,$type=1){
            $old = strtotime($oldtime);
            $now = time();
            if($type==1){
                $seconds = $now-$old;
            }else{
                $seconds = $old-$now;
            }
            return  floor($seconds/86400);
        }
        //获取距离生日天数
        function get_birthday($time){
            $times = explode('-',$time);
            $year = $times[0];
            $month = $times[1];
            $day = $times[2];
            
            $cur_y = date('Y'); //4位数字完整表示的年份
            
            $cur_m = date('n'); //数字表示的月份，没有前导零,1~12
            
            $cur_d = date('j'); //月份中的第几天，没有前导零,1~31
            
            //计算学生从出生到当前年的周岁
            
            $age = $cur_y - $year;
            
            //判断是否已过生日
            
            if($cur_m < $month || $cur_m==$month && $cur_d<$day){
                $age--;
            }
            $ca_age = ($year+$age) + 1;
            $days = $this->get_days($ca_age.'-'.$month.'-'.$day,2);
             
            return $days+1;
        }
        function get_access_token($app_id,$app_secret){
            $post_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$app_id."&secret=".$app_secret;
            
            $access_token = json_decode($this->get($post_url),true);
    
            return $access_token['access_token'];
        }
        
        function get_weather($region,$key){
            $region_url = "https://devapi.qweather.com/v7/weather/now?location=".$region."&key=".$key;
            $response = json_decode($this->get($region_url),true);

            $data=[
                'weather' => $response["now"]["text"],
                'temp' =>$response["now"]["temp"].'°C',
                'wind_dir'=>$response["now"]["windDir"],
            ];
            return $data;
        }
        function get_note(){
            $post_url = "https://v1.hitokoto.cn/?c=".$this->get_random_type();
            
            $notes = json_decode($this->get($post_url),true);
    
            return $notes['hitokoto'];
        }
        
        function get_random_type(){
            // a	动画
            // b	漫画
            // c	游戏
            // d	文学
            // e	原创
            // f	来自网络
            // g	其他
            // h	影视
            // i	诗词
            // j	网易云
            // k	哲学
            // l	抖机灵
            // 其他	作为 动画 类型处理
            $randoms = ['a','b','c','d','e','f','g','h','i','j','k','l'];
            
            $random_num = $this->NoRand(0,11,1)[0];

            //可直接return想要的分类 如： 
            //return 'a';
            return $randoms[$random_num];
        }
        function NoRand($begin=1,$end=5,$limit=3){
            $rand_array=range($begin,$end);
            shuffle($rand_array);//调用现成的数组随机排列函数
            return array_slice($rand_array,0,$limit);//截取前$limit个
        }
         function get($url,$header=[]){
            $curl = curl_init();
            if($header){
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $url,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_SSL_VERIFYPEER=>false,
                  CURLOPT_SSL_VERIFYHOST=>false,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'GET',
                  CURLOPT_HTTPHEADER => $header
                ));
            }else{
                curl_setopt_array($curl, array(
                  CURLOPT_URL => $url,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_SSL_VERIFYPEER=>false,
                  CURLOPT_SSL_VERIFYHOST=>false,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'GET',
                ));
            }
            
            $response = curl_exec($curl);
            
            curl_close($curl);
            return $response;
        }
        /*
         * post method
         */
        function post($url, $param){
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_SSL_VERIFYPEER=>false,
              CURLOPT_SSL_VERIFYHOST=>false,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => $param,
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
            return $response;
        }
    }
    
    $start = new start;
    $types = $_GET['types'];
    if($types=='yanzheng'){
        $start->yanzheng();
    }else if($types=='do'){
        $start->do_sth();
    }else{
        echo('请传入types参数');
    }
    
    
    
    
    
    
    
    
    