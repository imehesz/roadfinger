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
        return find_objects_by_sql("SELECT `raw_tweet` AS tweet FROM `tweets` ORDER BY `created` DESC LIMIT " . $limit);
    }

    return find_objects_by_sql("SELECT `raw_tweet` AS tweet FROM `tweets` ORDER BY `created` DESC");

}

/**
 *
 * @return <type>
 */
function find_raw_tweets()
{
    return find_objects_by_sql("SELECT `raw_tweet` AS tweet FROM `tweets`");
}

?>
