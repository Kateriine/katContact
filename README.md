# KatContact 3.0
WP plugin for global contact data

- Admin page to add contact data like address, phone, Facebook page, etc.
- Multilingual support
- Includes widgets to customize
- FB app ID and App Secret support
- Widgets: FB Like box (with number of likes); FB Join Box; Contact data box
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
	
Get data with:
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

Youtube channels support
Instagram channels support
