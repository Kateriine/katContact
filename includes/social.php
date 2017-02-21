<?php
//Social stats + share: works with plugin katContact

function social_count(){
  global $post;

  $now = time();

  //Get facebook cached or live data
  if(get_post_meta($post->ID, 'fb_shareArr' )) {
    $fbShareArr = get_post_meta($post->ID, 'fb_shareArr', true);
    
    if($now > $fbShareArr['time'] + 86400) {
      $fbShare = get_fb_count($post->ID);
      $fbShareArr = array('time' => $now, 'count' => $fbShare);
      update_post_meta($post->ID, 'fb_shareArr', $fbShareArr);
    }
    else {
      $fbShare = $fbShareArr['count'];
    }
  }
  else {
      $fbShare = get_fb_count($post->ID);
      $fbShareArr = array('time' => $now, 'count' => $fbShare);
      update_post_meta($post->ID, 'fb_shareArr', $fbShareArr);
  }

  // Get Twitter cached or live data
  if(get_post_meta($post->ID, 'tw_shareArr' )) {
    $twShareArr = get_post_meta($post->ID, 'tw_shareArr', true);
    
    if($now > $fbShareArr['time'] + 86400) {
      //check contact plugin for get_twitter_count
      $twShare = get_twitter_count(99999, get_permalink($post->ID ));
      $twShareArr = array('time' => $now, 'count' => $twShare);
      update_post_meta($post->ID, 'tw_shareArr', $twShareArr);
    }
    else {
      $twShare = $twShareArr['count'];
    }
  }
  else {
      $twShare = get_twitter_count(99999, get_permalink($post->ID ));
      $twShareArr = array('time' => $now, 'count' => $twShare);
      update_post_meta($post->ID, 'tw_shareArr', $twShareArr);
  }

  // Get Linkedin cached or live data
  if(get_post_meta($post->ID, 'ln_shareArr' )) {
    $lnShareArr = get_post_meta($post->ID, 'ln_shareArr', true);
    
    if($now > $lnShareArr['time'] + 86400) {
      $lnShare = get_lnShare($post->ID);
      $lnShareArr = array('time' => $now, 'count' => $lnShare);
      update_post_meta($post->ID, 'ln_shareArr', $lnShareArr);
    }
    else {
      $lnShare = $lnShareArr['count'];
    }
  }
  else {
      $lnShare = get_lnShare($post->ID);
      $lnShareArr = array('time' => $now, 'count' => $lnShare);
      update_post_meta($post->ID, 'ln_shareArr', $lnShareArr);
  }

  // Get Pinterest cached or live data
  if(get_post_meta($post->ID, 'pn_shareArr' )) {
    $pnShareArr = get_post_meta($post->ID, 'pn_shareArr', true);
    
    if($now > $pnShareArr['time'] + 86400) {
      $pnShare = get_pnShare($post->ID);
      $pnShareArr = array('time' => $now, 'count' => $pnShare);
      update_post_meta($post->ID, 'pn_shareArr', $pnShareArr);
    }
    else {
      $pnShare = $pnShareArr['count'];
    }
  }
  else {
      $pnShare = get_pnShare($post->ID);
      $pnShareArr = array('time' => $now, 'count' => $pnShare);
      update_post_meta($post->ID, 'pn_shareArr', $pnShareArr);
  }

    // Get Google cached or live data
  if(get_post_meta($post->ID, 'ggl_shareArr' )) {
    $gglShareArr = get_post_meta($post->ID, 'ggl_shareArr', true);
    
    if($now > $gglShareArr['time'] + 86400) {
      $gglShare = get_gglShare($post);
      $gglShareArr = array('time' => $now, 'count' => $gglShare);
      update_post_meta($post->ID, 'ggl_shareArr', $gglShareArr);
    }
    else {
      $gglShare = $gglShareArr['count'];
    }
  }
  else {
      $gglShare = get_gglShare($post->ID);
      $gglShareArr = array('time' => $now, 'count' => $gglShare);
      update_post_meta($post->ID, 'ggl_shareArr', $gglShareArr);
  }

  // Get 1st pic of real estate
function get_photo_url() {
  global $post;
  global $WP_Views;

  $pic = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
  return $pic[0]; 
}


  $html = '<div class="uk-social-links">
  <h3 class="uk-text-center">Partager</h3>
            <ul>
              <li class="share-count">
                <a class="facebook popup" href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode(get_permalink()) . '"  title="Partager sur Facebook">
                  <span class="share-count--icon">
                    <svg version="1.1" id="Calque_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="13px" height="28px" viewBox="160.923 50 12.923 28" enable-background="new 160.923 50 12.923 28" xml:space="preserve">
                    <path fill="currentColor" id="facebook-icon" d="M163.855,59.154h-2.932v4.786h2.932V78h5.638V63.88h3.934l0.418-4.727h-4.353c0,0,0-1.765,0-2.692
                      c0-1.114,0.225-1.556,1.301-1.556c0.868,0,3.051,0,3.051,0V50c0,0-3.216,0-3.904,0c-4.195,0-6.087,1.847-6.087,5.385
                      C163.855,58.466,163.855,59.154,163.855,59.154z"></path>
                    </svg>
                    </span>
                  <span class="share-count--count">'.
                  $fbShare.'
                  </span>
                </a>
              </li>

              <li class="share-count">
                <a class="twitter popup" href="https://twitter.com/home?status=' . urlencode(get_the_title()) . '%20' . urlencode(get_permalink()) . '"  title="Partager sur Twitter">
                  <span class="share-count--icon">
                    <svg width="28px" height="28px" viewBox="-1 7 37 28" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <path fill="currentColor" d="M35.84,10.304 C34.496,10.864 33.096,11.256 31.64,11.424 C33.152,10.528 34.328,9.184 34.888,7.504 C33.488,8.288 31.92,8.904 30.24,9.24 C28.896,7.84 26.992,7 24.864,7 C20.776,7 17.528,10.192 17.528,14.056 C17.528,14.616 17.584,15.176 17.696,15.68 C11.536,15.4 6.104,12.544 2.52,8.288 C1.904,9.352 1.512,10.528 1.512,11.816 C1.512,14.28 2.8,16.408 4.76,17.696 C3.528,17.64 2.408,17.36 1.456,16.8 L1.456,16.912 C1.456,20.328 3.976,23.184 7.336,23.856 C6.72,24.024 6.048,24.08 5.376,24.08 C4.928,24.08 4.424,24.024 3.976,23.968 C4.928,26.768 7.616,28.84 10.864,28.896 C8.344,30.8 5.152,31.92 1.736,31.92 C1.12,31.92 0.56,31.864 -1.24344979e-15,31.808 C3.248,33.824 7.112,35 11.256,35 C24.808,35 32.2,24.248 32.2,14.896 L32.2,14 C33.6,12.992 34.832,11.76 35.84,10.304 Z" id="Shape" stroke="none" fill="#000000" fill-rule="nonzero"></path>
                    </svg>
                  </span>
                  <span class="share-count--count">'.
                  $twShare.'
                  </span>
                </a>
              </li>

              <li class="share-count">
                <a class="linkedin popup" href="http://www.linkedin.com/shareArticle?mini=true&url=' . urlencode(get_permalink()) . '&title='.urlencode(get_the_title()).'&summary='.urlencode(get_the_excerpt(get_the_id())).'" title="Partager sur Linkedin">
                  <span class="share-count--icon">
                    <svg version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="28px" height="28px" viewBox="0 0 28 28" enable-background="new 0 0 28 28" xml:space="preserve">
                        <path fill="currentColor" d="M25.424,15.887v8.447h-4.896v-7.882c0-1.979-0.709-3.331-2.48-3.331c-1.354,0-2.158,0.911-2.514,1.803
                            c-0.129,0.315-0.162,0.753-0.162,1.194v8.216h-4.899c0,0,0.066-13.349,0-14.731h4.899v2.088c-0.01,0.016-0.023,0.032-0.033,0.048
                            h0.033V11.69c0.65-1.002,1.812-2.435,4.414-2.435C23.008,9.254,25.424,11.361,25.424,15.887z M5.348,2.501
                            c-1.676,0-2.772,1.092-2.772,2.539c0,1.421,1.066,2.538,2.717,2.546h0.032c1.709,0,2.771-1.132,2.771-2.546
                            C8.054,3.593,7.019,2.501,5.343,2.501H5.348z M2.867,24.334h4.897V9.603H2.867V24.334z"></path>
                    </svg>
                  </span>
                  <span class="share-count--count">'.
                  $lnShare.'
                  </span>
                </a>
              </li>

              <li class="share-count">
                <a class="gplus popup" href="https://plus.google.com/share?url=' . urlencode(get_permalink()) . '" title="Partager sur Google +">
                  <span class="share-count--icon">  
                    <svg width="28px" height="28px" viewBox="0 11 28 19" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                      <path fill="currentColor" d="M8.88533333,19.448 L8.88533333,22.4533333 L13.8942222,22.4533333 C13.6764444,23.7164444 12.3697778,26.1991111 8.88533333,26.1991111 C5.83644444,26.1991111 3.39733333,23.7164444 3.39733333,20.7111111 C3.39733333,17.7057778 5.88,15.2231111 8.88533333,15.2231111 C10.6275556,15.2231111 11.76,15.9635556 12.4133333,16.5733333 L14.8088889,14.3084444 C13.2844444,12.8711111 11.2808889,12 8.88533333,12 C3.96355556,12 0,15.8764444 0,20.7111111 C0,25.5457778 3.96355556,29.4222222 8.88533333,29.4222222 C14.0248889,29.4222222 17.3786667,25.8942222 17.3786667,20.9288889 C17.3786667,20.3626667 17.3351111,19.9271111 17.248,19.4915556 L8.88533333,19.4915556 L8.88533333,19.448 Z" id="Shape" stroke="none"></path>
                      <path fill="currentColor" d="M25.3493333,19.448 L25.3493333,16.9653333 L22.8231111,16.9653333 L22.8231111,19.448 L20.2968889,19.448 L20.2968889,21.9306667 L22.8231111,21.9306667 L22.8231111,24.4133333 L25.3493333,24.4133333 L25.3493333,21.9306667 L27.8755556,21.9306667 C27.8755556,21.9742222 27.8755556,19.448 27.8755556,19.448 L25.3493333,19.448 L25.3493333,19.448 Z" id="Shape" stroke="none" fill-rule="nonzero"></path>
                    </svg>
                  </span>
                  <span class="share-count--count">'.
                  $gglShare.'
                  </span>
                </a>
              </li>

              <li class="share-count">
                <a class="pinterest popup" href="http://pinterest.com/pin/create/button/?url=' . urlencode(get_permalink()) . '&media='.urlencode(get_photo_url()).'&description='.urlencode(get_the_excerpt(get_the_id())).'" title="Partager sur Pinterest">
                  <span class="share-count--icon">                    
                    <svg width="21px" height="28px" viewBox="8 0 21 28" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <path fill="currentColor" d="M13.6261682,26.517134 C14.2803738,25.4267913 15.2834891,23.5950156 15.6760125,22.1121495 C15.894081,21.3271028 16.6791277,18.1433022 16.6791277,18.1433022 C17.2024922,19.1900312 18.7725857,20.0623053 20.4299065,20.0623053 C25.3582555,20.0623053 28.9345794,15.4392523 28.9345794,9.63862928 C28.9345794,4.14330218 24.529595,0 18.8161994,0 C11.7507788,0 8,4.8411215 8,10.1183801 C8,12.5607477 9.26479751,15.6137072 11.3146417,16.6168224 C11.6199377,16.7476636 11.7943925,16.7040498 11.8816199,16.3987539 C11.9252336,16.1806854 12.2305296,15.046729 12.317757,14.5233645 C12.3613707,14.3489097 12.317757,14.2180685 12.1869159,14.0436137 C11.4890966,13.2149533 10.9657321,11.6448598 10.9657321,10.2056075 C10.9657321,6.49844237 13.7133956,2.96573209 18.3800623,2.96573209 C22.3925234,2.96573209 25.2274143,5.75700935 25.2274143,9.7694704 C25.2274143,14.305296 23.0031153,17.4454829 20.0809969,17.4454829 C18.4672897,17.4454829 17.2461059,16.0934579 17.6386293,14.4361371 C18.1183801,12.4299065 18.9906542,10.2928349 18.9906542,8.85358255 C18.9906542,7.58878505 18.3364486,6.49844237 16.8971963,6.49844237 C15.2398754,6.49844237 13.9314642,8.24299065 13.9314642,10.5545171 C13.9314642,12.0373832 14.411215,13.0404984 14.411215,13.0404984 C14.411215,13.0404984 12.7975078,20.0623053 12.4922118,21.3707165 C12.1433022,22.8099688 12.2741433,24.8598131 12.4485981,26.1682243 L12.623053,28 L13.6261682,26.517134 Z" id="Shape" stroke="none" fill="#000000" fill-rule="nonzero"></path>
                    </svg>
                  </span>
                  <span class="share-count--count">'.
                  $pnShare.'
                  </span>
                </a>
              </li>
          </ul>
        </div>';
  return $html;
}


