<?php
/**
 * Created by PhpStorm.
 * User: evis
 * Date: 1/20/17
 * Time: 12:14 PM
 */

namespace Evis\Http;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Slim\Http\Response;
use Slim\Interfaces\Http\HeadersInterface;

class ArrayResponse extends Response implements ResponseInterface{
    private $arrayData;

    public function __construct($status = 200, HeadersInterface $headers = null, StreamInterface $body = null){
        $this->arrayData = array();
        parent::__construct($status, $headers, $body);
    }

    public function setArrayData(array $data){
        $clone = clone $this;
        $clone->arrayData = $data;

        return $clone;
    }

    public function appendArrayData(array $data){
        $clone = clone $this;
        $clone->arrayData = array_merge($this->arrayData, $data);

        return $clone;
    }

    public function getArrayData(){
        return $this->arrayData;
    }
}