
            ╭───────────────────────╮
    ────┤           支付宝代码示例结构说明             ├────
            ╰───────────────────────╯ 
　                                                                  
　       接口名称：支付宝标准双接口（trade_create_by_buyer）
　 　    代码版本：3.3
         开发语言：PHP
         版    权：支付宝（中国）网络技术有限公司
　       制 作 者：支付宝商户事业部技术支持组
         联系方式：商户服务电话0571-88158090

    ─────────────────────────────────

───────
 代码文件结构
───────

trade_create_by_buyer-php-UTF-8
  │
  ├lib┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈类文件夹
  │  │
  │  ├alipay_core.function.php ┈┈┈┈┈┈支付宝接口公用函数文件
  │  │
  │  ├alipay_notify.class.php┈┈┈┈┈┈┈支付宝通知处理类文件
  │  │
  │  ├alipay_submit.class.php┈┈┈┈┈┈┈支付宝各接口请求提交类文件
  │  │
  │  └alipay_md5.function.php┈┈┈┈┈┈┈支付宝接口MD5函数文件
  │
  ├log.txt┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈日志文件
  │
  ├alipay.config.php┈┈┈┈┈┈┈┈┈┈┈┈基础配置类文件
  │
  ├alipayapi.php┈┈┈┈┈┈┈┈┈┈┈┈┈┈支付宝接口入口文件
  │
  ├notify_url.php ┈┈┈┈┈┈┈┈┈┈┈┈┈服务器异步通知页面文件
  │
  ├return_url.php ┈┈┈┈┈┈┈┈┈┈┈┈┈页面跳转同步通知文件
  │
  ├cacert.pem ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈用于CURL中校验SSL的CA证书文件
  │
  └readme.txt ┈┈┈┈┈┈┈┈┈┈┈┈┈┈┈使用说明文本

※注意※

1、必须开启curl服务
（1）使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"即可
（2）文件夹中cacert.pem文件请务必放置到商户网站平台中（如：服务器上），并且保证其路径有效，提供的代码demo中的默认路径是当前文件夹下——getcwd().'\\cacert.pem'

2、需要配置的文件是：
alipay.config.php
alipayapi.php

●本代码示例（DEMO）采用fsockopen()的方法远程HTTP获取数据、采用DOMDocument()的方法解析XML数据。

请根据商户网站自身情况来决定是否使用代码示例中的方式——
如果不使用fsockopen，那么建议用curl来代替；
如果环境不是PHP5版本或其以上，那么请用其他方法代替DOMDocument()。

curl、XML解析方法需您自行编写代码。


─────────
 类文件函数结构
─────────

alipay_core.function.php

function createLinkstring($para)
功能：把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
输入：Array  $para 需要拼接的数组
输出：String 拼接完成以后的字符串

function createLinkstringUrlencode($para)
功能：把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对参数值urlencode
输入：Array  $para 需要拼接的数组
输出：String 拼接完成以后的字符串

function paraFilter($para)
功能：除去数组中的空值和签名参数
输入：Array  $para 签名参数组
输出：Array  去掉空值与签名参数后的新签名参数组

function argSort($para)
功能：对数组排序
输入：Array  $para 排序前的数组
输出：Array  排序后的数组

function logResult($word='')
功能：写日志，方便测试（看网站需求，也可以改成存入数据库）
输入：String $word 要写入日志里的文本内容 默认值：空值

function getHttpResponsePOST($url, $cacert_url, $para, $input_charset = '')
功能：远程获取数据，POST模式
输入：String $url 指定URL完整路径地址
      String $cacert_url 指定当前工作目录绝对路径
      Array  $para 请求的数据
      String $input_charset 编码格式。默认值：空值
输出：String 远程输出的数据

function getHttpResponseGET($url, $cacert_url)
功能：远程获取数据，GET模式
输入：String $url 指定URL完整路径地址
      String $cacert_url 指定当前工作目录绝对路径
输出：String 远程输出的数据

function charsetEncode($input,$_output_charset ,$_input_charset)
功能：实现多种字符编码方式
输入：String $input 需要编码的字符串
      String $_output_charset 输出的编码格式
      String $_input_charset 输入的编码格式
输出：String 编码后的字符串

function charsetDecode($input,$_input_charset ,$_output_charset) 
功能：实现多种字符解码方式
输入：String $input 需要解码的字符串
      String $_output_charset 输出的解码格式
      String $_input_charset 输入的解码格式
输出：String 解码后的字符串

┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉

function md5Sign($prestr, $key)
功能：MD5签名
输入：String $prestr 待签名数据
      String $key 私钥
输出：String 签名结果

function md5Verify($prestr, $sign, $key)
功能：MD5验签
输入：String $data 待签名数据
      String $sign 签名结果
      String $key 私钥
输出：bool 验证结果
┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉

alipay_notify.class.php

function verifyNotify()
功能：对notify_url的认证
输出：Bool  验证结果：true/false

function verifyReturn()
功能：对return_url的认证
输出：Bool  验证结果：true/false

function getSignVeryfy($para_temp, $sign)
功能：获取返回时的签名验证结果
输入：Array $para_temp 通知返回来的参数数组
      String $sign 支付宝返回的签名结果
输出：Bool 获得签名验证结果

function getResponse($notify_id)
功能：获取远程服务器ATN结果,验证返回URL
输入：String $notify_id 通知校验ID
输出：String 服务器ATN结果

┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉

alipay_submit.class.php

function buildRequestMysign($para_sort)
功能：生成要请求给支付宝的参数数组
输入：Array $para_sort 已排序要签名的数组
输出：String 签名结果

function buildRequestPara($para_temp)
功能：根据反馈回来的信息，生成签名结果
输入：Array $para_temp 请求前的参数数组
输出：String 要请求的参数数组

function buildRequestParaToString($para_temp)
功能：根据反馈回来的信息，生成签名结果
输入：Array $para_temp 请求前的参数数组
输出：String 要请求的参数数组字符串

function buildRequestForm($para_temp, $method, $button_name)
功能：建立请求，以表单HTML形式构造（默认）
输入：Array $para_temp 请求前的参数数组
      String $method 提交方式。两个值可选：post、get
      String $button_name 确认按钮显示文字
输出：String 提交表单HTML文本

function buildRequestHttp($para_temp)
功能：建立请求，以模拟远程HTTP的POST请求方式构造并获取支付宝的处理结果
输入：Array $para_temp 请求前的参数数组
输出：String 支付宝处理结果

function buildRequestHttpInFile($para_temp, $file_para_name, $file_name)
功能：建立请求，以模拟远程HTTP的POST请求方式构造并获取支付宝的处理结果，带文件上传功能
输入：Array $para_temp 请求参数数组
      String $file_para_name 文件类型的参数名
      String $file_name 文件完整绝对路径
输出：String 支付宝返回处理结果

function query_timestamp() 
功能：用于防钓鱼，调用接口query_timestamp来获取时间戳的处理函数
输出：String 时间戳字符串

┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉


──────────
 出现问题，求助方法
──────────

如果在集成支付宝接口时，有疑问或出现问题，可使用下面的链接，提交申请。
https://b.alipay.com/support/helperApply.htm?action=supportHome
我们会有专门的技术支持人员为您处理




