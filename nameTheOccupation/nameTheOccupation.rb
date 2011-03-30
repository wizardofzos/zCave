# This is a ruby interpretation on xkcd.com/500

datafile = File.new("nameTheOccupation.datafile", "r")
names = Array.new()

while(line = datafile.gets)
  data = line.split(' ')
  names.push(data[1].capitalize)
end

datafile2 = File.new("nameTheOccupation.datafile2", "r")
occupations = Array.new()

while(line = datafile2.gets)
  if line.chomp != " " then
    occupations.push(line.chomp.gsub(/[_]/, ' '))
  end
end

while(1)
  puts names[rand(names.length)] + " the " + occupations[rand(occupations.length)]
end


