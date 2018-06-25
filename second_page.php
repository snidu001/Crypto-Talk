<!DOCTYPE html>

<html>

<head>

	<title>CryptoTalk!</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/chat.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

	
</head>

<?php

if (version_compare(phpversion(), "5.3.0", ">=") == 1)
  error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
else
  error_reporting(E_ALL & ~E_NOTICE);

require_once ('db_connect.inc.php');
require_once('inc/chat.inc.php');

$servername = 'localhost';
$username = 'admin';
$password = 'M0n@rch$';
$dbname = 'cryptocurrency';
$conn = mysqli_connect($servername, $username, $password, $dbname);
 

$Email = $_SESSION['email'];//mysqli_real_escape_string($conn,$_POST['email']);
    $_SESSION["dm"]=array();
	$_SESSION["grp"]=array();
	$_SESSION["grp1"]=array();
	$_SESSION["test"]=$Email;
$result=mysqli_query($conn,"SELECT username FROM `userinfo` WHERE email='{$Email}'");

if($result){
	while($name=mysqli_fetch_assoc($result))
	{	
	$Name=$name['username'];
	$_SESSION['Name']=$Name;
	}
}
$oSimpleChat = new SimpleChat();


//echo $sChatResult;

//Groups
$query_grp = mysqli_query($conn,"SELECT c.CName FROM channel c INNER JOIN userchannel u ON c.CId=u.CID where u.UserID=(Select UserID from userinfo where Email = '{$Email}')");
           
            $numrows_grp = mysqli_num_rows($query_grp);
			
                if($numrows_grp!=0)
                {
                    while($row_user = mysqli_fetch_assoc($query_grp))
                    {					
                        array_push($_SESSION["grp"],$row_user['CName']);                
                    }
				}
				
            //Direct Message
            $query_dm = mysqli_query($conn,"SELECT UserName FROM userinfo where Email != '{$Email}'");
            
            $numrows_dm = mysqli_num_rows($query_dm);

                if($numrows_dm!=0)
                {
                    while($row_user = mysqli_fetch_assoc($query_dm))
                    {
                        array_push($_SESSION["dm"],$row_user['UserName']);
                         
                    }
                }
            
