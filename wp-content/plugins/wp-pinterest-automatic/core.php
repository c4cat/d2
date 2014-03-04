<?php

// instant echo for debugging purpose 
if(!function_exists('echome')){
	function echome($val){
		echo str_repeat(' ',1024*64);
		echo $val;
	}
}

//wp-load
@include('../../../wp-load.php');
 
 
// spintax
require_once('inc/class.spintax.php');
 
/* ------------------------------------------------------------------------*
* Auto Link Builder Class
* ------------------------------------------------------------------------*/
class pinterest{
public $ch='';
public $db='';
public $spintax='';

/* ------------------------------------------------------------------------*
* Class Constructor
* ------------------------------------------------------------------------*/
function pinterest(){
 	//plugin url
 	
	//db 
	global $wpdb;
	$this->db=$wpdb;
	$this->db->show_errors();
	@$this->db->query("set session wait_timeout=28800");


	$this->ch = curl_init();

	/*verboxe	
	*/
	$verbose=fopen( dirname(__FILE__).'/verbose.txt', 'w');
	curl_setopt($this->ch, CURLOPT_VERBOSE , 1);
	curl_setopt($this->ch, CURLOPT_STDERR,$verbose);
	
	curl_setopt($this->ch, CURLOPT_HEADER,1);
	curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($this->ch, CURLOPT_TIMEOUT,20);
	curl_setopt($this->ch, CURLOPT_REFERER, 'https://pinterest.com/login/');
	curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8');
	curl_setopt($this->ch, CURLOPT_MAXREDIRS, 5); // Good leeway for redirections.
    curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 0); // Many login forms redirect at least once.
	curl_setopt($this->ch, CURLOPT_COOKIEJAR , "cookie.txt"); 	
	curl_setopt($this->ch, CURLOPT_HEADER, 1);
	curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
	
	//spintax
	$this->spintax = new Spintax();
	
	$this->log('ini','Pinterest automatic started ...');
}
 

 
/* ------------------------------------------------------------------------*
* Pinterest Post
* ------------------------------------------------------------------------*/
function pinterest_post($post_id,$pin_text,$pin_board) {
	
	$original_pin=$pin_board;
	
	if(!is_numeric($pin_board)){
		//get id 
		$pin_board=$this->pinterest_getboard($pin_board);
		
		if(!is_numeric($pin_board)){
			
			$this->log('Error','Failed to get id for board url cancel pin');
			echo '<br>Failed to get board id';
			return false;
			
		}else{
			
			//update the id 
			$query="update wp_amazonpin_camps set camp_pinterest_board = '$pin_board' where camp_pinterest_board ='$original_pin'  ";
			$this->db->query($query);
			
		}		
		
	}
	

	
	//verify account
	$puser=get_option('wp_amazonpin_pu','');
	$ppass=get_option('wp_amazonpin_pp','');
	
	if(trim($puser)=='' || trim($ppass)== '' ){
		echo '<br>No pinterest Account added';
		return false;
	}
	
	//get first image from post or return
	$post=get_post($post_id);
	$html=$post->post_content;
	$post_link=get_permalink($post->ID); 
	
	 
	$images = array();
	
    if (stripos($html, '<img') !== false) {
            $imgsrc_regex = '#<\s*img [^\>]*src\s*=\s*(["\'])(.*?)\1#im';
            preg_match($imgsrc_regex, $html, $matches);
            unset($imgsrc_regex);
            unset($html);
            
            if (is_array($matches) && !empty($matches)) {
                $img= $matches[2];
            } else {
                //alternate added image
                $img=get_option('product_img','');
				                
            }
            
            if(trim($img) == '') {
            	echo '<br>No Images found to pin';
            	return false;
            }
            
            //let's pin 
            $tocken=$this->pinterest_login($puser,$ppass);
            
            if(trim($tocken) != ''){
            	$this->pinterest_pin($tocken,$pin_board,$pin_text,$post_link,$img);
            }else{
            	echo '<br>Faild to login canel pinning...';
            }
            
            
     }  

	
}
 

