<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @return <type> 
 */
function find_tweets()
{
    return find_objects_by_sql("SELECT * FROM `tweets`");
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
