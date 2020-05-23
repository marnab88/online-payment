import datetime
from services.database import *
from services.funfile import *


def insert_MFI(sheet, RecordId):

    if sheet.ncols == 20:
        totrows = 0
        Mismatch = 0
        for i in range(1, sheet.nrows):
            try:
                row = sheet.row_values(i)
                slno = row[0]

                if slno != '':
                    data = insertData(i,sheet,RecordId)
                    totrows = totrows + 1
                    Mismatch = Mismatch + data


            except Exception as e:
                message = 'Error occur in MFI in row ' + str(i)+' and error- '+str(e)
                send_msg(str(message))

        database = mycus()
        cursor = database.cursor()
        cursor.execute("UPDATE UploadRecords SET  Status = 1,Mismatch='" + str(Mismatch) + "' , Count = '" + str(
            totrows) + "' WHERE RecordId = '" + str(RecordId) + "' ")
        cursor.close()
        database.commit()
        database.close()
        print('Data Successfully Inserted')
    else:
        database = mycus()
        cursor = database.cursor()
        cursor.execute("UPDATE UploadRecords SET  Status = -1  WHERE RecordId = '" + str(RecordId) + "' ")
        cursor.close()
        database.commit()
        database.close()




def insertData(i,sheet,RecordId):
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
    spouse_name = row[7]
    village = row[8]
    center = row[9]
    group = row[10]
    mobile_no = row[11]
    EMIsr_no = row[12]
    Demand_date = row[13]
    last_month_due = row[14]
    current_month_due = row[15]
    late_penalty = row[16]
    next_installment_date = row[17]
    upload_month = row[18]
    product_vertical = row[19]

    if slno != '':
        try:
            loan_account_no = str(loan_account_no)
            loan_account_no = loan_account_no[:loan_account_no.index('.')]
        except:
            print('hello')
        if len(str(loan_account_no)) < 6 or len(str(loan_account_no)) > 9:
            error_count = 1
            msg = ' not valid loan account number.'
            error_msg = error_msg + str(msg)
        else:

            database = mycus()
            cursor = database.cursor()
            Demand_date_t = date_strftime(Demand_date)
            sql = "SELECT *  from ExcelData WHERE LoanAccountNo='" + str(loan_account_no) + "' and DemandDate like '" + str(Demand_date_t) + "%'"
            cursor.execute(sql)
            rowcursor = cursor.fetchall()
            cursor.close()
            database.close()
            if len(rowcursor) > 0:
                error_count = 1
                msg = ' this loanAcct already exist for this month.'
                error_msg = error_msg + str(msg)
        try:
            client_id = str(client_id)
            client_id = client_id[:client_id.index('.')]
        except:
            print('hello')
        if client_id == '':
            error_count = 1
            msg = ' Client Id cannot be empty.'
            error_msg = error_msg + str(msg)

        else:
            if len(str(client_id)) < 4:
                error_count = 1
                msg = ' client id is invalid .'
                error_msg = error_msg + str(msg)
            else:
                database = mycus()
                cursor = database.cursor()
                Demand_date_t = date_strftime(Demand_date)
                sql = "SELECT *  from ExcelData WHERE ClientId='" +   str(client_id) + "' and DemandDate like '" + str(Demand_date_t) + "%'"
                cursor.execute(sql)
                rowcursor = cursor.fetchall()
                cursor.close()
                database.close()
                if len(rowcursor) > 0:
                    error_count = 1
                    msg = ' this client id already exist for this month.'
                    error_msg = error_msg + str(msg)


        if check_validation(mobile_no):
            if not len(str(int(mobile_no))) == 10:
                error_count = 1
                msg = ' mobile number is invalid.'
                error_msg = error_msg + str(msg)
        else:
            error_count = 1
            msg = ' mobile number is invalid.'
            error_msg = error_msg + str(msg)

        if check_validation(EMIsr_no):
            if int(EMIsr_no) < 1 or int(EMIsr_no) > 60:
                error_count = 1
                msg = ' EMIsr is Invalid.'
                error_msg = error_msg + str(msg)
        else:
            msg = ' EMIsr number is invalid format.'
            error_count = 1
            error_msg = error_msg + str(msg)

        if check_validation(last_month_due):
            if not len(str(int(last_month_due))) < 8:
                error_count = 1
                msg = ' Lastmonthdue is invalid.'
                error_msg = error_msg + str(msg)
        else:
            msg = ' Lastmonthdue is in wrong format.'
            error_count = 1
            error_msg = error_msg + str(msg)

        if check_validation(current_month_due):
            if not len(str(int(current_month_due))) < 8:
                error_count = 1
                msg = ' CurrentMonthDue is invalid.'
                error_msg = error_msg + str(msg)
        else:
            msg = ' Currentmonthdue is in wrong format.'
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

        if state == '':
            error_count = 1
            msg = ' State cannot be empty.'
            error_msg = error_msg + str(msg)

        if client_name == '':
            error_count = 1
            msg = ' Client name is not empty.'
            error_msg = error_msg + str(msg)

        else:
            if len(client_name) < 3 or len(client_name) > 20 or not alpha_check(client_name):
                error_count = 1
                msg = ' Client name is invalid.'
                error_msg = error_msg + str(msg)

        if spouse_name == '':
            error_count = 1
            msg = ' Spouse name is empty.'
            error_msg = error_msg + str(msg)

        else:
            if len(spouse_name) < 3 or len(spouse_name) > 20 or not alpha_check(spouse_name):
                error_count = 1
                msg = ' Spouse name is not valid.'
                error_msg = error_msg + str(msg)

        if village == '':
            error_count = 1
            msg = ' Village is empty.'
            error_msg = error_msg + str(msg)

        else:
            if len(village) < 3 or len(village) > 20 or not alpha_num(village):
                error_count = 1
                msg = ' Village is invalid.'
                error_msg = error_msg + str(msg)

        if center == '':
            error_count = 1
            msg = ' Center cannot empty.'
            error_msg = error_msg + str(msg)

        else:
            if len(center) < 3 or len(center) > 20 or not alpha_num(center):
                error_count = 1
                msg = ' Center is invalid.'
                error_msg = error_msg + str(msg)

        if group == '':
            error_count = 1
            msg = ' Group cannot empty.'
            error_msg = error_msg + str(msg)

        else:
            if len(group) < 3 or len(group) > 20 or not alpha_num(group):
                error_count = 1
                msg = ' Group is invalid.'
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

        if check_validation(last_month_due):
            if not len(str(int(last_month_due))) < 7:
                error_count = 1
                msg = ' Last month due is invalid.'
                error_msg = error_msg + str(msg)
        else:
            error_count = 1
            msg = ' Last month due is not digit'

            error_msg = error_msg + str(msg)


        if not nextinstallment_comparision(next_installment_date):
            error_count = 1
            msg = ' Next installment date is invalid.'
            error_msg = error_msg + str(msg)

        if not upload_comparision(upload_month):
            error_count = 1
            msg = ' Upload month is invalid.'
            error_msg = error_msg + str(msg)

        if product_vertical.strip() != 'MFI':
            error_count = 1
            msg = ' Product vertical should be MFI.'
            error_msg = error_msg + str(msg)

        if int(late_penalty) > 0:
            error_count = 1
            msg = ' Late penalty should be zero.'
            error_msg = error_msg + str(msg)

        if (error_count == 1):
            Mismatch = 1

        Demand_date = date_conversion(Demand_date)
        next_installment_date = date_conversion(next_installment_date)
        database = mycus()
        cursor = database.cursor()
        sql = "INSERT INTO ExcelData (RecordId,BranchName,Cluster,State,ClientId,LoanAccountNo,ClientName,SpouseName,VillageName,Center,GroupName,MobileNo,EMISrNo,DemandDate,LastMonthDue,CurrentMonthDue,NextInstallmentDate,UploadMonth,ProductVertical,Type,errorMsg,errorCount) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
        val = (
            RecordId, branch_name, cluster, state, client_id, loan_account_no, client_name, spouse_name, village,
            center,
            group, mobile_no, EMIsr_no,
            Demand_date, last_month_due, current_month_due, next_installment_date, upload_month,
            product_vertical, 'MFI' ,error_msg, error_count)

        cursor.execute(sql, val)
        database.commit()
        cursor.close()
        database.close()

    return Mismatch
