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

        $db = new PDO(
            'mysql:host='.DB_HOST.';dbname='.DB_NAME,
            DB_USER,
            DB_PASS,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );

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

        set( 'top5', get_top5() );
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
                    $html_tweet = str_ireplace('roadrage ','<strong>roadrage</strong> ',$tweet -> tweet);
                    $html_tweet = str_ireplace('roadfinger ','<strong>roadfinger</strong> ', $html_tweet );
                    // TODO - replace ereg_replace with something else
                    $output .=
                        "<div class=\"tweet\" style=\"background-color:#{$color};width:{$width}px;\">" .
                        ereg_replace(
                            "http://([a-zA-Z0-9./-]+)$",
                            "<a target='_blank' href=\"\\0\">\\0</a>",
                            htmlspecialchars_decode($html_tweet)
                        ). '</div>';
                }
            }
            return html( $output );
        }

    dispatch( '/about', 'about' );
        function about()
        {
            return html(
<<<HTML
<p style="margin-top:50px;"></p>
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
                    <div class="menu-item"><a href="<?php echo url_for( 'about' );?>" alt="about" title="about"><img border="0" src="images/about.png" /></a></div>
                    <div class="menu-item"><a alt="contact" title="contact" href="javascript:void();" onclick="alert('info [at] mehesz.net');"><img border="0" src="images/contact.png" /></a></div>
                    <div class="menu-item"><a href="http://limonade-php.net/"><img alt="Limonade PHP Framework" title="Limonade PHP Framework" src="images/limonade.png" border="0"/></a></div>
                    <div><a href="http://en.wikipedia.org/wiki/Parental_Advisory" target="_blank"><img border="0" src="images/paec75x50.jpg" /></a></div>                    
                    <div style="clear:both;"></div>
                </div>
                <div class="content">
                    <div class="content-left">
                        <p><br /><br /></p>
                        
                        <img alt="this is just a tank" title="this is just a tank" src="images/tank.png" style="vertical-align:bottom;" />

                        <p style="margin-top:30px;">
                            <img src="images/top5.png" alt="the TOP5 roadrager" title="the TOP5 roadrager" />
                            <?php if( is_array( $top5 ) ): ?>
                                <?php foreach( $top5 as $rager ) : ?>
                                    <?php
                                        $whereisat = strpos( $rager -> user , '@' );
                                        $name = substr( $rager->user, 0, $whereisat );
                                    ?>
                                    <div class="top5">
                                        <a href="http://twitter.com/<?php print $name; ?>" target="_blank"><?php print $name . ' ('. $rager->cnt .')'; ?></a>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </p>
                        <p style="margin-top:50px;">
						<img alt="the finger" title="the finger" src="images/finger.png" style="vertical-align:bottom;" />
						</p>
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
