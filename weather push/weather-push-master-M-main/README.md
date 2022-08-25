# weather-push-master
微信公众号天气推送
/*
*   微信公众号搜索 “铁阿呆”（sr_liang36）
*   抖音：阿呆是小梁
*/

调试效果：

![Image text](https://github.com/Liang-GR/weather-push/blob/M-main/weather%20push/weather-push-master-M-main/imgs/%E5%9B%BE%E7%89%878.jpg)

开始搭建环境：

1.  免费虚拟主机(百度一个免费的)：  https://www.tonghuacloud.com/
2.  免费二级域名(百度一个免费的)：  https://domain.umto.cn/home
3.	微信公众平台接口测试账号申请：https://mp.weixin.qq.com/debug/cgi-bin/sandbox?t=sandbox/login
4.	获取天气API： 和风天气开放平台（注册登录）：https://dev.qweather.com/ 控制台创建应用（获取Key）：https://console.qweather.com/#/apps?lang=zh
5.	获取城市id：  https://geoapi.qweather.com/v2/city/lookup?location=你的城市&key=你申请到的天气key
![Image text](https://github.com/Liang-GR/weather-push-master/blob/M-main/imgs/%E5%9B%BE%E7%89%871.png)

start.php内的城市名字region_name可以任意填写，如：重庆市，与之对应的region字段才是控制获取天气的主要参数，请勿填写错误

6.  申请填写内容

    1)![Image text](https://github.com/Liang-GR/weather-push-master/blob/M-main/imgs/%E5%9B%BE%E7%89%872.png)

    2)![Image text](https://github.com/Liang-GR/weather-push-master/blob/M-main/imgs/%E5%9B%BE%E7%89%873.png)

    3)![Image text](https://github.com/Liang-GR/weather-push-master/blob/M-main/imgs/%E5%9B%BE%E7%89%874.png)
  
7.  模板示例如下:

{{date.DATA}} 
{{cusinfo.DATA}} 
地区：{{region.DATA}} 
天气：{{weather.DATA}} 
气温：{{temp.DATA}} 
风向：{{wind_dir.DATA}} 
今天是我们相恋的第{{love_day.DATA}}天 
{{birthday1.DATA}} 
{{birthday2.DATA}} 
{{note_ch.DATA}}

![Image text](https://github.com/Liang-GR/weather-push-master/blob/M-main/imgs/%E5%9B%BE%E7%89%875.png)

8.  部署运行及定时发送

    1）在服务器上运行一个php环境
    
    2）将start.php上传至web运行目录即可
    
    3）如果你是linux服务器，定时发送可通过linux计划任务的方式（不推荐小白操作）
    
    4）如果你是小白，你可以选择使用宝塔面板来部署+定时发送，如何使用宝塔搭建php网站这里不多bb，可以百度一下，基本就是点点点即可。
    宝塔计划任务操作步骤如下：
    
    ![Image text](https://github.com/Liang-GR/weather-push-master/blob/M-main/imgs/%E5%9B%BE%E7%89%876.png)
    
    ![Image text](https://github.com/Liang-GR/weather-push-master/blob/M-main/imgs/%E5%9B%BE%E7%89%877.png)
   
9.  定时发送的平替方式：  
    网址监控： https://monit.or.passby.me/
    
    
