###################
# my little ruby script to ride on the Twitter stream
# and get information about roadrage, roadfinger
# imehesz [ at ] gmail [ dot ] com
# http://mehesz.net
###################

require 'open-uri'
require 'pp'
require 'sqlite3'

require 'rexml/document'
include REXML

url = 'http://search.twitter.com/search.rss?q=+roadrage+OR+roadfinger'
# url = 'xmlsample.xml'

class String
  def escape_single_quotes
    self.gsub(/'/, '"')
  end
end

#
#
#
def removeTags( str )
    retval = str.sub( /<[a-zA-Z+]*>/, '' )
    retval = retval.sub( /<\/[a-zA-Z+]*>/, '' )
    return retval 
end

#
#
#
def tweetExist( user, pubdate )
    db = SQLite3::Database.new( "../db/dev.db" )
    db.execute( 
            "select * from tweets where user=? and raw_date=?",
            user,
            pubdate 
    ) do |row|
        # if there is a ROW with this user and time
        # it means we already saved that tweet ...
        return true
    end

    return false
end

#
#
#
def storeTweet( arr_tweet )
    db = SQLite3::Database.new( "../db/dev.db" )

    if arr_tweet["image"]       ==nil then arr_tweet["image"]       = '' end
    if arr_tweet["raw_tweet"]   ==nil then arr_tweet["raw_tweet"]   = '' end
    if arr_tweet["title"]       ==nil then arr_tweet["title"]       = '' end
    if arr_tweet["user"]        ==nil then arr_tweet["user"]        = '' end
    if arr_tweet["pubdate"]     ==nil then arr_tweet["pubdate"]     = '' end
    now = Time.now.to_i

    sql_text = "INSERT INTO tweets (`profile_image`,`raw_date`,`raw_tweet`,`user`,`created`) VALUES 
                ('"+arr_tweet["image"].escape_single_quotes+"','"+arr_tweet["pubdate"].escape_single_quotes+"','"+arr_tweet["title"].escape_single_quotes+"','"+arr_tweet["user"].escape_single_quotes+"','"+now.to_s+"')"

    db.execute( sql_text ) do |row|
        return true
    end
    
    return false
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

    if( user && pubdate )
        if ! tweetExist( user, pubdate )
            #puts 'create'

            arr_tweet = { 
                            'title'     => title,
                            'link'      => link,
                            'desc'      => desc,
                            'pubdate'   => pubdate,
                            'user'      => user,
                            'image'     => image
                        }

            storeTweet( arr_tweet )
        end
    end

    #puts title
end