function get_lnShare($post_id){
  $json = @file_get_contents('https://www.linkedin.com/countserv/count/share?url=' . urlencode( get_permalink( $post_id ) ) );
  $counts = @json_decode($json, true);

  if($counts && isset($counts["count"])){
    $shareCount = $counts["count"];
  }
  else {
    $shareCount = 0;
  }
  return $shareCount;
}
function get_pnShare($post_id){
  $json = @file_get_contents('http://api.pinterest.com/v1/urls/count.json?url=' . urlencode( get_permalink( $post_id ) ) );
  $counts = @json_decode($json, true);

  if($counts && isset($counts["count"])){
    $shareCount = $counts["count"];
  }
  else {
    $shareCount = 0;
  }
  return $shareCount;
}
function get_gglShare($post_id){
  $url = 'https://apis.google.com/u/0/se/0/_/+1/sharebutton?plusShare=true&url=' . urlencode( get_permalink( $post_id ) );
  $shareCount = 0;
  if(function_exists('curl_version')) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_USERAGENT, 'shareCount/1.2 by abemedia');
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
      curl_setopt($ch, CURLOPT_TIMEOUT, 1);
      $data = curl_exec($ch);
      curl_close($ch);
  }
  else {
      $data = @file_get_contents($url);
    
  }
  if ($data) {
    preg_match( '/window\.__SSR = {c: ([\d]+)/', $data, $matches);
    if($matches && isset($matches[1]))
      $shareCount = $matches[1]; 
  }
  
   return $shareCount;
}
?>
