<?php namespace Cafe\Http;
/**
 * Cafe PHP
 *
 * An agile development core based on PHP.
 *
 * @link     https://github.com/biejun/CafePHP
 * @copyright Copyright (c) 2021 Jun Bie
 * @license This content is released under the MIT License.
 */

use Cafe\Http\Request;

class Response
{
    # 响应状态码
    protected $status = 200;
    # 响应头部信息
    protected $headers = array();
    # 响应内容
    protected $body;
    # 字符编码
    private $charset = CHARSET;

    public static $codes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',

        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',

        226 => 'IM Used',

        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',

        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',

        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',

        426 => 'Upgrade Required',

        428 => 'Precondition Required',
        429 => 'Too Many Requests',

        431 => 'Request Header Fields Too Large',

        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',

        510 => 'Not Extended',
        511 => 'Network Authentication Required'
    );

    public function status($code = null)
    {
        if ($code === null) {
            return $this->status;
        }
        if (array_key_exists($code, self::$codes)) {
            $this->status = $code;
        } else {
            throw new \Exception('Invalid status code.');
        }
        return $this;
    }
    public function header($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->headers[$k] = $v;
            }
        } else {
            $this->headers[$name] = $value;
        }
        return $this;
    }
    public function headers()
    {
        return $this->headers;
    }
    public function write($str)
    {
        $this->body .= $str;
        return $this;
    }
    public function sendHeaders()
    {
        $expires = EXPIRES;
        if ($expires) {
            $expires = is_int($expires) ? $expires : strtotime($expires);
            $this->headers['Expires'] = gmdate('D, d M Y H:i:s', $expires) . ' GMT';
            $this->headers['Cache-Control'] = 'max-age='.($expires - time());
            if (isset($this->headers['Pragma']) && $this->headers['Pragma'] == 'no-cache') {
                unset($this->headers['Pragma']);
            }
        } else {
            $this->headers['Expires'] = 'Thu, 29 Oct 1992 08:30:00 GMT';
            $this->headers['Pragma'] = 'no-cache';
        }
        // Send status code header
        if (strpos(php_sapi_name(), 'cgi') !== false) {
            header(
                sprintf(
                    'Status: %d %s',
                    $this->status,
                    self::$codes[$this->status]
                ),
                true
            );
        } else {
            header(
                sprintf(
                    '%s %d %s',
                    (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1'),
                    $this->status,
                    self::$codes[$this->status]
                ),
                true,
                $this->status
            );
        }
        foreach ($this->headers as $field => $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    header($field.': '.$v, false);
                }
            } else {
                header($field.': '.$value);
            }
        }
        if (($length = strlen($this->body)) > 0) {
            header('Content-Length: '.$length);
        }
        return $this;
    }
    public function send()
    {
        if (ob_get_length() > 0) {
            ob_end_clean();
        }
        if (!headers_sent()) {
            $this->sendHeaders();
        }
        exit($this->body);
    }

    private function parseXml($data)
    {
        if (is_array($data)) {
            $result = '';
            foreach ($data as $key => $val) {
                $tagName = is_int($key) ? 'item' : $key;
                $result .= '<' . $tagName . '>' . $this->parseXml($val) . '</' . $tagName . '>';
            }
            return $result;
        } else {
            return preg_match("/^[^<>]+$/is", $data) ? $data : '<![CDATA[' . $data . ']]>';
        }
    }

    public function xml($data)
    {
        $xml = '<?xml version="1.0" encoding="'.$this->charset.'"?>';
        $xml .= '<response>';
        $xml .= $this->parseXml($data);
        $xml .= '</response>';
        $this->header('Content-Type', 'text/xml; charset='.$this->charset)
            ->write($xml)
            ->send();
    }

    public function json($data, $success=true)
    {
        $res = new \StdClass;
        $res->success = $success;
        $res->data = $data;
		$res->code = $this->status;
        $this->header('Content-Type', 'application/json; charset='.$this->charset)
            ->write(json_encode($res))
            ->send();
    }

    public function jsonp($data, $success=true)
    {
        $callback = (new Request)->get('callback', uniqid('Callback_'));
        $res = new \StdClass;
        $res->success = $success;
        $res->data = $data;
		$res->code = $this->status;
        $this->header('Content-Type', 'application/json; charset='.$this->charset)
            ->write($callback.'('.json_encode($res).')')
            ->send();
    }

    public function html($html)
    {
        $this->header('Content-Type', 'text/html; charset='.$this->charset)
            ->write($html)
            ->send();
    }

    public function text($text)
    {
        $this->header('Content-Type', 'text/plain; charset='.$this->charset)
            ->write($text)
            ->send();
    }

    public function redirect($location)
    {
        header('Location: '.Request::safeUrl($location), false, 302);
        exit;
    }

    /**
    * 返回来路
    *
    * @param string $suffix 附加地址
    * @param string $default 默认来路
    */
    public function goBack($suffix = null, $default = null)
    {
        //获取来源
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        //判断来源
        if (!empty($referer)) {
            if (!empty($suffix)) {
                $parts = parse_url($referer);
                $myParts = parse_url($suffix);

                if (isset($myParts['fragment'])) {
                    $parts['fragment'] = $myParts['fragment'];
                }

                if (isset($myParts['query'])) {
                    $args = array();
                    if (isset($parts['query'])) {
                        parse_str($parts['query'], $args);
                    }

                    parse_str($myParts['query'], $currentArgs);
                    $args = array_merge($args, $currentArgs);
                    $parts['query'] = http_build_query($args);
                }
                $referer = Request::buildUrl($parts);
            }
            $this->redirect($referer);
        } elseif (!empty($default)) {
            $this->redirect($default);
        }
    }
}
