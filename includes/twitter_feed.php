<?php
if(!defined('ABSPATH')) exit; //exit if accessed directly
  require_once( 'twitter/twitteroauth/twitteroauth.php' );

class KatTwitter{

    private $html;
    private $hashtag;
    private $numOfTweets;
    private $count;
    private $consumer_key;
    private $consumer_secret;
    private $oauth_token;
    private $oauth_token_secret;
    private $connection;

    public function __construct()  {
        global $post;
        $this->count = 0;
        $this->consumer_key = get_option('kat_twitter_consumer_key');
        $this->consumer_secret = get_option('kat_twitter_consumer_secret');
     
        $this->oauth_token = get_option('kat_twitter_oauth_token');
        $this->oauth_token_secret = get_option('kat_twitter_oauth_secret');
        if(
            $this->consumer_key =='' || 
            $this->consumer_secret =='' || 
            $this->oauth_token =='' || 
            $this->oauth_token_secret ==''  
            ) {
            return;
        }
        $this->connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $this->oauth_token, $this->oauth_token_secret);

    }

    public function getTSearch($numOfTweets=3, $hashtag='') {
        $this->hashtag = $hashtag;
        $this->numOfTweets = $numOfTweets;
        if(
            $this->consumer_key =='' || 
            $this->consumer_secret =='' || 
            $this->oauth_token =='' || 
            $this->oauth_token_secret ==''  
            ) {
            return;
        }
        $this->getTwitterJson();
        return $this->html;
    }

    public function getTwitterShareCount(){
       //echo'https://api.twitter.com/1.1/search/tweets.json?q='.urlencode($this->hashtag).'&count='.$this->numOfTweets.'&src=typd';
        if(
            $this->consumer_key =='' || 
            $this->consumer_secret =='' || 
            $this->oauth_token =='' || 
            $this->oauth_token_secret ==''  
            ) {
            return "no oauth key";
        }
        $tweets = $this->connection->get('https://api.twitter.com/1.1/search/tweets.json?q='.urlencode($this->hashtag).'&src=typd');
        $statuses = $tweets->statuses;
        
        $this->count = count($statuses);

        return $this->count;
    }

    private function getTwitterJson(){
        if(
            $this->consumer_key =='' || 
            $this->consumer_secret =='' || 
            $this->oauth_token =='' || 
            $this->oauth_token_secret ==''  
            ) {
            return;
        }
        //echo'https://api.twitter.com/1.1/search/tweets.json?q='.urlencode($this->hashtag).'&count='.$this->numOfTweets.'&src=typd';

        if($this->hashtag == '') {
            $tweetPage = apply_filters( 'wpml_translate_single_string', get_option("katTwitterPage"), 'KatContact Data', 'katTwitterPage' );
            if(substr($tweetPage, -1) == '/'){
                $tweetPage = substr($tweetPage, count($tweetPage)-1, -1);
            }
            $tweetPageArr = explode("/", $tweetPage);
            $user = $tweetPageArr[3];
            $json = 'https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name='.$user.'&count='.$this->numOfTweets.'&src=typd';

            $tweets = $this->connection->get($json);
            $statuses = $tweets;

        }
        else {
            $tweets = $this->connection->get('https://api.twitter.com/1.1/search/tweets.json?q='.urlencode($this->hashtag).'&count='.$this->numOfTweets.'&src=typd');
            $statuses = $tweets->statuses;
        }


        $this->htmlizer($statuses);
    }

    private function htmlizer($statuses){
            // echo '<pre>';
            // print_r($search);
            // echo '</pre>';
        
        $html='<div class="slick-slider">';
        for ($i=0; $i < count($statuses); $i++) { 

            $tweet = $statuses[$i];

            $html .= '<div>';
            $html .= '<a href="https://twitter.com/'.$tweet->user->screen_name.'" target="_blank"><img src="'.$tweet->user->profile_image_url.'" alt="" width="48" height="48" /></a>';
            $html .= '<div class="t-cont">';
            $html .= '<span class="tweet-name">'. $tweet->user->name . '</span> ' ;
            $html .= '<a href="https://twitter.com/'.$tweet->user->screen_name.'" target="_blank" class="tweet-username">@'.$tweet->user->screen_name. '</a><br />';

            $tText = $this->sanitize_links($tweet);
            $html .= '<div class="tweet-text">' . $tText . '</div>';

            // $html .= 'Date de creation: ' . $tweet->created_at;
            // $html .= 'Timezone: ' . date_default_timezone_get();
            // $html .= '<br>Maintenant: ' . date_i18n('G:i', strtotime(time()), true);
            // $html .= 'Tweeté à ' . strtotime($tweet->created_at).'<br>';
            // $html .= 'temps actuel ' . time().'<br>';

            $interval = time()-strtotime($tweet->created_at);
            if($interval > 86400){ //if more than 24h happened, we display the date
                $dTweet = date_i18n(get_option( 'date_format' ), strtotime($tweet->created_at));
            }
            else{ //otherwise we display the hour
                date_default_timezone_set(get_option('timezone_string'));
                $dTweet = date_i18n('G:i', strtotime($tweet->created_at), false);
            }
            $html .= '<div class="tweet-date"><a href="https://twitter.com/' . $tweet->user->screen_name . '/status/'. $tweet->id_str . '"  target="_blank" >' . $dTweet .'</a></div>';
            $html .= '</div>';
            $html .= '</div>';

        // echo '<pre>';
        // print_r($statuses[$i]);
        // echo '</pre>';
        }
            $html .= '</div>';
        
        $this->html = $html;
    }

    private function sanitize_links($tweet) {
        if(isset($tweet->retweeted_status)) {
            $rt_section = current(explode(":", $tweet->text));
            $text = $rt_section.": ";
            $text .= $tweet->retweeted_status->text;
        } else {
            $text = $tweet->text;
        }
        $text = preg_replace('/((http)+(s)?:\/\/[^<>\s]+)/i', '<a class="tLink" href="$0" target="_blank" rel="nofollow">$0</a>', $text );
        $text = preg_replace('/[@]+([A-Za-z0-9-_]+)/', '<a class="tUser" href="http://twitter.com/$1" target="_blank" rel="nofollow">@$1</a>', $text );
        $text = preg_replace('/[#]+([A-Za-z0-9-_]+)/', '<a class="tSearch hashtag" href="http://twitter.com/search?q=%23$1" target="_blank" rel="nofollow">$0</a>', $text );
        return $text;

    }
}