<?php
if(!defined('ABSPATH')) exit; //exit if accessed directly
  require_once( 'twitter/twitteroauth/twitteroauth.php' );

class KatTwitterSearch{

    private $html;
    private $hashtag;
    private $numOfTweets;

    public function __construct($hashtag='#GoT', $numOfTweets=3)  {
        global $post;
        $this->hashtag = $hashtag;
        $this->numOfTweets = $numOfTweets;
    }

    public function getTSearch() {
        $this->getTwitterJson();
        return $this->html;
    }

    private function getTwitterJson(){
        $consumer_key = get_option('kat_twitter_consumer_key');
        $consumer_secret = get_option('kat_twitter_consumer_secret');
     
        $oauth_token = get_option('kat_twitter_oauth_token');
        $oauth_token_secret = get_option('kat_twitter_oauth_secret');

        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);
        //echo'https://api.twitter.com/1.1/search/tweets.json?q='.urlencode($this->hashtag).'&count='.$this->numOfTweets.'&src=typd';
        $search = $connection->get('https://api.twitter.com/1.1/search/tweets.json?q='.urlencode($this->hashtag).'&count='.$this->numOfTweets.'&src=typd');

        $this->htmlizer($search);
    }

    private function htmlizer($search){
        $statuses = $search->statuses;
            // echo '<pre>';
            // print_r($statuses);
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