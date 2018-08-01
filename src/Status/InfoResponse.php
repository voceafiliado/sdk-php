<?php namespace VCA\Sdk\Status;

use Carbon\Carbon;
use VCA\Sdk\ResponseObject;

class InfoResponse extends ResponseObject
{
    /**
     * Retorno o nome da aplicacao.
     *
     * @return string
     */
    public function getApp()
    {
        return $this->get('app', '');
    }

    /**
     * Retorna a versao da aplicacao.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->get('version', '');
    }

    /**
     * Retorna a data e hora do servidor.
     *
     * @return null|Carbon
     */
    public function getDateServer()
    {
        $ret = $this->get('date', null);
        if (! is_null($ret)) {
            $ret = Carbon::createFromFormat(Carbon::ISO8601, $ret);
        }

        return $ret;
    }
}