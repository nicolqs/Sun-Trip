require 'rubygems'
require 'rest_client'
require 'htmlentities'
require 'unicode'
require 'json'
require 'yaml'
require 'net/http'
require 'uri'
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

  #flights
  html << "<h1>#{city.capitalize()}</h1>"
  html << "<div class='flights'>"
  postData = Net::HTTP.post_form(URI.parse('http://www.suntrip.co/ajax_fare.php'), {'from'=>'SFO', 'fromDate' => '09/11/2013', 'toDate' => '09/21/2013', 'to' => city})
  html << postData.body
  html << "</div>"

  # restaurants
  html << "<div class='restaurants'>"
  html << "<h1>Restaurants</h1>"
  restaurant_count = 0
  query_api("travel/#{@cities_dataset[city]['dataset']}/places?category=restaurant&")['results'].each do |restaurant|
    if restaurant_count > 2
      break
    end
    if restaurant['title']
      restaurant_count += 1
      info = query_api("travel/places/#{restaurant['id']}?")['result']
      if info['address'] && info['price']
        html << "<h2>#{@coder.encode(info['title'], :named)}</h2>"
        html << "<p>"
        html << "Price: #{@coder.encode(info['price']['range'], :named)}<br>"
        html << "Location: : #{@coder.encode(info['address'], :named)}<br>"
        html << "</p>"
      end
    end
  end
  html << "</div>"

  html << "<div class='todo'>"
  html << "<h1>Places of interest</h1>"
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
          html << "<h2>#{@coder.encode(place['images'][0]['title'].to_s, :named)}</h2>"
          html << '<p>'
          html << "<img src=\"#{api_resource(place['images'][0]['image'])}\" style=\"width:100%;\"><br>"
          html << "#{@coder.encode(place['text'], :named)}<br>"
          html << '</p>'
        end
      end
    end
  end
  html << "</div>"

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
