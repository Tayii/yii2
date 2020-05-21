<?php
namespace app\daemons;

use consik\yii2websocket\events\WSClientMessageEvent;
use consik\yii2websocket\WebSocketServer;
use Ratchet\ConnectionInterface;

class EchoServer extends WebSocketServer
{

    public function init()
    {
        parent::init();

        $this->on(self::EVENT_CLIENT_MESSAGE, function (WSClientMessageEvent $e) {
		$request = json_decode($e->message, true);
		if (!isset($request['auth_key']) || !isset($request['controller']) || !isset($request['action'])) {
			return;
		}
		$c = curl_init();
		curl_setopt($c, CURLOPT_SSL_VERIFYSTATUS, 0);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_HTTPHEADER, array("Accept: application/json"));
		switch ($request['action']) {
			case "index":
				curl_setopt($c, CURLOPT_URL, "https://localhost/" . $request['controller'] . "s?" . http_build_query($request));
        			curl_setopt($c, CURLOPT_CUSTOMREQUEST, "GET");
			break;
			
			case "view":
				if (!isset($request['id'])) return;
				curl_setopt($c, CURLOPT_URL, "https://localhost/" . $request['controller'] . "s/" . $request['id'] . "?" . http_build_query($request));
        			curl_setopt($c, CURLOPT_CUSTOMREQUEST, "GET");
			break;
			
			case "update":
				if (!isset($request['id'])) return;
				curl_setopt($c, CURLOPT_URL, "https://localhost/" . $request['controller'] . "s/" . $request['id']);
        			curl_setopt($c, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($request));
			break;
		}
		$result = curl_exec($c);
		if ($result === FALSE)
			$e->client->send(curl_error($c));
		else
			$e->client->send($result);
		curl_close($c);
        });
    }
}
