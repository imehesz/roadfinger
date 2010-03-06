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
            $tweets = find_tweets( 15 );

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

    dispatch( '/about', 'about' );
        function about()
        {
            return html(
<<<HTML
<div class="yellow box">
    <strong>NO</strong> registration<br />
    <strong>NO</strong> signup<br />
    <strong>NO</strong> hidden fees<br />
</div>

<div class="aqua box">
    use your <a alt="twitter.com" title="twitter.com" href="http://twitter.com">twitter</a> account,<br />
    or get one!
</div>

<div class="lime box">
    if someone ticks you off on the road, <br />
    <strong>tweet</strong> it (use <strong>#roadfinger</strong> or <strong>#roadrage</strong> tags)
</div>

<div class="fuchsia box">
    you can help us rank the <strong>donkeys</strong> by providing location information using this example:
</div>

<div class="aqua box" style="width:300px;">
<strong>#roadfinger</strong> to the SOB in the yellow mustang
t:GH67Y3 <span class="mini-info">(tag)</span> in
c:Smallville <span class="mini-info">(city)</span>
z:12345 <span class="mini-info">(zip code)</span>
 for smashing my car this afternoon
</div>


<div class="yellow box">
thanks, and have fun ;)
</div>
HTML
            );
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
                    <div class="menu-item"><a href="<?php echo url_for( 'about' );?>" alt="about" title="about"><img src="images/about.png" /></a></div>
                    <div class="menu-item"><a alt="contact" title="contact" href="javascript:void();" onclick="alert('info [at] mehesz.net');"><img src="images/contact.png" /></a></div>
                    <div class="menu-item"><a href="http://limonade.sofa-design.net/"><img alt="Limonade PHP Framework" title="Limonade PHP Framework" src="images/limonade.png" border="0"/></a></div>
                    <div style="clear:both;"></div>
                </div>
                <div class="content">
                    <div class="content-left">
                        <p>
                            <br /><br /><br /><br /><br />
                        </p>
                        <img alt="this is just a tank" title="this is just a tank" src="images/tank.png" style="vertical-align:bottom;" />
                    </div>
                    <div class="content-right"><?php print $content?></div>
                    <div style="clear:both;"></div>
                </div>
                <div style="">
                    <img src="images/bg-bottom.png" />
                </div>
            </div>
            <div class="footer">
                &copy; <a href="http://mehesz.net" alt="mehesz.net - open source for life" title="mehesz.net - open source for life">mehesz<span style="color:#f00;">.</span>net</a> <?php print date('Y', time()); ?>
            </div>
        </div>
        <script type="text/javascript">
        var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
        document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
        </script>
        <script type="text/javascript">
        try {
        var pageTracker = _gat._getTracker("UA-5417349-4");
        pageTracker._trackPageview();
        } catch(err) {}</script>
    </body>
    </html>
    <?}?>
