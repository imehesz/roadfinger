<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @return <type> 
 */
function find_tweets( $limit = NULL )
{
    if( $limit  )
    {
        return find_objects_by_sql("SELECT `raw_tweet` AS tweet FROM `roadfinger_tweets` ORDER BY `created` DESC LIMIT " . $limit);
    }

    return find_objects_by_sql("SELECT `raw_tweet` AS tweet FROM `roadfinger_tweets` ORDER BY `created` DESC");

}

function find_geo_tweets( $limit = null )
{
    if( $limit )
    {
        return find_objects_by_sql("SELECT `raw_tweet`,`location`,`profile_image`,`raw_date`,`raw_tweet`,`user` tweet FROM `roadfinger_tweets` WHERE `location` <> '' ORDER BY `created` DESC LIMIT " . $limit);
    }

    return find_objects_by_sql("SELECT `raw_tweet`,`location`,`profile_image`,`raw_date`,`raw_tweet`,`user` tweet FROM `roadfinger_tweets` WHERE `location` <> '' ORDER BY `created` DESC" );
}

/**
 *
 * @return <type>
 */
function find_raw_tweets()
{
    return find_objects_by_sql("SELECT `raw_tweet` AS tweet FROM `roadfinger_tweets`");
}

function get_top5()
{
    return find_objects_by_sql( "SELECT user, count( * ) AS cnt FROM `roadfinger_tweets` GROUP BY user ORDER BY cnt DESC LIMIT 5 " );
}

?>