function pinterest_getboards(){
	//we already logged get https://pinterest.com/settings/
	$user=get_option('wp_pinterest_automatic_username',1);
	$sess=get_option('wp_pinterest_automatic_session',1);
	
	$this->log('Fetching boards >> after login','fetching for user '.$user);
	
	//get the boards url: http://pinterest.com/resource/NoopResource/get/?data={%22options%22%3A{}%2C%22module%22%3A{%22name%22%3A%22UserBoards%22%2C%22options%22%3A{%22username%22%3A%22mganna%22%2C%22secret_board_count%22%3A0}%2C%22append%22%3Afalse%2C%22errorStrategy%22%3A0}%2C%22context%22%3A{%22app_version%22%3A%22439f%22}}
	//old url: $url = "http://pinterest.com/resource/NoopResource/get/?data={%22options%22%3A{}%2C%22module%22%3A{%22name%22%3A%22UserBoards%22%2C%22options%22%3A{%22username%22%3A%22$user%22%2C%22secret_board_count%22%3A0}%2C%22append%22%3Afalse%2C%22errorStrategy%22%3A0}%2C%22context%22%3A{%22app_version%22%3A%22439f%22}}";
	$url= "http://www.pinterest.com/resource/NoopResource/get/?data=%7B%22options%22%3A%7B%7D%2C%22module%22%3A%7B%22name%22%3A%22PinCreate%22%2C%22options%22%3A%7B%22image_url%22%3A%22http%3A%2F%2Ffc05.deviantart.net%2Ffs71%2Ff%2F2012%2F002%2F2%2Fa%2Fangry_birds_png_by_christabelcstr-d4l53ez.png%22%2C%22action%22%3A%22create%22%2C%22method%22%3A%22scraped%22%2C%22link%22%3A%22http%3A%2F%2Ffc05.deviantart.net%2Ffs71%2Ff%2F2012%2F002%2F2%2Fa%2Fangry_birds_png_by_christabelcstr-d4l53ez.png%22%7D%2C%22append%22%3Afalse%2C%22errorStrategy%22%3A0%7D%2C%22context%22%3A%7B%22app_version%22%3A%228c5407c%22%7D%7D&source_url=%2Fpin%2Ffind%2F%3Furl%3Dhttp%253A%252F%252Ffc05.deviantart.net%252Ffs71%252Ff%252F2012%252F002%252F2%252Fa%252Fangry_birds_png_by_christabelcstr-d4l53ez.png&module_path=App%28%29%3EImagesFeedPage%28resource%3DFindPinImagesResource%28url%3Dhttp%3A%2F%2Ffc05.deviantart.net%2Ffs71%2Ff%2F2012%2F002%2F2%2Fa%2Fangry_birds_png_by_christabelcstr-d4l53ez.png%29%29%3EGrid%28%29%3EGridItems%28%29%3EPinnable%28url%3Dhttp%3A%2F%2Ffc05.deviantart.net%2Ffs71%2Ff%2F2012%2F002%2F2%2Fa%2Fangry_birds_png_by_christabelcstr-d4l53ez.png%2C+type%3Dpinnable%2C+link%3Dhttp%3A%2F%2Ffc05.deviantart.net%2Ffs71%2Ff%2F2012%2F002%2F2%2Fa%2Fangry_birds_png_by_christabelcstr-d4l53ez.png%29&_=1370428493460";
	
	 
	//curl get
	$x='error';
	
	curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
	curl_setopt($this->ch, CURLOPT_URL, trim($url));
	while (trim($x) != ''  ){
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array("X-Requested-With:XMLHttpRequest"));
		curl_setopt($this->ch,CURLOPT_COOKIE,'_pinterest_sess="'.$sess.'"');
		$exec=curl_exec($this->ch);
		$x=curl_error($this->ch);
		//echo $x;
	}
	

	//extract boards ids and names : "board_id": "85638899103223401", "board_name": "Books Worth Reading"
	//old preg_match_all("{BoardCoverEditPage\", \"options\": \{\"board_id\": \"(.*?)\", \"board_name\": \"(.*?)\"}",$exec,$matches,PREG_PATTERN_ORDER);
	preg_match_all('/data-id=\\\"(.*?)\\\"/s',$exec,$matches,PREG_PATTERN_ORDER);
	
	@$ids=$matches[1];
	@$ids=array_unique($ids);
	
 
	
	
	//\n\n                            Products I Love\n                        <
	preg_match_all('{</div>\\\\n\\\\n                            (.*?)\\\\n                    </l}',$exec,$matches2,PREG_PATTERN_ORDER);
	
	@$titles=$matches2[1];
	
 	
	if (count($ids)>=1){
	
		update_option('wp_pinterest_boards',array('ids'=> $ids , 'titles' => $titles ));
	
		$res['ids']=$ids;
		$res['titles']=$titles;
		$res['status']='success';
		
		$this->log('Fetching boards >> Success',count($ids) . ' boards fetched successfully ');
		
	}else{
	
		$res['status']='fail';
		$this->log('Fetching boards >> Fail','failed to fetch boards');
	}
	
	print_r(json_encode($res));
}

