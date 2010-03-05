<?php
    require_once 'config.php';
    require_once LIMONADE_PATH .'limonade.php';
    require_once 'lib/db.php';

    /**
     *
     */
    function configure()
    {
        $env = stristr( $_SERVER[ 'HTTP_HOST' ], 'local' ) ? ENV_DEVELOPMENT : ENV_PRODUCTION;
        option('env', $env );

        if( option('env') > ENV_PRODUCTION )
        {
            // some development settings ...
            $dsn = 'sqlite:db/dev.db';
        }
        else
        {
            // some production settings ...
            $dsn = 'sqlite:db/prod.db';
        }

        $db = new PDO($dsn);
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        
        option('dsn', $dsn);
        option('db_conn', $db);
    }

    /**
     *
     */
    function before()
    {
        layout('html_my_layout');
    }

    dispatch('/', 'welcome');
        function welcome()
        {
            $tweets = find_raw_tweets();

            if( is_array( $tweets ) && sizeof($tweets) )
            {
                $colors = array( '0ff', 'ff0', 'f0f', '33ff00', '33ffee', 'ff22aa', '00ff11' );
                
                foreach( $tweets as $tweet )
                {
                    
                    $color = $colors[rand(0,sizeof($colors)-1)];
                    $width = rand( 100,400 );
                    $html_tweet = str_ireplace('roadrage','<strong>roadrage</strong>',$tweet -> tweet);
                    $html_tweet = str_ireplace( 'roadfinger','<strong>roadfinger</strong>', $html_tweet );
                    $output .= "<div class=\"tweet\" style=\"background-color:#{$color};width:{$width}px;\">" . $html_tweet . '</div>';
                }
            }
            return html( $output );
        }

    dispatch( '/list', 'listTweets' );
        function listTweets()
        {
            $tweets = find_raw_tweets();
            if( is_array( $tweets) && sizeof( $tweets ) )
            {
                die( json_encode( $tweets ) );
            }
        }


    /**
     *
     */
    function after($output)
    {
        $time = number_format( (float)substr(microtime(), 0, 10) - LIM_START_MICROTIME, 6);
        $output .= "<!-- page rendered in $time sec., on ".date(DATE_RFC822)."-->";
        return $output;
    }

    run();

    function html_my_layout($vars){ extract($vars);?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
    <html xmlns="http://www.w3.org/1999/xhtml">
    <html>
    <head>
        <title>roadfinger.mehesz.net</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery-twitter-plugin.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                // This is more like it!
                // $.twitter.search("roadrage",printSuccess);
//                $.twitter.test(printSuccess);

            });

//            function printSuccess(data, textStatus){
//                //Handle data as a JSON object
////                alert( data );
//            }
        </script>
    </head>
    <body>
        <div class="wrapper">
            <div class="wrapper-inside">
                <div class="menu">
                    <div class="menu-item"><a href="/"><img src="images/url.png" alt="RoadFinger.mehesz.net" title="RoadFinger.mehesz.net" border="0"/></a></div>
                    <div class="menu-item"><img src="images/about.png" /></div>
                    <div class="menu-item"><img src="images/contact.png" /></div>
                    <div class="menu-item"><a href="http://limonade.sofa-design.net/"><img alt="Limonade PHP Framework" title="Limonade PHP Framework" src="images/limonade.png" border="0"/></a></div>
                    <div style="clear:both;"></div>
                </div>
                <div class="content">
                    <div class="content-left">
                        <p>
                            <br /><br /><br /><br /><br />
                        </p>
                        <img src="images/tank.png" style="vertical-align:bottom;" />
                    </div>
                    <div class="content-right"><?php print $content?></div>
                    <div style="clear:both;"></div>
                </div>
                <div style="">
                    <img src="images/bg-bottom.png" />
                </div>
            </div>
            <div style="text-align:center;font-size:10px;color:#999;">mehesz.net</div>
        </div>
    </body>
    </html>
    <?}?>