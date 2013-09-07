require 'rubygems'
require 'rest_client'
require 'json'
require 'yaml'
cities_dataset = YAML::load(File.open('./cities.yml'))

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
query_api("travel/#{cities_dataset[city]}/places?category=restaurant&")['results'].each do |restaurant|
  if restaurant['title']
    info = query_api("travel/places/#{restaurant['id']}?")['result']
    if info['address'] && info['price']
      puts info['title']
      html << info['title']
      html << "<b>Description:</b> #{info['price']['range']}"
      html << "<b>Price:</b> #{info['price']['description']}"
      html << "<b>Location:</b> : #{info['address']}"
      html << "<br>"
    end
  end
end

# TODO in the city
results = query_api("travel/topten?limit=5&search=#{city}&")['results']
results.each do |h|
  puts "Explore #{h['title']}"

  query_api("travel/topten/#{h['id']}?")['result']['topten'].each do |place|
    if place['images']
      html << place['images'][0]['title'].to_s
      html << "<img src=\"#{api_resource(place['images'][0]['image'])}\">"
      html << place['text']
      html << '<br>'
    end
  end
end

puts html