?>

	<style>
		body,


		h3 {
			color: black;
		}

		input[type="file"] {
			display: none;
		}

		.custom-file-upload {
			border: 1px solid #ccc;
			display: inline-block;
			padding: 6px 6px;
			cursor: pointer;
		}

		.functionbuttons {
			
			background-color: grey;
			
		}
        .speech {border: 1px solid #DDD; width: 300px; padding: 0; margin: 0}
        .speech input {border: 0; width: 240px; display: inline-block; height: 30px;}
        .speech img {float: right; width: 40px }
		

	</style>

	<body>
		<script src="js/jscripts.js"></script>

		<div id="mySidenav" class="sidenav">
			<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
			<div class="card">
				<?php      
        $sql= "SELECT image FROM `userinfo` WHERE email='{$Email}'";
        $result=mysqli_query($conn,$sql);
        while($row=mysqli_fetch_array($result))
            echo "<img src='". $row['image'] ."' Height='90px' Width='90px' style='border-radius: 30%;'>";
    ?>
				<h1>
					<?php
  echo $_SESSION["Name"];
  ?>
				</h1>
				<p>
					<?php
  echo $_SESSION["test"];
  ?>
				</p>

			</div>

			<lv><a class="active">Crypto Groups</a></lv>

			<?php
  $servername = 'localhost';
				$username = 'admin';
				$password = 'M0n@rch$';
				$dbname = 'cryptocurrency';
				$conn = mysqli_connect($servername, $username, $password, $dbname);
  
  $Email = $_SESSION['email'];
  foreach($_SESSION["grp"] as $key=>$value){
	  echo "<lv><a href='?value=" . $value . "'class='ex5'># " . $value . "</a></lv>";
  }
 
$name=$_GET["value"];
      
  $query1 = mysqli_query($conn,"select CID,UserID from userchannel where CID=(select CId from channel where CName='{$name}') and  UserID=(select UserId from userinfo where email='{$Email}')");
  $msgtyp="G";
  
  while ($row1 = mysqli_fetch_array($query1)) {
  $cid= $row1['CID']; 
  $uid= $row1['UserID'];		//Query
  }

  $_SESSION["channelid"]=$cid;
  $_SESSION["channelname"]=$name;
  $_SESSION["usserid"]=$uid;

  $sChatResult = 'Need login before using';
          $sChatResult = $oSimpleChat->acceptDMessages($Email,$name,$msgtyp,$cid,$uid);

?>



				<br><br>
				<lv><a class="active">Direct Messages</a></lv>

				<?php
	 $servername = 'localhost';
				$username = 'admin';
				$password = 'M0n@rch$';
				$dbname = 'cryptocurrency';
				$conn = mysqli_connect($servername, $username, $password, $dbname);
  foreach($_SESSION["dm"] as $key=>$value){
	  echo "<lv><a href='?value=" . $value . "' class='ex5'>@ " . $value . "</a></lv>";  
  }
  $name=$_GET["value"];
      
  $query2 = mysqli_query($conn,"select d.email, s.userid from userinfo d,userinfo s WHERE d.username = '{$name}' and s.UserID=(select UserId from userinfo where email='{$Email}')");
  $msgtyp="D";
  
  while ($row2 = mysqli_fetch_array($query2)) {
  $dmail= $row2['email'];  
  $sid= $row2['userid'];		//Query
  }

  $_SESSION["dmail"]=$dmail;
  $_SESSION["sid"]=$sid;

  $sChatResult = 'Need login before using';
  $sChatResult = $oSimpleChat->acceptDMessages1($Email,$dmail,$sid);

            $verifychat=mysqli_query($conn,"select * from channel where CName='{$name}'");
            $verifychatcount=mysqli_num_rows($verifychat);
            if($verifychatcount>0){
                $_SESSION['chattype']='Group';
            }else{
                unset($_SESSION['chattype']);
            }
            
 ?>

		</div>


		<div id="main">



			<div id="chat_window" style='max-width:99%' ;>



				<div class="chat_main" id="chat_box">
					<script>
					var page = "<?php 
					$page=$_GET["page"];
					echo "$page";
					?>";
				$.ajax({
		url: "messages.php",
		data: "page=" + page,
		success: function (result) {
		console.log(result);
		$("#chat_box").empty();
		$("#chat_box").append(result);
		}
});		
				</script>
				</div>

				<div id="footer">

					<?php
					require_once ('db_connect.inc.php');
                       
						$chaname=$_SESSION['channelname'];
						$chstat = mysqli_query($conn, "SELECT status from `channel` where CName = '{$chaname}'");
						while($chstatus=mysqli_fetch_assoc($chstat))
						{
							$stat=$chstatus['status'];
						}
						
							if($stat=='archived'){
								echo "<h2 style='text-align: center; color:red;'>This channel is archived</h2>";
								
							}else{
                                 $id='id01';
                        $t1='block';
                        $t2='none';
                        $t3='Image';
                        $t4='Image URL';
                        $t5='File';
								echo '
				
				<div id=func_buttons class="functionbuttons">
				<div id="format_buttons" style="margin-bottom:-5px; margin-left:80px">
				
                
                
                

                
                
                
                
                
                
                <!-- Trigger/Open The Modal -->
                            <button id="myBtn"><i class="fa fa-paperclip" aria-hidden="true"></i></button>

                            <!-- The Modal -->
                            <div id="myModal" class="modal">

                              <!-- Modal content -->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <span class="close">&times;</span>
                                  <h2>Insert Image</h2>
                                </div>
                                <div class="modal-body">
                                <h3><b>Attach Image</b></h3>
                                <form action="" method="post" enctype="multipart/form-data">
                                <label style="background-color:Grey;" for="file-upload" class="custom-file-upload" title="Upload image from local">
                                <i class="fa fa-file-image-o" aria-hidden="true"></i></i></label>
				                <input id="file-upload" name="image" value="image" type="file" accept="image/*" onchange="preview_image()" />  
                                <input type="submit" id="subimg" name="submit_image" value="Upload Image"/>
                                <div id="image_preview"></div>
                                </form>
                                <hr>
                                
                                <h3><b>Attach Image using URL</b></h3>
                                <form action="" method="post" enctype="multipart/form-data">
                                <input type="url" class="form-control" name="image_path" placeholder="Enter Image URL" title="To upload image from web inser url">
                                <button id="upload-web" name="post_image_web" title="upload image from web"><i class="fa fa-globe" aria-hidden="true"></i></button>
                                </form>
                                <hr>
                                
                                <h3><b>Attach File</b></h3>
                                <form action="" method="post" enctype="multipart/form-data">
                                <label style="background-color:Grey;" for="file" class="custom-file-upload" title="Upload file from local">
                                <i class="fa fa-paperclip" aria-hidden="true"></i></i></label>
				                <input id="file" name="file" value="file" type="file" size="100MB" accept="*" onchange="uploadfile()" />
                                <input type="submit" id="subfile" name="submit_image" value="Attach File"/>
                                </form>
                                
                                </div>
                                <div class="modal-footer">
                                  <h3></h3>
                                </div>
                              </div>

                            </div>
				
                <button id="boldbutton" title="Bold Text @b b@" onclick="bold_function()"><i class="fa fa-bold" aria-hidden="true"></i></button>
				<button id="italicbutton" title="Italics Text @i i@" onclick="italic_function()"><i class="fa fa-italic" aria-hidden="true"></i></button>
				<button id="codebutton" title="Code Snippet @code code@" onclick="code_function()"><i class="fa fa-code" aria-hidden="true"></i></button>
				<button id="marktxtbutton" title="Highlight text @m m@" onclick="mark_function()"><i class="fa fa-pencil" aria-hidden="true"></i></button>
				<button id="striketxtbutton"  title="Strikethrough text @d d@" onclick="strike_function()"><i class="fa fa-strikethrough" aria-hidden="true"></i></button>
				</div>
				
				<div id="form_div" style="margin-bottom:-5px">
				<form class="submit_form" method="post" enctype="multipart/form-data" >
				
				
				</div>
				

				
				
				
		<textarea rows="1" placeholder="Enter Message here"  id="txt" type="textbox" class="input" name="s_message" placeholder="Type Message" onclick="scrolltobottom()"></textarea>
        
		<img id="img" onclick="startDictation()" src="Images/mic.png" />		
		<input id="submitbtn" type="submit" class="button" value="say" name="s_say" onclick="display()">
					</form> </div>';
									
							}
							?><!--Text Area and submit button along with a mic-->
				</div>

			</div>


			<div id="Horiz_bar">
				<ul>
					<li onclick="openNav()"><a>&#9776; </a></li>
					<lf>
						<li><button style='background:#333; border:none;' onclick="archive_channel()"><img src="Images/acrh.png" width=40px height=40px;/></button>
                        </li>
						
                        <li><a href='./logout.php'>Sign Out</a></li>
					</lf>
                    
<li style="float:right"><a href="./graph.php"><i class="fa fa-line-chart" aria-hidden="true"><label>BTC</label></i></a><a href="./etcgraph.php"><i class="fa fa-line-chart" aria-hidden="true"><label>ETH</label></i></a><a href="./ltcgraph.php"><i class="fa fa-line-chart" aria-hidden="true"><label>LTC</label></i></a><a href="./calc.php"><i class="fa fa-calculator" aria-hidden="true"></i></a><a href="./Helppage.php"><i class="fa fa-question-circle fa-fw" aria-hidden="true"></i></a></li>
<!--Horizonal navigation bar with calculator and graph-->
                    
					<lf style="float:left">
						<li class="dropdown">
							<a href="javascript:void(0)" class="dropbtn">
								<?php
  echo $_SESSION["Name"];
  ?>
							</a>
							<div class="dropdown-content">
								<a href="channel.php">Add Channel</a>
								<?php
	                               $Email = $_SESSION['email'];
	                               $c = $_SESSION["channelid"];
	                               require_once ("db_connect.inc.php");
								    $getustat = mysqli_query($conn, "SELECT usertype from `userinfo` where email = '{$Email}'");		
										while($resll=mysqli_fetch_assoc($getustat))		
										{		
											$ustype=$resll['usertype'];		
										}
								   
	                               $result = mysqli_query($conn, "SELECT CName from `channel` where CreatedBy = '{$Email}' and cid = '{$c}'");
	                               $rows = mysqli_num_rows($result);
	if($rows>0 || $ustype=='superuser'){
		echo "<a href='user.php?value=".$_SESSION["channelname"]."'>Add user(s) to channel</a>";
	}
		
		if($ustype=='superuser'){		
			echo "<a href='removeuser.php?value=".$_SESSION["channelname"]."'>Remove user(s) from channel</a>";
            echo "<a href='removeuseraccount.php'>Remove user account </a>";

	}
         echo "<a href='profile.php?email=" . $Email . "'>Profile and Channel(s)</a>";
		 echo "<a href='channelinfo.php?value=".$_SESSION["channelname"]."'>Channel Details</a>";
	?>

									<?php
     
if(isset($_POST['post_image_web']))
{
 $image_url=$_POST['image_path'];
$ran=rand(1000,100000)."-";
$ext = substr($image_url, strrpos($image_url, '.') + 1);
  if ($ext == "jpg" || $ext == "jpeg" || $ext == "gif" || $ext == "png") {
$db = mysqli_connect("localhost", "admin", "M0n@rch$", "cryptocurrency");
$data = file_get_contents($image_url);
 $target = "Images/".$ran.substr($image_url, strrpos($image_url, '/') + 1);
 $image= substr($target, strrpos($target, '/') + 1);
 $upload =file_put_contents($target, $data);
	
 if($upload) {
     $_SESSION['uuid']=$_SESSION["usserid"];
     
     if($_SESSION['chattype']=='Group'){
    $sql = mysqli_query($db,"INSERT INTO `message` SET `Message`='{$image}', `MessageType`='G', `CId`='{$_SESSION["channelid"]}', messageformat='image', `UserId`='{$_SESSION["usserid"]}', `WId`=1, `Source`='{$_SESSION["test"]}',`Destination`='{$_SESSION["channelname"]}',`lcount`=0, `dcount`=0, `TimeStamp`=CURRENT_TIMESTAMP()");
            mysqli_query($db, $sql);
     }else{
         
         $sql = mysqli_query($db,"INSERT INTO `message` SET `Message`='{$image}', `MessageType`='D', `CId`=NULL, messageformat='image', `UserId`='{$_SESSION["sid"]}', `WId`=1, `Source`='{$_SESSION["test"]}',`Destination`='{$_SESSION["dmail"]}',`lcount`=0, `dcount`=0, `TimeStamp`=CURRENT_TIMESTAMP()");
            mysqli_query($db, $sql);
     }
           echo "<meta http-equiv='refresh' content='0'>";
        }
        else
        {
            echo "<script>alert(\"Sorry! Couldn't upload the image. Please insert url with image extension only.\")</script>";
        }
	  if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $msg = "Image Uploaded Successfully";
            echo "<meta http-equiv='refresh' content='0'>";
        } 
        else {
            $msg = "There Was A problem uploading image";
        }  
  }
	  else {  echo "<script>alert(\"Sorry! Couldn't upload the image. URL should have image type extension.\")</script>";
		}
           
}
    
