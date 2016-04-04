<?php
class osTicket
{
    protected $url;
    protected $key;
    
    public function init(){
        $this->url = CParams::load()->params->support->url;
        $this->key = CParams::load()->params->support->api->key;
    }
    
    public function createTicket($data){
        $output = Yii::app()->curl
        ->setOption(CURLOPT_HEADER, false)
        ->setOption(CURLOPT_HTTPHEADER, array( 'Expect:', 'X-API-Key: '.$this->key))
        ->setOption(CURLOPT_FOLLOWLOCATION, false)
        ->setOption(CURLOPT_RETURNTRANSFER, true)
        ->post($this->url.'/api/http.php/tickets.json', json_encode($data));
        return $output;
    }
}
?>