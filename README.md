# KatContact
WP plugin for global contact data

- Admin page to add contact data like address, phone, Facebook page, etc.
- Multilingual support
- Includes widgets to customize
- FB app ID and App Secret support
- Widgets: FB Like box (with number of likes); FB Join Box; Contact data box
- New: Easily stylable Google maps generated from your adress. Just display it with 
<code>
<?php echo display_map(); ?>
or
[display-map]
<code>
- Icons work if you're using svg xlink in your theme
<code>
<svg class="chicon">
  <use xlink:href="' .get_stylesheet_directory_uri() . '/images/icons.svg#chicon-facebook" />
</svg>
</code>

Get data with:
get_option('katCompany');
get_option('katAddress');
get_option('katZipTown');
get_option('katCountry');
get_option('katPhone');
get_option('katFax');
get_option('katEmail');
get_option('katFbPage');
get_option('katTwitterPage');
get_option('katYoutubePage');
get_option('katGplusPage');
get_option('katLinkedinPage');
get_option('katInstagramPage');

To add:

Youtube and Flickr channels support