if (isset($_POST['uploadfile'])) {    

		$ran=rand(1000,100000)."-";
        $target = "files/".basename($ran.$_FILES['file']['name']);
        $db = mysqli_connect("localhost", "admin", "M0n@rch$", "cryptocurrency");
        $file = $ran.$_FILES['file']['name'];
        $type = $_FILES['file']['type'];
		$size = $_FILES['file']['size'];
		
        $id= $_FILES['file']['id'];
        $types = array('text/plain', 'text/html', 'text/css', 'text/javascript', 'audio/midi', 'audio/mpeg', 'audio/webm', 'audio/ogg', 'audio/wav', 'video/webm', 'video/ogg', 'video/mp4', 'application/octet-stream', 'application/pkcs12', 'application/vnd.mspowerpoint', 'application/xhtml+xml', 'application/xml',  'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation');
		
		$imgtypes = array('image/gif', 'image/png', 'image/jpeg', 'image/bmp', 'image/webp');
        
       if(in_array($_FILES['file']['type'], $types)) 
        {
            if($_SESSION['chattype']=='Group'){
            $sql = mysqli_query($db,"INSERT INTO `message` SET `Message`='{$file}', `MessageType`='G', `CId`='{$_SESSION["channelid"]}', messageformat='file', `UserId`='{$_SESSION["usserid"]}', `WId`=1, `Source`='{$_SESSION["test"]}',`Destination`='{$_SESSION["channelname"]}',`lcount`=0, `dcount`=0, `TimeStamp`=CURRENT_TIMESTAMP()");
            mysqli_query($db, $sql);
            }else{
                
                $sql = mysqli_query($db,"INSERT INTO `message` SET `Message`='{$file}', `MessageType`='D', `CId`=NULL, messageformat='file', `UserId`='{$_SESSION["sid"]}', `WId`=1, `Source`='{$_SESSION["test"]}',`Destination`='{$_SESSION["dmail"]}',`lcount`=0, `dcount`=0, `TimeStamp`=CURRENT_TIMESTAMP()");
            mysqli_query($db, $sql);
            }
			echo "<meta http-equiv='refresh' content='0'>";
           
        }
        else
        {
            echo "<script>alert(\"Sorry! Couldn't upload the image. Invalid Format! .\")</script>";
           
        }
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
            $msg = "Image Uploaded Successfully";
            echo "<meta http-equiv='refresh' content='0'>";
        } 
        else {
            $msg = "There Was A problem uploading File";
        }
           
}	
	
	 if (isset($_POST['uploadimage'])) { 
         $ran=rand(1000,100000)."-";
        $target = "Images/".basename($ran.$_FILES['image']['name']);
        $db = mysqli_connect("localhost", "admin", "M0n@rch$", "cryptocurrency");
        $image = $ran.$_FILES['image']['name'];
        $type = $_FILES['image']['type'];
        $id= $_FILES['image']['id'];
        $types = array('image/jpeg', 'image/gif','image/png');
        
       if(in_array($_FILES['image']['type'], $types)) 
        {
            if($_SESSION['chattype']=='Group'){
            $sql = mysqli_query($db,"INSERT INTO `message` SET `Message`='{$image}', `MessageType`='G', `CId`='{$_SESSION["channelid"]}', messageformat='image', `UserId`='{$_SESSION["usserid"]}', `WId`=1, `Source`='{$_SESSION["test"]}',`Destination`='{$_SESSION["channelname"]}',`lcount`=0, `dcount`=0, `TimeStamp`=CURRENT_TIMESTAMP()");
            mysqli_query($db, $sql);
            }else{
                $sql = mysqli_query($db,"INSERT INTO `message` SET `Message`='{$image}', `MessageType`='D', `CId`=NULL, messageformat='image', `UserId`='{$_SESSION["sid"]}', `WId`=1, `Source`='{$_SESSION["test"]}',`Destination`='{$_SESSION["dmail"]}',`lcount`=0, `dcount`=0, `TimeStamp`=CURRENT_TIMESTAMP()");
            mysqli_query($db, $sql);
            }
			echo "<meta http-equiv='refresh' content='0'>";
           
        }
        else
        {
            echo "<script>alert(\"Sorry! Couldn't upload the image. Invalid Format! .\")</script>";
           
        }
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $msg = "Image Uploaded Successfully";
            echo "<meta http-equiv='refresh' content='0'>";
        } 
        else {
            $msg = "There Was A problem uploading image";
        }
           
}
 

        
?>
							</div>
						</li>
						<li class="dropdown">
							<input id="search_box" type="text" name="search" placeholder="Search User." onkeyup="search_user()" />

							<div id="searchlist" class="dropdown-content"></div>

						</li>

					</lf>
				</ul>
			</div>

		</div>
        
        
        
        <!--Script for speech recognition-->
        <script>
                function startDictation() {

                if (window.hasOwnProperty('webkitSpeechRecognition')) {
                    
                   document.getElementById("img").src="https://raw.githubusercontent.com/googlearchive/webplatform-samples/master/webspeechdemo/mic-animate.gif";

                var recognition = new webkitSpeechRecognition();

                recognition.continuous = false;
                recognition.interimResults = false;

                recognition.lang = "en-US";
                recognition.start();

                recognition.onresult = function(e) {
                document.getElementById('txt').value
                                 = e.results[0][0].transcript;
                recognition.stop();
                document.getElementById('sub').submit();
            };

            recognition.onerror = function(e) {
             recognition.stop();
        }

    }
  }
        </script>
        
        
		<script>
			function search_user() {
				var x = document.getElementById("search_box").value;

				//location.href = "showuser.php?inp=" + x;
				$.ajax({
					url: "searchuser.php",
					method: 'GET',
					data: "inp=" + x,
					success: function(result) {
						console.log(result);
						$("#searchlist").empty();
						$("#searchlist").append(result);
					}

				});
			}

			function archive_channel() {
				var chname = "<?php echo $_SESSION["channelname"]; ?>";
				var user = "<?php echo $_SESSION["test"]; ?>"
				location.href = "arch.php?chname=" + chname + "&user=" + user;

			}

		</script>
        
        <script>
