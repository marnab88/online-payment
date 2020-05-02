import datetime
from services.database import *
from services.funfile import *


def insert_MSME(sheet,RecordId):
	totrows = 0
	Mismatch=0
	for i in range(1, sheet.nrows):
		row = sheet.row_values(i)
		error_count = 0
		error_msg = ''
		slno = row[0]
		branch_name = row[1]
		cluster = row[2]
		state = row[3]
		client_id = row[4]
		loan_account_no = row[5]
		client_name = row[6]
		mobile_no = row[7]
		EMIsr_no = row[8]
		Demand_date = row[9]
		last_month_due = row[10]
		current_month_due = row[11]
		late_penalty = row[12]
		next_installment_date = row[13]
		upload_month = row[14]
		product_vertical = row[15]


		if slno != '':
			totrows=totrows+1

			if len(loan_account_no) != 20 :
				error_count =  1
				msg = ' not valid loan account number.'
				error_msg = error_msg + str(msg)


			else:

				database = mycus()
				cursor = database.cursor()
				Demand_date_t = date_strftime(Demand_date)
				sql="SELECT *  from MsmeExcelData WHERE LoanAccountNo='"+str(loan_account_no)+"' and DemandDate like '"+str(Demand_date_t)+"%'"
				cursor.execute(sql)
				rowcursor = cursor.fetchall()
				cursor.close()
				database.close()

				if len(rowcursor) > 0 :
					error_count = 1
					msg = ' this loanAcct already exist for this month.'
					error_msg = error_msg +str(msg)


			if check_validation( mobile_no ) :
				if len(str(int(mobile_no))) != 10:
					error_count =  1
					msg = ' not a valid mobile number.'
					error_msg = error_msg +str(msg)

				else:
					database = mycus()
					cursor = database.cursor()
					Demand_date_t = date_strftime(Demand_date)
					sql = "SELECT *  from MsmeExcelData WHERE MobileNo='" + str(int(mobile_no)) + "' and  DemandDate like '" + str(Demand_date_t) + "%'"
					cursor.execute(sql)
					rowcursor = cursor.fetchall()
					cursor.close()
					database.close()

					if len(rowcursor) > 0:
						error_count =  1
						msg = ' this Mobile No already exist for this month.'
						error_msg = error_msg + str(msg)

			else:
				msg = ' mobile number is not a number.'
				error_msg = error_msg + str(msg)
				error_count =  1


			if check_validation( EMIsr_no ) :
				if not int(EMIsr_no) <= 240 :
					error_count =  1
					msg = ' duplicate emisr of this loanAc.'
					error_msg =error_msg +str(msg)
			else:
				msg = ' EMIsr number is not a number.'
				error_count = 1
				error_msg = error_msg + str(msg)


			if check_validation( last_month_due ) :
				if not len(str(int(last_month_due))) <= 8 :
					error_count =  1
					msg = ' Lastmonth due error.'
					error_msg = error_msg +str(msg)
			else:
				msg = 'last month_due  is not a number.'
				error_count =  1
				error_msg = error_msg + str(msg)


			if check_validation( current_month_due ):
				if not len(str(int(current_month_due))) <= 8 :
					error_count =  1
					msg = ' Current Month due error.'
					error_msg =error_msg +str(msg)
			else:
				msg = 'current month due is not a number.'
				error_count =  1
				error_msg = error_msg + str(msg)

			if cluster == '':
				error_count =  1
				msg = ' cluster cannot be blank.'
				error_msg = error_msg+str(msg)


			if branch_name == '':
				error_count =  1
				msg = ' BranchName cannot be empty.'
				error_msg =error_msg +str(msg)


			if client_name == '' or not len(client_name)>=2 and not len(client_name)<=21:
				error_count = 1
				msg = ' Client name cannot be empty'
				error_msg =error_msg+str(msg)

			if Demand_date == '' :
				error_count = 1
				msg = ' Demand date cannot be empty'
				error_msg =error_msg+str(msg)



			if(error_count==1):
			   Mismatch=Mismatch+1

			Demand_date = date_conversion(Demand_date)
			next_installment_date = date_conversion(next_installment_date)
			database = mycus()
			cursor = database.cursor()
			sql = "INSERT INTO MsmeExcelData (RecordId,BranchName,Cluster,State,ClientId,LoanAccountNo,ClientName,MobileNo,EMISrNo,DemandDate,LastMonthDue,CurrentMonthDue,LatePenalty,NextInstallmentDate,UploadMonth,ProductVertical,errorMsg,errorCount) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
			val = (RecordId,branch_name, cluster, state, client_id, loan_account_no, client_name, mobile_no, EMIsr_no, Demand_date,last_month_due, current_month_due, late_penalty, next_installment_date, upload_month, product_vertical,error_msg,error_count)

			cursor.execute(sql, val)
			database.commit()



	cursor.execute("UPDATE UploadRecords SET  Status = 1,Mismatch='"+str(Mismatch)+"' , Count = '"+ str(totrows) +"' WHERE RecordId = '" + str(RecordId) + "' ")
	cursor.close()
	database.commit()
	database.close()
	print('Data Successfully Inserted')














