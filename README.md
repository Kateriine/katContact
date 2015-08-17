# KatContact 3.0
WP plugin for global contact data

- Admin page to add contact data like address, phone, Facebook page, etc.
- Multilingual support
- Includes widgets to customize *directly in the plugin*:  FB Like box (with number of likes); FB Join Box; Contact data box
- Easily stylable Google maps generated from your adress. Just display it with 
<code>
<?php echo display_map(); ?></code>
or
<code>
[display-map]</code>
- NEW: WPML support
- NEW: Flickr pictures display with 
<code>
	echo display_flickr($numberofpics);</code>
- NEW: Twitter hashtag search feed with 
<code>
	echo display_twitter($hashtag, $bumberofTweets);
</code><br />
Please customize the html in includes/twitter.php

##So, what it does, in brief:
- FB Like box widget (with number of likes)
- FB Join Box widget
- Contact data box widget
- Map
- Flickr feed
- Twitter feed search
	
## You can get data anywhere with:
getKatCompany();<br />
getKatAddress();<br />
getKatZipTown();<br />
getKatPhone();<br />
getKatCountry();<br />
getKatPhone();<br />
getKatFax();<br />
getKatEmail();<br />
getKatFbPage();<br />
getKatTwitterPage();<br />
getKatYoutubePage();<br />
getKatGplusPage();<br />
getKatLinkedinPage();<br />
getKatInstagramPage();<br />
getKatFlickrPage();<br />
getKatPinterestPage();<br />

To add:

Youtube channels support<br />
Instagram channels support
