import datetime
from services.database import *
from services.funfile import *


def insert_MSME(sheet, RecordId):

    if sheet.ncols == 16:
        totrows = 0
        Mismatch = 0
        for i in range(1, sheet.nrows):
            try:
                row = sheet.row_values(i)
                slno = row[0]
                Status = -1
                if slno != '' :

                    data = insertMSME(i, sheet, RecordId)
                    totrows = totrows + 1
                    Mismatch = Mismatch + data


            except Exception as e:
                message = 'Error occur in MSME in row ' + str(i) + ' and error - ' + str(e)
                send_msg(str(message))

        database = mycus()
        cursor = database.cursor()
        cursor.execute("UPDATE UploadRecords SET  Status = 1,Mismatch='" + str(Mismatch) + "' , Count = '" + str(
            totrows) + "' WHERE RecordId = '" + str(RecordId) + "' ")
        cursor.close()
        database.commit()
        database.close()
        print('data successfully inserted')


    else:

        database = mycus()
        cursor = database.cursor()
        cursor.execute("UPDATE UploadRecords SET Status = -1 WHERE RecordId = '" + str(RecordId) + "' ")
        cursor.close()
        database.commit()
        database.close()
        print('data successfully Updated')

def insertMSME(i, sheet, RecordId):
    Mismatch = 0
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
        try:
            loan_account_no = str(loan_account_no)
            loan_account_no = loan_account_no[:loan_account_no.index('.')]
        except :
            print('hello')
        if len(loan_account_no) < 8:
            error_count = 1
            msg = ' not valid loan account number.'
            error_msg = error_msg + str(msg)

        else:
            database = mycus()
            cursor = database.cursor()
            Demand_date_t = date_strftime(Demand_date)
            sql = "SELECT *  from MsmeExcelData WHERE LoanAccountNo='" + str(loan_account_no) + "' and DemandDate like '" + str(Demand_date_t) + "%'"

            cursor.execute(sql)
            rowcursor = cursor.fetchall()
            cursor.close()
            database.close()

            if len(rowcursor) > 0:
                error_count = 1
                msg = ' this loanAcct already exist for this month.'
                error_msg = error_msg + str(msg)

        if check_validation(mobile_no):
            if not mobile_check(mobile_no) :
                error_count = 1
                msg = ' not a valid mobile number.'
                error_msg = error_msg + str(msg)

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
                    error_count = 1
                    msg = ' this Mobile No already exist for this month.'
                    error_msg = error_msg + str(msg)

        else:
            msg = ' mobile number is not a number.'
            error_msg = error_msg + str(msg)
            error_count = 1

        if check_validation(EMIsr_no):
            if int(EMIsr_no) > 240  or int(EMIsr_no) < 1 :
                error_count = 1
                msg = ' EmI Sr of this loanAc is invalid.'
                error_msg = error_msg + str(msg)
        else:
            msg = ' EMIsr number is not a number.'
            error_count = 1
            error_msg = error_msg + str(msg)

        if check_validation(last_month_due):
            if not len(str(int(last_month_due))) < 8:
                error_count = 1
                msg = ' Lastmonth due error.'
                error_msg = error_msg + str(msg)
        else:
            msg = 'last month_due  is not a number.'
            error_count = 1
            error_msg = error_msg + str(msg)

        if check_validation(current_month_due):
            if not len(str(int(current_month_due))) < 8:
                error_count = 1
                msg = ' Current Month due error.'
                error_msg = error_msg + str(msg)
        else:
            msg = 'current month due is not a number.'
            error_count = 1
            error_msg = error_msg + str(msg)

        if cluster == '':
            error_count = 1
            msg = ' cluster cannot be blank.'
            error_msg = error_msg + str(msg)

        if branch_name == '':
            error_count = 1
            msg = ' BranchName cannot be empty.'
            error_msg = error_msg + str(msg)

        if client_name == '':
            error_count = 1
            msg = ' Client name cannot empty.'
            error_msg = error_msg + str(msg)


        elif len(client_name) < 4 or not alpha_check(client_name):
            error_count = 1
            msg = ' Client name invalid.'
            error_msg = error_msg + str(msg)

        if Demand_date == '':
            error_count = 1
            msg = ' Demand date cannot be empty.'
            error_msg = error_msg + str(msg)

        else:
            if not demanddate_comparision(Demand_date):
                error_count = 1
                msg = ' Demand date is invalid.'
                error_msg = error_msg + str(msg)

        if not nextinstallment_comparision(next_installment_date):
            error_count = 1
            msg = ' Next installment date is invalid.'
            error_msg = error_msg + str(msg)

        if product_vertical.strip() != 'MSME':
            error_count = 1
            msg = ' Product vertical should be MSME.'
            error_msg = error_msg + str(msg)

        if check_validation(late_penalty):
            if not len(str(int(late_penalty))) < 8:
                error_count = 1
                msg = ' Late penalty is invalid.'
                error_msg = error_msg + str(msg)
        else:
            error_count = 1
            msg = ' Late penalty is wrong format.'
            error_msg = error_msg + str(msg)

        try:
            client_id = str(client_id)
            client_id = client_id[:client_id.index('.')]
        except:
            print('hello')
        if len(client_id) < 4:
            error_count = 1
            msg = ' client id is invalid .'
            error_msg = error_msg + str(msg)
        else:
            database = mycus()
            cursor = database.cursor()
            Demand_date_t = date_strftime(Demand_date)
            sql = "SELECT *  from MsmeExcelData WHERE ClientId='" + str(client_id) + "' and DemandDate like '" + str(Demand_date_t) + "%'"
            cursor.execute(sql)
            rowcursor = cursor.fetchall()
            cursor.close()
            database.close()
            if len(rowcursor) > 0:
                error_count = 1
                msg = ' this client id already exist for this month.'
                error_msg = error_msg + str(msg)


        if not upload_comparision(upload_month):
            error_count = 1
            msg = ' Upload month is invalid.'
            error_msg = error_msg + str(msg)

        if (error_count == 1):
            Mismatch = 1

        Demand_date = date_conversion(Demand_date)
        next_installment_date = date_conversion(next_installment_date)
        database = mycus()
        cursor = database.cursor()
        sql = "INSERT INTO MsmeExcelData (RecordId,BranchName,Cluster,State,ClientId,LoanAccountNo,ClientName,MobileNo,EMISrNo,DemandDate,LastMonthDue,CurrentMonthDue,LatePenalty,NextInstallmentDate,UploadMonth,ProductVertical,Type,errorMsg,errorCount) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
        val = (RecordId, branch_name, cluster, state, client_id, loan_account_no, client_name, mobile_no, EMIsr_no,
               Demand_date, last_month_due, current_month_due, late_penalty, next_installment_date, upload_month,
               product_vertical, 'MSME', error_msg, error_count)

        cursor.execute(sql, val)
        database.commit()

    return Mismatch
