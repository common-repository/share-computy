<?php

function share_computy_buttons(){
    $share_buttons ='';
   $post_id = get_the_ID();
   $url = get_permalink( $post_id );
   $title = get_the_title($post_id);
   $desc = get_the_excerpt( $post_id );
    //должно находится внутри цикла
    if( has_post_thumbnail() ) {
        $img = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
    }
    else {
        $img = '';
    }

  global $wpdb;
  $table_name = $wpdb->prefix . 'share_computy';

    $count1 = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM " . $table_name. " WHERE  post_id = %d AND vote= %s",
        $post_id,'vk'
    ) );
    $count2 = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM " . $table_name. " WHERE  post_id = %d AND vote= %s",
        $post_id,'fb'
    ) );
    $count3 = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM " . $table_name. " WHERE  post_id = %d AND vote= %s",
        $post_id,'ok'
    ) );
    $count4 = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM " . $table_name. " WHERE  post_id = %d AND vote= %s",
        $post_id,'tw'
    ) );
    $count5 = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM " . $table_name. " WHERE  post_id = %d AND vote= %s",
        $post_id,'tg'
    ) );
    $count6 = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM " . $table_name. " WHERE  post_id = %d AND vote= %s",
        $post_id,'whatsapp'
    ) );
    $count7 = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM " . $table_name. " WHERE  post_id = %d AND vote= %s",
        $post_id,'viber'
    ) );
   $sesid = session_id();
    $table_lc = $wpdb->get_row( $wpdb->prepare(
        "SELECT * FROM " . $table_name. " WHERE  post_id = %d AND session_id= %s",
        $post_id,$sesid
    ) );
    $golos_stoit = $table_lc->vote ?? '';

    $val = get_option( 'share_computy_option' );

    $title_share  = $val['title-share'] ?? __( 'Share', 'bonus-for-woo' );

  if(isset($val['share-vk'])){
      $share_buttons .= '<a rel="nofollow noopener" onclick="go(\'https://vk.com/share.php?url='.$url.'&title='.$title.'&description='.$desc.'&image='.$img.'&noparse=true\'); return false;" href="https://vk.com/share.php?url='.$url.'&title='.$title.'&description='.$desc.'&image='.$img.'&noparse=true" class="share-item vk" data-type="vk" title="'. __( 'Vkontakte', 'share-computy' ).'" data-title="'. __( 'Vkontakte', 'share-computy' ).'">
     <div class="icon"></div>
    <span class="value" data-value="'.$count1.'">'.$count1.'</span></a>';
  }

    if(isset($val['share-fb'])){
        $share_buttons .= '<a  rel="nofollow noopener" href="https://www.facebook.com/sharer/sharer.php?u='.$url.'" onclick="go(\'https://www.facebook.com/sharer/sharer.php?u='.$url.'\'); return false;" class="share-item fb" data-type="fb" title="'. __( 'Facebook', 'share-computy' ).'" data-title="'. __( 'Facebook', 'share-computy' ).'">
     <div class="icon"></div>
    <span class="value" data-value="'.$count2.'">'.$count2.'</span></a>';
    }

    if(isset($val['share-ok'])){
        $share_buttons .= '<a  rel="nofollow noopener" href="https://connect.ok.ru/offer?url='.$url.'&title='.$title.'" onclick="go(\'https://connect.ok.ru/offer?url='.$url.'&title='.$title.'\'); return false;" class="share-item ok" data-type="ok" title="'. __( 'Odnoklassniki', 'share-computy' ).'" data-title="'. __( 'Odnoklassniki', 'share-computy' ).'">
     <div class="icon"></div>
    <span class="value" data-value="'.$count3.'">'.$count3.'</span></a>';
    }

    if(isset($val['share-tw'])){
        $share_buttons .= '<a rel="nofollow noopener" href="https://twitter.com/share?url='.$url.'&text='.$title.'" onclick="go(\'https://twitter.com/share?url='.$url.'&text='.$title.'\'); return false;" class="share-item tw" data-type="tw" title="'. __( 'Odnoklassniki', 'share-computy' ).'" data-title="'. __( 'Twitter', 'share-computy' ).'">
     <div class="icon"></div>
    <span class="value" data-value="'.$count4.'">'.$count4.'</span></a>';
    }

    if(isset($val['share-tg'])){
        $share_buttons .= '<a rel="nofollow noopener"  href="https://telegram.me/share/url?url='.$url.'&text='.$title.' '.$desc.'"  onclick="go(\'https://telegram.me/share/url?url='.$url.'&text='.$title.' '.$desc.'\'); return false;"class="share-item tg" data-type="tg" title="'. __( 'Telegram', 'share-computy' ).'" data-title="'. __( 'Telegram', 'share-computy' ).'">
     <div class="icon"></div>
    <span class="value" data-value="'.$count5.'">'.$count5.'</span></a>';
    }

    if(isset($val['share-whatsapp'])){
        $share_buttons .= '<a rel="nofollow noopener" href="whatsapp://send?text='.$url.'" onclick="go(\'whatsapp://send?text='.$url.'\'); return false;" data-action="share/whatsapp/share"  class="share-item whatsapp" data-type="whatsapp" title="'. __( 'Whatsapp', 'share-computy' ).'" data-title="'. __( 'Whatsapp', 'share-computy' ).'">
     <div class="icon"></div>
    <span class="value" data-value="'.$count6.'">'.$count6.'</span></a>';
    }

    if(isset($val['share-viber'])){
        $share_buttons .= '<a rel="nofollow noopener"  href="viber://forward?text='.$url.' '.$desc.'" onclick="go(\'viber://forward?text='.$url.' '.$desc.'\'); return false;" class="share-item viber" data-type="viber" title="'. __( 'Viber', 'share-computy' ).'" data-title="'. __( 'Viber', 'share-computy' ).'">
     <div class="icon"></div>
    <span class="value" data-value="'.$count7.'">'.$count7.'</span></a>';
    }
    return '
<script>
function go(addr) {
        window.open(addr,"MyWin", "menubar=yes, width=860, height=470, top="+((screen.height-470)/2)+",left="+((screen.width-860)/2)+", resizable=yes, scrollbars=no, status=yes");
    }
</script>
    <div id="share_computy" >
    <div class="title_share_computy">'.$title_share.'</div>
     <input class="vashvote" name="vashvote" type="hidden" value="'.$golos_stoit.'">
     <input class="sesid" name="sesid" type="hidden" value="'.$sesid.'">
     <input class="postid" name="postid" type="hidden" value="'.$post_id.'">
      '.$share_buttons.'
    </div> 
           ';
}