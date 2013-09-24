<?php

include '../ayar/ayarlar.php';

//if(yoneticiMi()){

$k="";
if(isset($_SESSION['kullanici_adi']))
$k = $_SESSION['kullanici_adi'];
$feed = array();
$g = $_POST['g'];
$g_fbid = facebookIDGetir($g);
$feedtype = $_POST['f'];

if($feedtype=="hepsi" || $feedtype=="facebook"){
	// facebook data
	$fb = array();
	$g_fbid = facebookIDGetir($g);
	$fbobj = facebookNesnesiGetir();
	$fb_feed_ = $fbobj->api('/'.$g_fbid.'/feed');
	$fb['feed'] = $fb_feed_['data'];
	$fb['feedcount'] = count($fb['feed']);
	for($i=0;$i<$fb['feedcount'];$i++){
		$fb['feed'][$i]['kimdir_time'] = strtotime($fb['feed'][$i]['created_time']); // time converting
		$fb['feed'][$i]['kimdir_type'] = "facebook"; // feed type identifier
		$fb['feed'][$i]['kimdir_item_url'] = "http://www.facebook.com/".str_replace("_","/posts/",$fb['feed'][$i]['id']);
		$fb['feed'][$i]['kimdir_s_uid'] = $g_fbid;
	}
	$feed = array_merge($feed,$fb['feed']);
}

if($feedtype=="hepsi" || $feedtype=="twitter"){
	$tw = array(); 
	$twobj = twitterNesnesiGetir($g);
	$code = $twobj->request('GET', $twobj->url('1/statuses/user_timeline'), array(
		'include_entities' => '1',
		'include_rts'      => '1',
		'screen_name'      => sosyalAgGetir($g,"twitter"),
		'count'            => 20
	));
	if($code == 200){
		$tw['feed'] = json_decode($twobj->response['response'],true);
		$tw['feedcount'] = count($tw['feed']);

		for($i=0;$i<$tw['feedcount'];$i++){
			$tw['feed'][$i]['kimdir_time'] = strtotime($tw['feed'][$i]['created_at']); // time converting
			$tw['feed'][$i]['kimdir_type'] = "twitter"; // feed type identifier
			if(isset($tw['feed'][$i]['retweeted_status'])){
				$tw['feed'][$i]['kimdir_item_url'] = "http://twitter.com/".$tw['feed'][$i]['retweeted_status']['user']['screen_name']."/status/".$tw['feed'][$i]['retweeted_status']['id'];
				foreach($tw['feed'][$i]['retweeted_status']['entities']['urls'] as $rtt) // urls
					$tw['feed'][$i]['retweeted_status']['text'] = str_replace($rtt['url'],"<a href=\"".$rtt['url']."\" target=\"_blank\">".$rtt['display_url']."</a>",$tw['feed'][$i]['retweeted_status']['text'],$asdasd);
				foreach($tw['feed'][$i]['retweeted_status']['entities']['hashtags'] as $rtt) // hashtags
					$tw['feed'][$i]['retweeted_status']['text'] = str_replace("#".$rtt['text'],"<a href=\"http://twitter.com/#!/search/%23".$rtt['text']."\" target=\"_blank\">#".$rtt['text']."</a>",$tw['feed'][$i]['retweeted_status']['text'],$asdasd);
				foreach($tw['feed'][$i]['retweeted_status']['entities']['user_mentions'] as $rtt) // mentions
					$tw['feed'][$i]['retweeted_status']['text'] = str_replace("@".$rtt['screen_name'],"<a href=\"http://twitter.com/#!/".$rtt['screen_name']."\" target=\"_blank\" title=\"".$rtt['name']."\">@".$rtt['screen_name']."</a>",$tw['feed'][$i]['retweeted_status']['text'],$asdasd);
			}else{
				foreach($tw['feed'][$i]['entities']['urls'] as $rtt) // urls
					$tw['feed'][$i]['text'] = str_replace($rtt['url'],"<a href=\"".$rtt['url']."\" target=\"_blank\">".$rtt['display_url']."</a>",$tw['feed'][$i]['text'],$asdasd);
				foreach($tw['feed'][$i]['entities']['hashtags'] as $rtt) // hashtags
					$tw['feed'][$i]['text'] = str_replace("#".$rtt['text'],"<a href=\"http://twitter.com/#!/search/%23".$rtt['text']."\" target=\"_blank\">#".$rtt['text']."</a>",$tw['feed'][$i]['text'],$asdasd);
				foreach($tw['feed'][$i]['entities']['user_mentions'] as $rtt) // mentions
					$tw['feed'][$i]['text'] = str_replace("@".$rtt['screen_name'],"<a href=\"http://twitter.com/#!/".$rtt['screen_name']."\" target=\"_blank\" title=\"".$rtt['name']."\">@".$rtt['screen_name']."</a>",$tw['feed'][$i]['text'],$asdasd);
				$tw['feed'][$i]['kimdir_item_url'] = "http://twitter.com/".$tw['feed'][$i]['user']['screen_name']."/status/".$tw['feed'][$i]['id'];
			}
		}
		$feed = array_merge($feed,$tw['feed']);
	} else {
		echo "Tweetler çekilirken bir sorun oluştu.";
	}
}

