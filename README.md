# KatContact 3.0
WP plugin for global contact data
<ul>
<li>Admin page to add contact data like address, phone, Facebook page, etc.</li>
<li>Multilingual support</li>
<li>Includes widgets to customize *directly in the plugin*:  FB Like box (with number of likes); FB Join Box; Contact data box</li>
<li>Easily stylable Google maps generated from your adress. Just display it with 
	<ul>
		<li>
			<code>echo display_map();</code>
		</li>
		<li>
			or <code>[display-map]</code>
		</li>
	</ul>
	To change the marker, simply add an image called "markerIcon.svg" in your theme images folder.
</li>
<li><strong>NEW:</strong> WPML support</li>
<li><strong>NEW:</strong> Facebook feed with 
	<code>echo display_flickr($numberofStatuses=2, $numberofCols=2);</code><br />
    
By default, displays facebook status feed of the user's page.<br />
Parameters:<br />
	<ul>
		<li>Number of pictures (integer) - Default: 2</li>
		<li>Number of columns (integer) - Default: 2</li>
	</ul>
</li>
<li><strong>NEW:</strong> Flickr pictures with 
	<code>echo display_flickr($numberofPics=2, $numberofCols=2, $displayTitle=false, $flickrFeed='');</code><br />
    
By default, displays flickr pics feed of the user's page.<br />
Parameters:<br />
	<ul>
		<li>Number of pictures (integer) - Default: 2</li>
		<li>Number of columns (integer) - Default: 2</li>
		<li>Optional: Display the album title if there's a feed url in 4th parameter - Default: false</li>
		<li>Optional: Album URL - Default: ''</li>
	</ul>
</li>
<li><strong>NEW:</strong> Twitter feed with 
<code>
	echo display_twitter($numberofTweets, $hashtag);
</code><br />
By default, displays flickr pics feed of the user's page.<br />
Parameters:<br />
	<ul>
		<li>Number of tweets (integer) - Default: 2</li>
		<li>hashtag: Display a Twitter search feed - Default: 2</li>
	</ul>
Please customize the html in includes/twitter.php
</li>
<li><strong>NEW:</strong> Youtube feed with 
<code>
	echo display_youtube($numberofVids=2, $numberofCols=2, $displayTitle=false, $youtubeFeed='');
</code><br />
By default, displays flickr pics feed of the user's page.<br />
Parameters:<br />
	<ul>
		<li>Number of videos (integer) - Default: 2</li>
		<li>Number of columns (integer) - Default: 2</li>
		<li>Optional: Optional: Display the playlist title if there's a feed url in 4th parameter - Default: false</li>
		<li>Optional: Playlist URL - Default: ''</li>
	</ul>
</li>
<li>
	<strong>NEW:</strong> Instagram feed with
	<code>
	echo display_instagram($numberofPics=2, $numberofCols=2);
</code><br />
By default, displays Instagram pics feed of the user's page.<br />
Parameters:<br />
	<ul>
		<li>Number of pictures (integer) - Default: 2</li>
		<li>Number of columns (integer) - Default: 2</li>
	</ul>
	
</li>
</ul>
##So, what it does, in brief:
- Google map
- FB Like box widget (with number of likes)
- FB Join Box widget
- Contact data box widget
- Map
- Flickr user feed, Flickr album feed
- Twitter user feed, Twitter feed search
- Youtube user feed, Youtube playlist feed
- Instagram user feed
	
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
