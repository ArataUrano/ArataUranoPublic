<div class="container"><div class="row">'
<?php
for ( $i=0; $i<$photo_cnt; $i++ ) {

  if ( $i >= self::MAXCNT ) { break; }

  if ( self::$instaData->data[$i]->type != 'image') { continue; }

  $thumbnail 	= self::$instaData->data[$i]->images->thumbnail->url;

  $link				= self::$instaData->data[$i]->link; ?>

  <div class="col-xs-3">
  <a href="<?php echo $link; ?>"><img src="<?php echo $thumbnail; ?>" class="rounded img-thumbnail img-responsive" alt="DEMO暮らし.net インスタグラム対応" />
  <div class="introText mt-5">
  </div></a>
</div>
  <?php
} ?>
</div></div>
