<?php

namespace App\Services;

use Carbon\Carbon;

class TpsConnect
{
    protected $ip;
    protected $port;
    protected $now;
    protected $postData;
    protected $responceData;

    public function __construct() {
        $this->ip = config('socket.'.config('app.server_place').'.ip');
        $this->port = config('socket.'.config('app.server_place').'.port');
        $this->now = date('YmdHis');
    }

    public function putSocketDummy()
    {
        $this->responceData = '3  16              14            14            3';
        return true;
    }

    public function putSocket()
    {
        $responceData = null;
        $success = false;
        try{
            var_dump($this->ip, $this->port);
            $fp = fsockopen($this->ip, $this->port);

            if ($fp) {
                \Log::debug('Start Socket');

                // Send socket request
                fputs($fp, $this->postData);

                // Get socket responce
                $response = fgets($fp);

                // Check time out
                $meta = stream_get_meta_data($fp);
                if (!$meta['timed_out']) {

                    \Log::debug('response:', [$response]);
                    $tpsResponce = $response;
                    $success = true;
                }

              // End socket connection
              fclose($fp);
            }
        }
        catch(\Exception $e){
            \Log::debug('Fatal Error', [$this->ip, $this->port, $e]);
        }

        $this->responceData = $responceData;
        return $success;
    }

    public function setPostData($QRcode, $iPadId)
    {
        \Log::debug('Start tpsConnect');

        $data = array(
            'stHeader$' => 'DAT',
            // 'stBHTID$' => 'IPAD0001        ',
            'stBHTID$' => 'IPAD'.$iPadId.'        ',
            'stDataID$' => $this->now,
            'stProgramID$' => 'TSYCHK',
            'stGyomuID$' => '0001',
            'stKinoID$' => '1000',
            'stSyosaiID$' => '1000',
            'stDataLen$' => sprintf('%05d', strlen($QRcode)-5),
            'stSosinKbn$' => '1',
            'stSyoriKbn$' => '1',
            'gstQRData$' => sprintf('%-178s', $QRcode),
            // 'stTermID$' => '0001   ',
            'stTermID$' => $iPadId.'   ',
            'stSendDate$' => $this->now,
            'stSendFlg$' => '1',
            'stModeNo$' => '3',
            'stAfterCode$' => '9999999999',
            'stAfterName$' => '                    '
        );

        \Log::debug('datas: ', $data);

        $postData = implode('', $data);

        $this->postData = $postData;

        return $postData;
    }

    public function getResponceData()
    {
        return $this->responceData;
    }
}