/* ------------------------------------------------------------------------*
* Get board id from url
* ------------------------------------------------------------------------*/
function pinterest_getboard($board_url){
	if (trim($board_url)== '') return false;

	//curl ini
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER,0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_TIMEOUT,20);
	curl_setopt($ch, CURLOPT_REFERER, 'http://www.bing.com/');
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8');
	curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // Good leeway for redirections.
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Many login forms redirect at least once.
	curl_setopt($ch, CURLOPT_COOKIEJAR , "cookie2.txt");
	
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	
   
		//Get
	$x='error';
	while (trim($x) != ''  ){
		$url=$board_url;
		curl_setopt($ch, CURLOPT_HTTPGET, 1);
		curl_setopt($ch, CURLOPT_URL, trim($url));
		$exec=curl_exec($ch);
		echo $x=curl_error($ch);
	}
	
	//echo $exec;
	
	preg_match_all("{var board = (.*?);}",$exec,$matches,PREG_PATTERN_ORDER);
	$res=$matches[1];
	$id=$res[0];
	echo '<br>Board id:'.$id;
	
	 
	
	return $id;
		
}
 
/* ------------------------------------------------------------------------*
* Pinterest Log 
* ------------------------------------------------------------------------*/
function pinterest_login($email,$pass,$staylogged=true){
	
	$this->log('Login','Trying to login for email : '.$email );
	
	//check if we are still logged in if so just return the tocken 
	$oldsession=get_option('wp_pinterest_automatic_session','');
	
	if(trim($oldsession) != ''  && $staylogged == true  ){
		//good news we already logged in before let's check if the login still active
		 
		//curl get
		$x='error';
		$url='http://www.pinterest.com/';
		curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
		curl_setopt($this->ch, CURLOPT_URL, trim($url));
		curl_setopt($this->ch,CURLOPT_COOKIE, '_pinterest_sess="'.$oldsession.'"');
		while (trim($x) != ''  ){
			$exec=curl_exec($this->ch);
			$x=curl_error($this->ch);
		}

		if (stristr($exec,'find_friends')){
			//another good news we are still logged in let's return the csfrtocken
			//get csrftocken : csrftoken=Qpmfgu25x4iuph7CqdBONUDcFGrRDYLN;
			preg_match_all("{csrftoken=(.*?);}",$exec,$matches,PREG_PATTERN_ORDER);
			$resz=$matches[1];
			$csrftoken=$resz[0];
			$user=get_option('wp_pinterest_automatic_username','');
			$this->log('Login >> Success', 'Pinterest login success* username is : '.$user);
			$this->log('Login >> csrftocken',$csrftoken);
			
			
			
			return $csrftoken;
			
		}
		
		
	}
	
	/*
	//get latest session this may keep us logged in 
	$query="select * from wp_pinterest_automatic where action like '%pinterest_sess:post login' limit 1";
	
	$rows=$this->db->get_results($query);
	$row=$rows[0];
	$session=$row->data;
	
	//curl get
	$x='error';
	$url='http://www.pinterest.com/';
	curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
	curl_setopt($this->ch, CURLOPT_URL, trim($url));
	curl_setopt($this->ch,CURLOPT_COOKIE, '_pinterest_sess="'.$session.'"');
	while (trim($x) != ''  ){
		$exec=curl_exec($this->ch);
		 $x=curl_error($this->ch);
	}
	
	print_r($exec);
	exit;
	*/
	
	
	
	
	$email=urlencode($email);
	$pass=urlencode($pass);
			
   	// GET login page : https://pinterest.com/login/ 
 	$url='https://pinterest.com/login/?next=%2Flogin%2F';
 	$url='https://www.pinterest.com/login/?next=%2Flogin%2F';
 	
 	//Get
	$x='error';
	while (trim($x) != ''  ){
		curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
		curl_setopt($this->ch, CURLOPT_URL, trim($url));
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
		$exec=curl_exec($this->ch);
		$x=curl_error($this->ch);
		if($x != 'error' & trim($x) != ''){
			$this->log('Curl Try Error',$x);
		}
 
	}
	
	if(stristr($exec, 'detected a bot')){
		$this->log('IP banned'	,'your server ip is blacklisted from pinterest this means connection with pinterest fails ');
		return false;
	}
	
	//get csrftocken : csrftoken=Qpmfgu25x4iuph7CqdBONUDcFGrRDYLN;
	preg_match_all("{csrftoken=(.*?);}",$exec,$matches,PREG_PATTERN_ORDER);
	
	
	$resz=$matches[1];
	$csrftoken=$resz[0];
	$this->log('Login >> csrftocken',$csrftoken);
	

	
	
	//extract  _pinterest_sess parameter
	preg_match_all("{_pinterest_sess=\"(.*?)\"}",$exec,$matches,PREG_PATTERN_ORDER);
	$res=$matches[1];
	@$sess=$res[0];
	$this->log('Login >> _pinterest_sess',$sess);
	
	
	if( trim($sess) == ''){
		$this->log('Login >> Error','Failed to fetch Pinterest session num cancelling pinning this time ... ');
		return false;
	}	
	
	
	//extract ' name='csrfmiddlewaretoken' value='9dd872d04d23903c8cd1287998b9ea5d'
	preg_match_all("{name='csrfmiddlewaretoken' value='(.*?)'}",$exec,$matches,PREG_PATTERN_ORDER);
 	$res=$matches[1];
	@$tocken=$res[0];
	
 
	//check if the new login form or the old one
	if(trim($tocken) == ''){
		$this->log('Login >> Login form type ? ','The new updated one');
		
		// post
		$curlurl = "https://www.pinterest.com/resource/UserSessionResource/create/";
		//$curlurl= "http://www.pinterest.com/resource/UserSessionResource/creates/";
		$curlpost="data=%7B%22options%22%3A%7B%22username_or_email%22%3A%22$email%22%2C%22password%22%3A%22$pass%22%7D%2C%22context%22%3A%7B%22app_version%22%3A%22cc40cb7%22%7D%7D&source_url=%2Flogin%2F&module_path=App()%3ELoginPage()%3ELogin()%3EButton(class_name%3Dprimary%2C+text%3DLog+in%2C+type%3Dsubmit%2C+tagName%3Dbutton%2C+size%3Dlarge)";
		$curlpost="source_url=%2Flogin%2F&data=%7B%22options%22%3A%7B%22username_or_email%22%3A%22$email%22%2C%22password%22%3A%22$pass%22%7D%2C%22context%22%3A%7B%22app_version%22%3A%22f94de18%22%7D%7D&module_path=App()%3ELoginPage()%3ELogin()%3EButton(class_name%3Dprimary%2C+text%3DLog+in%2C+type%3Dsubmit%2C+tagName%3Dbutton%2C+size%3Dlarge)"; 
		$x='error';
	
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array("X-CSRFToken:$csrftoken","HOST:www.pinterest.com","X-NEW-APP:1","Referer:https://www.pinterest.com/login/","X-Requested-With:XMLHttpRequest" ));
		curl_setopt($this->ch,CURLOPT_COOKIE,'_pinterest_sess="'.$sess.'";csrftoken='.$csrftoken);
		curl_setopt($this->ch, CURLOPT_URL, $curlurl);
		curl_setopt($this->ch, CURLOPT_POST, true);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $curlpost);
		
		while (trim($x) != ''  ){
				$exec=curl_exec($this->ch);
				$x=curl_error($this->ch);
		}
		
 
	  
		 
		//full_name 
		if(stristr($exec,'full_name')){
			$this->log('Login >> New form login ','Success');
			
			//leave it to the login check .
			
			
			
		}else{
			 
			$this->log('Login >> New form login ','<span style="color:red">{Fail} </span>'.urlencode($exec));
			return false;
		}
		
	}else{
		
		$this->log('Login >> Login form type:','The old one');
		
			
		//extract ' name='_ch' value='9dd872d04d23903c8cd1287998b9ea5d'
		preg_match_all("{name='_ch' value='(.*?)'}",$exec,$matches,PREG_PATTERN_ORDER);
		$res=$matches[1];
		$_ch=$res[0];
		$this->log('_ch',$_ch);
			
		//Post login email=sweetheatmn%40yahoo.com&password=01292 &next=%2F&csrfmiddlewaretoken=8e0371f9dac6d39b1fe26e00a0595606
			
		$curlurl='https://pinterest.com/login/?next=%2Flogin%2F';
		//$curlurl='http://www.almanhag.com/atef/php/debug/index.php';
			
		$x='error';
			
		while (trim($x) != ''  ){
		
			//curl post
			$curlpost="email=$email&password=$pass&next=%2F&csrfmiddlewaretoken=$tocken&_ch=$_ch";
		
			curl_setopt($this->ch,CURLOPT_COOKIE,'_pinterest_sess="'.$sess.'";csrftoken='.$tocken);
			curl_setopt($this->ch, CURLOPT_URL, $curlurl);
			curl_setopt($this->ch, CURLOPT_POST, true);
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, $curlpost);
		
		
			$x='error';
			while (trim($x) != ''  ){
				$exec=curl_exec($this->ch);
				$x=curl_error($this->ch);
				$this->log('Curl Try Error',$x);
			}
		
		}
			
			
		
	}
	
	$url='http://www.pinterest.com/';
	
	//extract
	preg_match_all("{_pinterest_sess=\"(.*?)\"}",$exec,$matches,PREG_PATTERN_ORDER);
	$res=$matches[1];
	@$sess=$res[0];
		
		
	$this->log('Login >> _pinterest_sess:post login',$sess);
		
	if( trim($sess) == ''){
		//echo '<br>Session Parameter failed';
		$this->log('Login >> Error','Failed to fetch Pinterest session num 2 with unexpected response '.(str_replace(';','-',$exec)));
	
		return false;
	}
 			
	//check if login success or not 	
	$x='error';
	while (trim($x) != ''  ){
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array( "X-Requested-With:"));
		curl_setopt($this->ch,CURLOPT_COOKIE,"_pinterest_sess=\"$sess\";__utma=229774877.1960910657.1333904477.1333904477.1333904477.1; __utmb=229774877.1.10.1333904477; __utmc=229774877; __utmz=229774877.1333904477.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __utmv=229774877.|2=page_name=login_screen=1");
		curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
		curl_setopt($this->ch, CURLOPT_URL, trim($url));
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
		$exec=curl_exec($this->ch);
				$x=curl_error($this->ch);
		if($x != 'error' & trim($x) != ''){
			$this->log('Curl Try Error',$x);
		}

	}
	
	 
	 
	 if (stristr($exec,'find_friends')){ 	
	 	
	 	
	 	//getting username and saving it . ex: "username": "mganna"
		//echo $exec;
	 	
	 	preg_match_all("{is_employee\": false, \"username\": \"(.*?)\"}",$exec,$matches,PREG_PATTERN_ORDER);
	 	
	 	$res=$matches[1];
	 	$user=$res[0];
	 	update_option('wp_pinterest_automatic_username',$user);
	 	 
	 	$this->log('Login >> Success', 'Pinterest login success username is : '.$user);
	 	update_option('wp_pinterest_automatic_session',$sess);
	 	return $csrftoken;
	 }else{
	 	$this->log('Login >> Failed login ', 'Pinterest Login Failed');
	 	return false;
	 }
	
}

