#!/usr/local/rvm/rubies/ruby-2.1.10/bin/ruby

require 'date'
require 'net/smtp'
require 'rubygems'
require 'mailfactory'

#by cron
if Time.now.min == 59 and Time.now.hour == 8
	to = ['original@mail.ru','next@mail.ru']
else
	to = ['test@bk.ru','next@mail.ru']
end

from = 'sender@mail.ru'

file_way = "/root/beerdy/day_ago_mp3/"
login    = 'login@mail.ru'
server   = 'beta-term'
password = 'password'

mail = MailFactory.new()
mail.to = to
mail.from = from
mail.subject = "От #{Date.today - 1}. Записи звонков сервиса - 3144 3187 3189"

mail.text = "Доброе утро!

--
С уважением, Александр
"

#mail.html = ''

Dir["#{file_way}*"].each do |file|
	mail.attach(file)
end

smtp = Net::SMTP.new server, 465
smtp.enable_ssl

smtp.start(server, login, password, :login) do
  smtp.send_message(mail.to_s(), from, to)
end
