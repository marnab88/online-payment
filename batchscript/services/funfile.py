import datetime
from time import strptime
import re

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

def demanddate_comparision(userdate):
	if userdate == '':
		return userdate
	userdate = int((userdate - 25569) * 86400)
	userdate = int(str(userdate) + "000")
	userdate = datetime.datetime.fromtimestamp(userdate / 1e3)
	date_split = str(userdate.date()).split('-')
	date_month = date_split[1]
	date_year = date_split[0]
	today_date=str(datetime.datetime.now().date()).split('-')
	today_month = today_date[1]
	today_year = today_date[0]
	if date_month == today_month and date_year == today_year :
		return True

def nextinstallment_comparision(userdate):
	if userdate == '':
		return userdate
	userdate = int((userdate - 25569) * 86400)
	userdate = int(str(userdate) + "000")
	userdate = datetime.datetime.fromtimestamp(userdate / 1e3)
	date_split = str(userdate.date()).split('-')
	date_month = date_split[1]
	date_year = date_split[0]
	date_month_chk = str(int(date_month)+1)
	today_date=str(datetime.datetime.now().date()).split('-')
	today_month = today_date[1]
	today_year = today_date[0]
	if (date_month == today_month and date_year == today_year) or (date_month == date_month_chk and date_year == today_year) :
		return True

def upload_comparision(userdate):
	if userdate != '' and  len(userdate)== 6 and userdate[3] == '`':
		upload_month = userdate.split("`")
		month_user = upload_month[0]
		year = upload_month[1]
		today_date = datetime.datetime.now()
		today_month = today_date.strftime("%b")
		today_year =  today_date.strftime("%y")
		if len(month_user)==3 and len(year)==2:
			check_nxt_month = datetime.datetime.strptime(month_user, '%b').month
			data = str(int(check_nxt_month) + 1)
			if (month_user.lower() == today_month.lower() and year == today_year) or (data == check_nxt_month and year == today_year)  :
				return True


def alpha_check(userdate):
	if userdate !='':
		if re.search('[a-zA-Z]+', userdate):
			return True








