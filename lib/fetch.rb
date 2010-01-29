###################
# my little ruby script to ride on the Twitter stream
# and get information about roadrage, roadfinger
# imehesz [ at ] gmail [ dot ] com
# http://mehesz.net
###################

require 'open-uri'
require 'pp'
# require 'sqlite3'

require 'rexml/document'
include REXML

url = 'http://search.twitter.com/search.rss?q=+roadrage+OR+roadfinger'
# url = 'xmlsample.xml'


def removeTags( str )
    retval = str.sub( /<[a-zA-Z+]*>/, '' )
    retval = retval.sub( /<\/[a-zA-Z+]*>/, '' )
    return retval 
end

# file = File.new( url )
file = open( url )
doc = Document.new(file)
# puts doc
root = doc.root

root.each_element( '//item' ) do |tweet|            
    title   = removeTags( tweet.elements[1].to_s() )
    link    = removeTags( tweet.elements[2].to_s() )
    desc    = removeTags( tweet.elements[3].to_s() )
    pubdate = removeTags( tweet.elements[4].to_s() )
    user    = removeTags( tweet.elements[6].to_s() )
    image   = removeTags( tweet.elements[7].to_s() )

    puts title
end
