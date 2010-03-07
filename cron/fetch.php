<?php
	$xml = @simplexml_load_file( 'http://search.twitter.com/search.rss?q=+roadrage+OR+roadfinger' );

	$pdo = new PDO(
		'mysql:host=localhost;dbname=dbname',
		'user',	
		'password',
		array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") 
	);
	foreach( $xml->channel->item as $item )
	{
		$pubDate = (string)strip_tags($item->pubDate);
		$description=(string)htmlspecialchars(strip_tags($item->description));
		$author = (string)strip_tags($item->author);

		$sql_text = sprintf("INSERT IGNORE 
								INTO roadfinger_tweets 
									(`raw_date`,`raw_tweet`,`user`,`created`) 
								VALUES 
									('%s','%s','%s','%s')",
									$pubDate, $description,$author,time());
		$stmt = $pdo->prepare( $sql_text );
		$stmt->execute();
	}
