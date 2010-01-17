<?php
    require_once 'config.php';
    require_once LIMONADE_PATH .'limonade.php';

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
        }
        else
        {
            // some production settings ...
        }
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
            return html( 'Welcome!' );
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
    <html>
    <head>
        <title>Limonde first example</title>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery-twitter-plugin.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                // This is more like it!
                // $.twitter.search("roadrage",printSuccess);
//                $.twitter.test(printSuccess);

            });

            function printSuccess(data, textStatus){
                //Handle data as a JSON object
//                alert( data );
            }
        </script>
    </head>
    <body>
    <h1>Roadfinger!</h1>
        <?=$content?>
        <div id="printSuccess"></div>
    </body>
    </html>
    <?}?>