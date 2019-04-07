<?php
class Curl
{
    /**
 * Power: mr li
 * Email：274805539@qq.com
 * @param $url
 * @return bool|mixed
 */
    static public function get($url){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }

    /**
 * Power: mr li
 * Email：274805539@qq.com
     * @param $url
     * @return bool|mixed
     */
    static public function getXml($url){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        $xml = simplexml_load_string($sContent);
        if($xml){
            return $xml;
        }else{
            return false;
        }


    }

    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    static public function post($url,$param,$post_file=false){
        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (is_string($param) || $post_file) {
            $strPOST = $param;
        } else{
            $aPOST = array();
            foreach($param as $key=>$val){
                $aPOST[] = $key."=".urlencode($val);
            }
            $strPOST =  join("&", $aPOST);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($oCurl, CURLOPT_POST,true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
     //   dump(json_decode($sContent));
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }

    static public function postJson($url,$param)
    {        $oCurl = curl_init();
        if(stripos($url,"https://")!==FALSE){
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }

        $strPOST = json_encode($param);
        $header= [
            'Content-Type: application/json',
        ];
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($oCurl, CURLOPT_POST,true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if(intval($aStatus["http_code"])==200){
            return $sContent;
        }else{
            return false;
        }
    }



    static public function getCurlFileMedia($file_path){
        if (class_exists('\CURLFile')) {// 这里用特性检测判断php版本
            $data =  new \CURLFile($file_path,"","");//>=5.5
        } else {
            $data =  '@' . $file_path;//<=5.5
        }
        return $data;

    }
    static public function  curlFile($url,$data){
// 兼容性写法参考示例
        $curl = curl_init();
        if (class_exists('\CURLFile')) {// 这里用特性检测判断php版本
            curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);

        } else {
            if (defined('CURLOPT_SAFE_UPLOAD')) {
                curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
            }
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1 );
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT,"TEST");
        $result = curl_exec($curl);
    //    $error = curl_error($curl);
        $status = curl_getinfo($curl);
        curl_close($curl);
        if(intval($status["http_code"])==200){
            return $result;
        }else{
            return false;
        }



    }


    /**
     * 生成安全JSON数据
     * @param array $array
     * @return string
     */
    static public function jsonEncode($array)
    {
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', create_function('$matches', 'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'), json_encode($array));
    }

    static public function  curlDownload($url, $dir)
    {
        $ch = curl_init($url);
        $fp = fopen($dir, "wb");
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $res = curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        return $res;
    }

    static public function page_exists($url)
    {
      $parts = parse_url($url);
      if (!$parts) {
           return false; /* the URL was seriously wrong */
       }
 
      if (isset($parts['user'])) {
          return false; /* user@gmail.com */
       }
 
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
 
       /* set the user agent - might help, doesn't hurt */
       //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; wowTreebot/1.0; +http://wowtree.com)');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
 
      /* try to follow redirects */
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 
      /* timeout after the specified number of seconds. assuming that this script runs
      on a server, 20 seconds should be plenty of time to verify a valid URL.  */
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
      curl_setopt($ch, CURLOPT_TIMEOUT, 20);
 
      /* don't download the page, just the header (much faster in this case) */
      curl_setopt($ch, CURLOPT_NOBODY, true);
      curl_setopt($ch, CURLOPT_HEADER, true);
 
     /* handle HTTPS links */
      if ($parts['scheme'] == 'https') {
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  1);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      }
 
      $response = curl_exec($ch);
      curl_close($ch);
 
     /* allow content-type list */
      $content_type = false;
      if (preg_match('/Content-Type: (.+\/.+?)/i', $response, $matches)) {
         switch ($matches[1])
          {
           case 'application/atom+xml':
           case 'application/rdf+xml':
           //case 'application/x-sh':
           case 'application/xhtml+xml':
           case 'application/xml':
           case 'application/xml-dtd':
           case 'application/xml-external-parsed-entity':
           //case 'application/pdf':
           //case 'application/x-shockwave-flash':
              $content_type = true;
              break;
          }
 
        if (!$content_type && (preg_match('/text\/.*/', $matches[1]) || preg_match('/image\/.*/', $matches[1]))) {
           $content_type = true;
        }
      }
 
      if (!$content_type) {
         return false;
      }
 
     /*  get the status code from HTTP headers */
      if (preg_match('/HTTP\/1\.\d+\s+(\d+)/', $response, $matches)) {
         $code = intval($matches[1]);
       } else {
         return false;
      }
 
      /* see if code indicates success */
     return (($code >= 200) && ($code < 400));
   }
}
