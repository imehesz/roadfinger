<?php
    require_once 'config.php';
    require_once LIMONADE_PATH .'limonade.php';

    function configure()
    {
      option('env', DEVELOPMENT);
    }


    function before()
    {
        layout('html_my_layout');
    }

    dispatch('/', 'welcome');
        function welcome()
        {
            return html('Welcome!');
        }

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
    </head>
    <body>
    <h1>Roadfinger!</h1>
        <?=$content?>
    </body>
    </html>
    <?}?>