require 'rubygems'
require 'rest_client'
require 'json'
require 'yaml'

city = ARGV[0]
@API_URI = "http://api.pearson.com/v2/"
@API_KEY = "&apikey=bDpAVJlCztk9kPkLcfjqvAknoktNPGUp"
html = ''

def query_api(query)
  puts "#{@API_URI}#{query}#{@API_KEY}"
  JSON.parse(RestClient.get("#{@API_URI}#{query}#{@API_KEY}"))
end

def api_resource(content)
  "http://api.pearson.com#{content}?apikey=bDpAVJlCztk9kPkLcfjqvAknoktNPGUp"
end

cities_hash = {}
0.upto(4) do |i|
  i = 25 * i
  query_api("travel/datasets?offset=#{i}&limit=25")['results'].each do |data_set|

    cities_hash[data_set['name'].sub('tt_', '')] = data_set['id']
  end
end

File.open('./cities.yml', 'w') { |file| file.write(cities_hash.to_yaml) }
