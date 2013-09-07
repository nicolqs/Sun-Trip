require 'rubygems'
require 'rest_client'
require 'htmlentities'
require 'unicode'
require 'json'
require 'yaml'
$KCODE = 'UTF-8'


cities_dataset = YAML::load(File.open('../assets/yml/cities.yml'))
coder = HTMLEntities.new

city = ARGV[0]
@API_URI = "http://api.pearson.com/v2/"
@API_KEY = "apikey=bDpAVJlCztk9kPkLcfjqvAknoktNPGUp"
html = ''

def query_api(query)
  puts "#{@API_URI}#{query}#{@API_KEY}"
  JSON.parse(RestClient.get("#{@API_URI}#{query}#{@API_KEY}"))
end

def api_resource(content)
  "http://api.pearson.com#{content}?apikey=bDpAVJlCztk9kPkLcfjqvAknoktNPGUp"
end

# restaurants
html << "<h1>Restaurants</h1>"
query_api("travel/#{cities_dataset[city]['dataset']}/places?category=restaurant&")['results'].each do |restaurant|
  if restaurant['title']
    info = query_api("travel/places/#{restaurant['id']}?")['result']
    if info['address'] && info['price']
      html << "<p>"
      html << "<b>#{coder.encode(info['title'], :named)}</b><br>"
      html << "Description: #{coder.encode(info['price']['range'], :named)}<br>"
      html << "Price: #{coder.encode(info['price']['description'], :named)}<br>"
      html << "Location: : #{coder.encode(info['address'], :named)}<br>"
      html << "</p>"
    end
  end
end

html << "<h1>TODOs</h1>"
# TODO in the city
results = query_api("travel/topten?limit=5&search=#{city}&")['results']
results.each do |h|
  puts "Explore #{h['title']}"

  query_api("travel/topten/#{h['id']}?")['result']['topten'].each do |place|
    if place['images']
      html << '<p>'
      html << "#{coder.encode(place['images'][0]['title'].to_s, :named)}<br>"
      html << "<img src=\"#{api_resource(place['images'][0]['image'])}\" style=\"width:90%;\"><br>"
      html << "#{coder.encode(place['text'], :named)}<br>"
      html << '</p>'
    end
  end
end

File.open("./#{city}.html", 'w') { |file| file.write(html) }