if($feedtype=="hepsi" || $feedtype=="foursquare"){
	// foursquare data
	$foursquare = null;
	$fs = array();
	if(serviseYetkiVermisMi($g,"foursquare")){
		$foursquare = true;
		$fsobj = foursquareYetkiliNesnesiGetir($g);
		$fsusr = $fsobj->get("/users/self");
		$fs_user = object_to_array($fsusr->response);
		$fs_username = $fs_user['user']['firstName']." ".$fs_user['user']['lastName'];
		$fs_userphoto = $fs_user['user']['photo'];
		$params = array("v"=>"20120608"); // for big size venue category image
		$creds = $fsobj->get("/users/self/checkins",$params);
		$fs_feed_ = object_to_array($creds->response);
		$fs['feed'] = $fs_feed_['checkins']['items'];
		$fs['feedcount'] = count($fs_feed_['checkins']['items']);
		for($i=0;$i<$fs['feedcount'];$i++){
			$fs['feed'][$i]['kimdir_time'] = $fs['feed'][$i]['createdAt']; // time converting
			$fs['feed'][$i]['kimdir_type'] = "foursquare"; // feed type identifier
			$fs['feed'][$i]['username'] = $fs_username;
			$fs['feed'][$i]['userphoto'] = $fs_userphoto;
			$fs['feed'][$i]['kimdir_s_uid'] = $fs_user['user']['id'];
			$fs['feed'][$i]['kimdir_item_url'] = "http://foursquare.com/user/".$fs_user['user']['id']."/checkin/".$fs['feed'][$i]['id'];
		}
		$feed = array_merge($feed,$fs['feed']);
	} else {
		$foursquare = false;
		$fs['auth_url'] = foursquareYetkiAdresiYarat("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],$ANASAYFA_URL);
	}
}
if($feedtype=="hepsi"){
	usort($feed, "tarihSirala"); // order by time
}
?>
<?php foreach($feed as $d){ ?>
						<li>
<?php 	if($d['kimdir_type']=="twitter"){ ?>
<?php 		if(isset($d['retweeted_status'])){ ?>
							<div class="fl-left">
								<img class="profil_icerik_img lazy" src="img/hizmet-twitter.png" data-original="<?php echo $d['retweeted_status']['user']['profile_image_url']; ?>" />
							</div>
							<div class="fl-left singlepost">
								<span class="profil_hesap">RT <a href="http://twitter.com/<?php echo $d['retweeted_status']['user']['screen_name']; ?>" target="_blank">@<?php echo $d['retweeted_status']['user']['screen_name']; ?></a>
									<em class="feed_tarih"><a href="<?php echo $d['kimdir_item_url']; ?>" target="_blank"><?php echo tarihYaz($d['kimdir_time'],true); ?></a></em>
								</span>
								<?php echo $d['retweeted_status']['text']; ?>
							</div>
							<div class="fl-right">
								<img class="profil_icerik_hizmet" src="img/hizmet-twitter.png" alt="" />
							</div>
<?php 		} else { ?>
							<div class="fl-left">
								<img class="profil_icerik_img lazy" src="img/hizmet-twitter.png" data-original="<?php echo $d['user']['profile_image_url']; ?>" alt="" />
							</div>
							<div class="fl-left singlepost">
								<span class="profil_hesap">
									<a href="http://twitter.com/<?php echo $d['user']['screen_name']; ?>" target="_blank">@<?php echo $d['user']['screen_name']; ?></a>
									<em class="feed_tarih"><a href="<?php echo $d['kimdir_item_url']; ?>" target="_blank"><?php echo tarihYaz($d['kimdir_time'],true); ?></a></em>
								</span>
								<?php echo $d['text']; ?>
							</div>
							<div class="fl-right">
								<img class="profil_icerik_hizmet" src="img/hizmet-twitter.png" alt="" />
							</div>
<?php 		}?>
<?php 	} else if($d['kimdir_type']=="foursquare"){ ?>
							<div class="fl-left">
								<img class="profil_icerik_img lazy" src="img/hizmet-foursquare.png" data-original="<?php echo $d['venue']['categories'][0]['icon']['prefix'].$d['venue']['categories'][0]['icon']['sizes'][3].$d['venue']['categories'][0]['icon']['name']; ?>" alt="<?php echo $d['venue']['categories'][0]['name']; ?>" />
							</div>
							<div class="fl-left singlepost">
								<span class="profil_hesap">
									<a href="http://foursquare.com/user/<?php echo $d['kimdir_s_uid']; ?>"><?php echo $d['username']; ?></a>
									<em class="feed_tarih"><a href="<?php echo $d['kimdir_item_url']; ?>" target="_blank"><?php echo tarihYaz($d['kimdir_time'],true); ?></a></em>
								</span><?php 
								if(isset($d['shout']))
									echo "\"".$d['shout']."\" ";
								?>
								@ <a href="http://foursquare.com/v/<?php echo $d['venue']['id']; ?>"><?php echo $d['venue']['name']; ?></a><br />
								<i><?php 
								if(isset($d['venue']['location']['city']) && isset($d['venue']['location']['state'])) 
									echo "(".$d['venue']['location']['city'].", ".$d['venue']['location']['state'].")";
								?></i>
							</div>
							<div class="fl-right">
								<img class="profil_icerik_hizmet" src="img/hizmet-foursquare.png" alt="" />
							</div>
<?php 	} else if($d['kimdir_type']=="facebook"){ ?>
							<div class="fl-left">
								<img class="profil_icerik_img lazy" src="img/hizmet-facebook.png" data-original="https://graph.facebook.com/<?php echo $d['from']['id']; ?>/picture" />
							</div>
							<div class="fl-left singlepost">
								<span class="profil_hesap"><a href="http://www.facebook.com/profile.php?id=<?php echo $d['from']['id']; ?>" target="_blank"><?php echo $d['from']['name']; ?></a>
								<em class="feed_tarih"><a href="<?php echo $d['kimdir_item_url']; ?>" target="_blank"><?php echo tarihYaz($d['kimdir_time'],true); ?></a></em>
								</span>
<?php 		if(isset($d['message'])){ ?>
								<span class="feed_text"><?php echo $d['message']; ?></a>
<?php 		} else if(isset($d['story'])){ ?>
								<span class="feed_text"><?php echo $d['story']; ?></a>
<?php 		} else if(isset($d['name'])){ ?>
								<span class="feed_text"><?php echo $d['name']; ?></a>
<?php 		} ?>
<?php 		if(isset($d['picture'])){ ?>
								<br /><img class="postimg lazy" src="<?php echo $ANASAYFA_URL."img/logoOrta.png"; ?>" data-original="<?php echo $d['picture']; ?>" />
<?php 		} ?>
							</div>
							<div class="fl-right">
								<img class="profil_icerik_hizmet" src="img/hizmet-facebook.png" alt="" />
							</div>
<?php 	} ?>
						</li>
<?php } ?>
