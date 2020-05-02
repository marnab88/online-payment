import datetime

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
	today_date=str(datetime.datetime.now().date()).split('-')
	today_month = today_date[1]
	if date_month >= today_month:
		return True

def nextinstallment_comparision(userdate):
	if userdate == '':
		return userdate
	userdate = int((userdate - 25569) * 86400)
	userdate = int(str(userdate) + "000")
	userdate = datetime.datetime.fromtimestamp(userdate / 1e3)
	date_split = str(userdate.date()).split('-')
	date_month = date_split[1]
	today_date=str(datetime.datetime.now().date()).split('-')
	today_month = today_date[1]
	if date_month > today_month:
		return True

def upload_comparision(userdate):
	if userdate == '':
		return userdate
	upload_month = userdate.split("'")
	month = upload_month[0]
	year = upload_month[1]
	if len(month)<=3 and len(year)<=2:
		if month.isalpha() and year.isdigit():
			return True





