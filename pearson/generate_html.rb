require 'rubygems'
require 'rest_client'
require 'htmlentities'
require 'unicode'
require 'json'
require 'yaml'
$KCODE = 'UTF-8'

@API_URI = "http://api.pearson.com/v2/"
@API_KEY = "apikey=bDpAVJlCztk9kPkLcfjqvAknoktNPGUp"
@cities_dataset = YAML::load(File.open('../assets/yml/cities.yml'))
@coder = HTMLEntities.new


def generate_city_content(city)
  html = ''

  def query_api(query)
    JSON.parse(RestClient.get("#{@API_URI}#{query}#{@API_KEY}"))
  end

  def api_resource(content)
    "http://api.pearson.com#{content}?apikey=bDpAVJlCztk9kPkLcfjqvAknoktNPGUp"
  end

  # restaurants
  html << "<h1>Restaurants</h1>"
  html << "All prices for a three-course meal for one with half a bottle of wine."
  restaurant_count = 0
  query_api("travel/#{@cities_dataset[city]['dataset']}/places?category=restaurant&")['results'].each do |restaurant|
    if restaurant_count > 2
      break
    end
    if restaurant['title']
      restaurant_count += 1
      info = query_api("travel/places/#{restaurant['id']}?")['result']
      if info['address'] && info['price']
        html << "<p>"
        html << "<b>#{@coder.encode(info['title'], :named)}</b><br>"
        html << "Price: #{@coder.encode(info['price']['range'], :named)}<br>"
        html << "Location: : #{@coder.encode(info['address'], :named)}<br>"
        html << "</p>"
      end
    end
  end

  html << "<h1>TODOs</h1>"
  todo_counter = 0
  # TODO in the city
  results = query_api("travel/topten?limit=5&search=#{city}&")['results']
  results.each do |h|

    query_api("travel/topten/#{h['id']}?")['result']['topten'].each do |place|
      if todo_counter > 3
        break
      end
      if place['images']
        unless place['images'][0]['title'].to_s.empty?
          todo_counter += 1
          html << '<p>'
          html << "<b>#{@coder.encode(place['images'][0]['title'].to_s, :named)}</b><br>"
          html << "<img src=\"#{api_resource(place['images'][0]['image'])}\" style=\"width:100%;\"><br>"
          html << "#{@coder.encode(place['text'], :named)}<br>"
          html << '</p>'
        end
      end
    end
  end

  File.open("../assets/cities/#{city}.html", 'w') { |file| file.write(html) }

end

if ARGV[0]
  city = ARGV[0]
  generate_city_content(city)
else
  @cities_dataset.each do |k,v|
    generate_city_content(k)
  end
end