function pinterest_pin($tocken,$board,$details,$link,$imgsrc){
	
	$this->log('Pinning','Trying to pin an  <a href="'.urlencode($imgsrc) .'" >image</a>');
	 
	  
	 $details= urlencode(stripslashes($details));
	 
	
	//curl post url http://pinterest.com/resource/PinResource/create/
	$curlurl='http://www.pinterest.com/resource/PinResource/create/';
	
	$original_link=$link;
	$original_src=$imgsrc;

	if( !  ( stristr($link, 'http') ||  stristr($link, 'www') ) ){
		$link= $_SERVER['HTTP_HOST'] .$link;
	}
	
	if( ! ( stristr($imgsrc, 'http') ||  stristr($imgsrc, 'www')  ) ){
		$imgsrc= $_SERVER['HTTP_HOST'] .$imgsrc;
	}
	
	
	//curl post data data=%7B%22options%22%3A%7B%22board_id%22%3A%2285638899103223404%22%2C%22description%22%3A%22test%22%2C%22link%22%3A%22http%3A%2F%2Ffc05.deviantart.net%2Ffs71%2Ff%2F2012%2F002%2F2%2Fa%2Fangry_birds_png_by_christabelcstr-d4l53ez.png%22%2C%22image_url%22%3A%22http%3A%2F%2Ffc05.deviantart.net%2Ffs71%2Ff%2F2012%2F002%2F2%2Fa%2Fangry_birds_png_by_christabelcstr-d4l53ez.png%22%2C%22method%22%3A%22scraped%22%7D%2C%22context%22%3A%7B%22app_version%22%3A%2291bf%22%7D%7D&source_url=%2Fpin%2Ffind%2F%3Furl%3Dhttp%253A%252F%252Ffc05.deviantart.net%252Ffs71%252Ff%252F2012%252F002%252F2%252Fa%252Fangry_birds_png_by_christabelcstr-d4l53ez.png&module_path=App()%3EImagesFeedPage(resource%3DFindPinImagesResource(url%3Dhttp%3A%2F%2Ffc05.deviantart.net%2Ffs71%2Ff%2F2012%2F002%2F2%2Fa%2Fangry_birds_png_by_christabelcstr-d4l53ez.png))%3EGrid()%3EPinnable(url%3Dhttp%3A%2F%2Ffc05.deviantart.net%2Ffs71%2Ff%2F2012%2F002%2F2%2Fa%2Fangry_birds_png_by_christabelcstr-d4l53ez.png%2C+link%3Dhttp%3A%2F%2Ffc05.deviantart.net%2Ffs71%2Ff%2F2012%2F002%2F2%2Fa%2Fangry_birds_png_by_christabelcstr-d4l53ez.png%2C+type%3Dpinnable)%23Modal(module%3DPinCreate())
	$curlpost="data=%7B%22options%22%3A%7B%22board_id%22%3A%22$board%22%2C%22description%22%3A%22$details%22%2C%22link%22%3A%22$link%22%2C%22image_url%22%3A%22$imgsrc%22%2C%22method%22%3A%22scraped%22%7D%2C%22context%22%3A%7B%22app_version%22%3A%2291bf%22%7D%7D&source_url=%2Fpin%2Ffind%2F%3Furl%3D$imgsrc&module_path=App()%3EImagesFeedPage(resource%3DFindPinImagesResource(url%3D$imgsrc))%3EGrid()%3EPinnable(url%3D$imgsrc%2C+link%3D$link%2C+type%3Dpinnable)%23Modal(module%3DPinCreate())";
	
	$link=$original_link;
	$imgsrc=$original_src;
	
	$sess=get_option('wp_pinterest_automatic_session',1);
	
    $x='error';
	while (trim($x) != ''  ){
		//curl post
	 	//@curl_setopt($this->ch, CURLOPT_HTTPHEADER, "HOST:pinterest.com");
	 	curl_setopt($this->ch, CURLOPT_HTTPHEADER, array("X-CSRFToken:$tocken","X-Requested-With:XMLHttpRequest"));
	 	curl_setopt($this->ch,CURLOPT_COOKIE,'_pinterest_sess="'.$sess.'";csrftoken='.$tocken);
	 	curl_setopt($this->ch, CURLOPT_REFERER, 'http://pinterest.com/');
		curl_setopt($this->ch, CURLOPT_URL, $curlurl);
		curl_setopt($this->ch, CURLOPT_POST, true);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $curlpost); 
		$exec=curl_exec($this->ch);
		$x=curl_error($this->ch);
		if($x != 'error' & trim($x) != ''){
			$this->log('Curl Try Error',$x);
		}
	}

	 
	if (stristr($exec,'"error": null')){
		
		//extract pin url
		preg_match_all("{\"board\", \"id\": \"(.*?)\"\}, \"id\": \"(.*?)\"\}, \"error\": null}",$exec,$matches,PREG_PATTERN_ORDER);
	 
		$res=$matches[2];
		$pin=$res[0];
		$url= 'http://pinterest.com/pin/'.$pin;
		$this->log('Pinning >> Success',"successful <a href=\"$url\">Pin</a>") ;
		return true;		
	}else{
		
		
	 
		if (stristr($exec,'captcha')){
			
			$this->log('Pinning >> Fail',"Pinterest asked for captcha please login manually to your account do a pin solve the captcha and pinning should back without problem also please don't pin very fast just let it be as a manual pin as posible") ;
			
		}else{
			
            $this->log('Pinning >> Fail',"<span style=\"color:red\">{Fail}</span>Pin fail please make sure you can login and pin manually without problem if the problem still exists please contact the author  <a href=\"http://codecanyon.net/user/DeanDev\">here</a> and describe the problem exactly also copy the code below on your mail if available  <br>".urlencode($exec)) ;			
		}

		
		if (stristr($exec,'combat spam')){
				
			$this->log('Spam Flag',"Pinterest account flagged for spam , we will deactivate pinning for one hour ") ;
			
			$now=time('now');
			
			$deactivetill = $now + 3600 ; //seconds
			
			update_option('wp_pinterest_automatic_deactivate', $deactivetill);

				
		}
		
		
 
		return false;
	}	
	
	
	
	}

/* ------------------------------------------------------------------------*
* Logging Function
* ------------------------------------------------------------------------*/	
function log($type,$data){
	//$now= date("F j, Y, g:i a");
	$now = date( 'Y-m-d H:i:s');
	$data=mysql_real_escape_string($data);
	$query="INSERT INTO wp_pinterest_automatic (action,date,data) values('$type','$now','$data')";
	//echome$query;
	
	
	 
	
	$this->db->query($query);
}
	
 

}//End



/* ------------------------------------------------------------------------*
* Testing the Plugin
* ------------------------------------------------------------------------*/	
$action='';
@$action=$_POST['action'];
if(trim($action) == 'boards'){

	$gm=new pinterest();
	$gm->log('Fetching boards','Trying to fetch boards if login success ');
	$tocken=$gm->pinterest_login($_POST['user'],$_POST['pass'],false);
	 
	if($tocken !=false){
	$gm->pinterest_getboards();
	}else{
		$res['status']='fail';
		print_r(json_encode($res));
	}
		
}

?>