document.getElementsByClassName("tablink")[0].click();

function openCity(evt, cityName) {
  var i, x, tablinks;
  x = document.getElementsByClassName("city");
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablink");
  for (i = 0; i < x.length; i++) {
    tablinks[i].classList.remove("w3-light-grey");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.classList.add("w3-light-grey");
}
</script>
        
        <script>
$(document).ready(function() 
{ 
 $('form').ajaxForm(function() 
 {
  alert("Uploaded SuccessFully");
 }); 
});

function preview_image() 
{
 document.getElementById("subimg").value = "uploadimage";
 document.getElementById("subimg").name = "uploadimage";
// document.getElementById("submitbtn1").style.dislay = "none";   
 var total_file=document.getElementById("file-upload").files.length;
 for(var i=0;i<total_file;i++)
 {
  $('#image_preview').append("<img src='"+URL.createObjectURL(event.target.files[i])+"' style=width:200px; height=200px;><br>");
 }
}
            
function preview_imageurl() 
{
 document.getElementById("subimg").value = "uploadimage";
 document.getElementById("subimg").name = "uploadimage";
// document.getElementById("submitbtn1").style.dislay = "none";   
 var total_file=document.getElementById("file-upload").files.length;
 for(var i=0;i<total_file;i++)
 {
  $('#image_preview').append("<img src='"+URL.createObjectURL(event.target.files[i])+"' style=width:200px; height=200px;><br>");
 }
}
            
</script>

<script>
        function test(){
            document.getElementById('id01').style.display='block';
        }
        
        function test1(){
             document.getElementById('id01').style.display='none';
        }
    
        function test3(){
            document.getElementById('id01').style.display='none'
        }
    
</script>        
        
<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

		<script>
		
			function uploadfile() {
				document.getElementById("subfile").value = "uploadfile";
				document.getElementById("subfile").name = "uploadfile";
				document.getElementById("submitbtn1").style.dislay = "none";


			}
		
			function uploadimage() {
				document.getElementById("submitbtn").value = "uploadimage";
				document.getElementById("submitbtn").name = "uploadimage";
				document.getElementById("submitbtn1").style.dislay = "none";


			}

			function uploadwebimage() {
				//document.getElementById("chat_textbox").value="@image	image@";
				//alert("Insert image URL in '@image' tags");
				document.getElementById("submitbtn1").value = "uploadwebimage";
				document.getElementById("submitbtn1").name = "uploadwebimage";
				document.getElementById("submitbtn").style.dislay = "none";

			}
			
			function bold_function(){
				
			
				$("#txt").append("@b	b@");
				
			
			}

			function italic_function(){
				
                $("#txt").append("@i	i@");
                
			}

			function code_function(){
				
                $("#txt").append("@code		code@");
                }

			function mark_function(){
				
                $("#txt").append("@m	m@");
                
			}

			function strike_function(){
			
                $("#txt").append("@d	d@");
            
			}

			function display() {
				//				document.getElementById("chat_textbox").value=document.getElementById("file-upload").value;
			}

			function parseurl() {

				alert(document.getElementById("file-upload-glob").value);
			}

		</script>

	</body>



</html>
