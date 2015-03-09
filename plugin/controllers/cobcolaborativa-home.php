<?php date_default_timezone_set('America/Sao_Paulo');

class CobColaborativaHome {
    private $palavraChave;
    private $qtde;
    private $youtube_key;
    private $flickr_key;
    private $flickr_photo_id;
    private $instagram_key;
    private $instagram_secret;
    private $instagram_callback;
    private $twitter_key;
    private $twitter_secret;
    private $twitter_token;
    private $twitter_token_secret;

    public function __construct() {
        $this->palavraChave = '';
        $this->qtde         = 15;
        $this->youtube_key = '';
        $this->flickr_key = '';
        $this->flickr_photo_id = '';
        $this->instagram_key = '';
        $this->instagram_secret = '';
        $this->instagram_callback = '';
        $this->twitter_key = '';
        $this->twitter_secret = '';
        $this->twitter_token = '';
        $this->twitter_token_secret = '';
    }

    //  CONNECTION WITH API (YOUTUBE/FLICKR/INSTAGRAM/TWITTER)
    public function youtube() {
        $DEVELOPER_KEY = $this->youtube_key;

        $client = new Google_Client();
        $client->setDeveloperKey($DEVELOPER_KEY);

        $youtube = new Google_Service_YouTube($client);

        $searchResponse = $youtube->search->listSearch('id,snippet', array(
            'q' => $this->palavraChave,
            'maxResults' => $this->qtde,
            'type' => 'video',
            'order' => 'date',
        ));

        foreach($searchResponse['items'] as $data) {
           $time = strtotime($data['snippet']['publishedAt']);

           $dados->name[]       = $data['snippet']['channelTitle'];
           $dados->type[]       = 'youtube';
           $dados->data[]       = 'http://www.youtube.com/embed/'.$data['id']['videoId'];
           $dados->author[]     = $data['snippet']['channelTitle'];
           $dados->link[]       = 'http://www.youtube.com/'.$data['id']['videoId'];
           $dados->generate[]   = date('Y-m-d H:i:s', $time);
           $dados->created[]    = date('Y-m-d H:i:s');
        }

        $this->insertDB($dados);
    }

    public function flickr() {
        $params = array(
            'api_key'	=> $this->flickr_key,
            'method'	=> 'flickr.photos.search',
            'photo_id'	=> $this->flickr_photo_id,
            'format'	=> 'php_serial',
            'tags'		=> $this->palavraChave,
            'per_page'	=> $this->qtde
        );

        $encoded_params = array();

        foreach ($params as $k => $v){
            $encoded_params[] = urlencode($k).'='.urlencode($v);
        }

        $url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);
        $rsp = file_get_contents($url);
        $rsp_obj = unserialize($rsp);

        foreach ($rsp_obj['photos']['photo'] as $data) {
            $dados->name[]       = $data['title'];
            $dados->type[]       = 'flickr';
            $dados->data[]       = 'http://c2.staticflickr.com/'.$data['farm'].'/'.$data['server'].'/'.$data['id'].'_'.$data['secret'].'_z.jpg';
            $dados->author[]     = $data['title'];
            $dados->link[]       = 'http://c2.staticflickr.com/'.$data['farm'].'/'.$data['server'].'/'.$data['id'].'_'.$data['secret'].'_z.jpg';
            $dados->generate[]   = date('Y-m-d H:i:s');
            $dados->created[]    = date('Y-m-d H:i:s');
        }
        $this->insertDB($dados);
    }
    public function instagram() {
        $instagram = new Instagram(array(
          'apiKey'      => $this->instagram_key,
          'apiSecret'   => $this->instagram_secret,
          'apiCallback' => $this->instagram_callback
        ));

        // create login URL
        $loginUrl = $instagram->getLoginUrl();
        $media = $instagram->getTagMedia($this->palavraChave, $this->qtde);

        //var_dump($media->data[0]);
        //exit;

        foreach ($media->data as $data) {
            $dados->name[]       = $data->caption->from->full_name;
            $dados->type[]       = 'instagram';
            $dados->data[]       = $data->images->standard_resolution->url;
            $dados->author[]     = $data->caption->from->username;
            $dados->link[]       = $data->link;
            $dados->generate[]   = date('Y-m-d H:i:s', $data->created_time);
            $dados->created[]    = date('Y-m-d H:i:s');
        }

        $this->insertDB($dados);

    }
    public function twitter() {
        \Codebird\Codebird::setConsumerKey($this->twitter_key, $this->twitter_secret);

        $cb = \Codebird\Codebird::getInstance();

        $cb->setToken($this->twitter_token, $this->twitter_token_secret);

        $reply = $cb->oauth2_token();
        $bearer_token = $reply->access_token;

        $reply = $cb->search_tweets('q=#'.$this->palavraChave, true);

        foreach ($reply->statuses as $data) {
            $time = strtotime($data->created_at);
            $dados->name[]       = $data->user->name;
            $dados->type[]       = 'twitter';
            $dados->data[]       = $data->text;
            $dados->author[]     = $data->user->screen_name;
            $dados->link[]       = 'https://www.twitter.com/'.$data->user->screen_name;
            $dados->generate[]   = date('Y-m-d H:i:s', $time);
            $dados->created[]    = date('Y-m-d H:i:s');
        }
        $this->insertDB($dados);
    }

    // VERIFICATION AND INCLUSION IN THE DATABASE
    public function verificationDB($data) {
        global $wpdb;
        $cobcolaborativa_model = new CobColaborativaDataModel();
        if($data != '') {
            $verifica = $wpdb->get_row("SELECT * FROM " . $cobcolaborativa_model->table_name . " WHERE data = '".$data."'");
        } else {
            $verifica = true;
        }
        return $verifica;
    }
    public function insertDB($dados) {
        global $wpdb;

        $cobcolaborativa_model = new CobColaborativaDataModel();

        for($i=0; $i < $this->qtde; $i++) {

            $check = $this->verificationDB($dados->data[$i]);
            if(!$check){
                $insert_data = array(
                    'name' => $dados->name[$i],
                    'type' => $dados->type[$i],
                    'data' => $dados->data[$i],
                    'author' => $dados->author[$i],
                    'link' => $dados->link[$i],
                    'generate_at' => $dados->generate[$i],
                    'created_at' => $dados->created[$i]
                );

                $wpdb->insert(
                    $cobcolaborativa_model->table_name,
                    $insert_data,
                    array(
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s'
                    )
                );

            }
        }

    }


}

?>
