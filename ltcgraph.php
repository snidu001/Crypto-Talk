<html>
    <head>
<link rel="stylesheet" type="text/css" href="css/form.css">
        <style>
            body,
    html {
        background-image: url('Images/step1.jpg');
        background-repeat: no-repeat;
        background-size: 100%;

    }
.center-div
{
  position: absolute;
  margin: auto;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  width: 100px;
  height: 100px;
        </style>
    </head>
    
    <?php
    require_once ('db_connect.inc.php');
    session_start();
        if(isset($_SESSION['email']))
        {
            
            $Email = $_SESSION['email'];
           
        }

            if(isset($Email)) 
            {
                        echo '<ul>
                        <li><a class="active" href="./login.php">Return to chat</a></li>
                        
						
                        </li>
					</ul>';
    
            }
    ?>


    
    <script type="text/javascript">
baseUrl = "https://widgets.cryptocompare.com/";
var scripts = document.getElementsByTagName("script");
var embedder = scripts[ scripts.length - 1 ];
(function (){
var appName = encodeURIComponent(window.location.hostname);
if(appName==""){appName="local";}
var s = document.createElement("script");
s.type = "text/javascript";
s.async = true;
var theUrl = baseUrl+'serve/v3/coin/chart?fsym=LTC&tsyms=USD,EUR,CNY,GBP,GOLD,JPY,AUD,RUB,INR';
s.src = theUrl + ( theUrl.indexOf("?") >= 0 ? "&" : "?") + "app=" + appName;
embedder.parentNode.appendChild(s);
})();
</script>
    
</html>

