<?php

namespace App\Notify;

use App\Lib\CurlRequest;
use App\Notify\NotifyProcess;
use App\Notify\Notifiable;

class Push extends NotifyProcess implements Notifiable{

    /**
    * Device Id of receiver
    *
    * @var array
    */
	public $deviceId;

    public $redirectUrl;

    public $pushImage;


    /**
    * Assign value to properties
    *
    * @return void
    */
	public function __construct(){
		$this->statusField = 'push_status';
		$this->body = 'push_body';
		$this->globalTemplate = 'push_template';
		$this->notifyConfig = 'firebase_config';
	}


    public function redirectForApp($getTemplateName){

        $screens = [

        ];

        foreach($screens as $screen => $array){
            if(in_array($getTemplateName ,$array)){
                return $screen;
            }
        }

        return 'HOME';
    }


    /**
    * Send notification
    *
    * @return void|bool
    */
	public function send(){
        //get message from parent
        $message = $this->getMessage();
        if (gs('pn') && $message) {
            try {
                $credentialsFilePath = getFilePath('pushConfig').'/push_config.json';

                if (!file_exists($credentialsFilePath)) {
                    throw new \Exception('Push notifications are on but the Firebase service account file is missing. Upload it under Notification Setting > Push Notification.');
                }

                // A service account only works against its own project. Catching
                // the mismatch here beats every send silently failing at Google.
                $serviceAccount = json_decode(file_get_contents($credentialsFilePath));
                $projectId      = gs('firebase_config')->projectId ?? null;

                if (($serviceAccount->project_id ?? null) !== $projectId) {
                    throw new \Exception('Firebase project mismatch: the service account belongs to "'.($serviceAccount->project_id ?? 'unknown').'" but the site is configured for "'.($projectId ?: 'unset').'". Both must come from the same Firebase project.');
                }

                $client = new \Google_Client();
                $client->setAuthConfig($credentialsFilePath);
                $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
                $client->fetchAccessTokenWithAssertion();
                $token = $client->getAccessToken();
                $access_token = $token['access_token'];
                $headers = [
                    "Authorization: Bearer $access_token",
                    'Content-Type: application/json'
                ];

                $data['notification'] = [
                    'body'=>$message,
                    'title'=>$this->getTitle(),
                    'image'=>asset(getFilePath('push')).'/'.$this->pushImage,
                ];

                $data['data'] = [
                    'icon'=>siteFavicon(),
                    'click_action'=>$this->redirectUrl,
                    'app_click_action'=>$this->redirectForApp($this->templateName)
                ];
                $failures = [];

                foreach ($this->toAddress as $toAddress) {
                    $data['token'] = $toAddress;
                    $payloadData['message'] = $data;
                    $payload = json_encode($payloadData);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/'.$projectId.'/messages:send');
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    $response = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

                    // Google answers every send; without reading it a wrong key or
                    // a stale device token looks exactly like success.
                    if ($httpCode < 200 || $httpCode >= 300) {
                        $detail = json_decode($response);
                        $failures[] = $detail->error->message ?? ('HTTP '.$httpCode);
                    }
                }

                if ($failures) {
                    throw new \Exception('Push delivery failed: '.implode(' | ', array_unique($failures)));
                }
            } catch(\Exception $e){
                $this->createErrorLog($e->getMessage());
                session()->flash('firebase_error',$e->getMessage());
            }
        }

    }



    /**
    * Configure some properties
    *
    * @return void
    */
	public function prevConfiguration(){
		if ($this->user) {
            $this->deviceId = $this->user->deviceTokens()->pluck('token')->toArray();
			$this->receiverName = $this->user->fullname;
		}
		$this->toAddress = $this->deviceId;
	}

    private function getTitle(){
        return $this->replaceTemplateShortCode($this->template->push_title ?? gs('push_title'));
    }
}
