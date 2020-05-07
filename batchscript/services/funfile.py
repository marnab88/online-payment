import datetime
from time import strptime
import re
import urllib
import requests

def check_validation( userdata ):
   res =  str(userdata).replace('.', '', 1).isdigit()
   return res

def date_conversion( userdate ):
	if userdate == '':
		return userdate
	userdate = int((userdate - 25569) * 86400)
	userdate = int(str(userdate) + "000")
	userdate = datetime.datetime.fromtimestamp(userdate / 1e3)
	userdate = userdate.date()
	return userdate

def date_strftime(userdate):
	if userdate=='':
		return userdate
	userdate = int((userdate - 25569) * 86400)
	userdate = int(str(userdate) + "000")
	userdate = datetime.datetime.fromtimestamp(userdate / 1e3)
	Demand_date_t = userdate.date().strftime("%Y-%m")
	return Demand_date_t

def nextinstallment_comparision(userdate):
	if userdate == '':
		return userdate
	userdate = int((userdate - 25569) * 86400)
	userdate = int(str(userdate) + "000")
	userdate = datetime.datetime.fromtimestamp(userdate / 1e3)
	date_split = str(userdate.date()).split('-')
	date_month = int(date_split[1])
	date_year = int(date_split[0])
	today_date=str(datetime.datetime.now().date()).split('-')
	today_month = int(today_date[1])
	today_year = int(today_date[0])
	if today_month < 12 :
		today_month_chk = today_month + 1
		if date_month == today_month_chk and date_year == today_year :
			return True
	else:
		today_month_chk = 1
		today_year = today_year+1
		if date_month == today_month_chk and date_year == today_year :
			return True


def demanddate_comparision(userdate):
	if userdate == '':
		return userdate
	userdate = int((userdate - 25569) * 86400)
	userdate = int(str(userdate) + "000")
	userdate = datetime.datetime.fromtimestamp(userdate / 1e3)
	date_split = str(userdate.date()).split('-')
	date_month = int(date_split[1])
	date_year = int(date_split[0])
	today_date=str(datetime.datetime.now().date()).split('-')
	today_month = int(today_date[1])
	today_year = int(today_date[0])
	if today_month < 12 :
		today_month_chk = today_month + 1
		if date_month == today_month and date_year == today_year or  date_month == today_month_chk and date_year == today_year:
			return True
	else:
		today_month_chk =  1
		today_year = today_year +1
		if date_month == today_month and date_year == today_year or  date_month == today_month_chk and date_year == today_year:
			return True



def upload_comparision(userdate):
	if userdate != '' and  len(userdate)== 6 and userdate[3] == '`':
		upload_month = userdate.split("`")
		month_user = upload_month[0]
		year = upload_month[1]
		today_date = datetime.datetime.now()
		today_month = today_date.strftime("%b")
		today_year =  today_date.strftime("%y")
		today_month_chk = int(today_date.strftime("%m"))
		if len(month_user)==3 and len(year)==2:
			check_nxt_month = datetime.datetime.strptime(month_user, '%b').month
			if today_month_chk < 12:
				data = int(check_nxt_month) + 1
				if month_user.lower() == today_month.lower() and year == today_year or data == today_month_chk and year == today_year :
					return True
			else:
				today_month_chk = 1
				today_year_chk = int(today_year)+1
				if month_user.lower() == today_month.lower() and year == today_year or data == today_month_chk and int(year) == today_year_chk:
					return True


def alpha_check(userdate):
	if userdate !='':
		if re.search('[A-Za-z ]+$', userdate):
			return True






def send_msg(message):
	message = urllib.parse.quote(message+str('>>>> from testserver'))
	API_ENDPOINT = 'https://api.telegram.org/bot1285906317:AAHND-_fixUAexGtxLpZlv_2g-MrS9k8UwQ/sendMessage?chat_id=-421475178&text=' + message
	r = requests.post(url=API_ENDPOINT)
	x = r.json()


