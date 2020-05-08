from services.datainsertMFI import insert_MFI
from services.datainsertMSME import insert_MSME
from services.funfile import send_msg
from services.database import *
import xlrd
import time



def export():
    database = mycus()
    cursor = database.cursor()
    cursor.execute("SELECT RecordId,Status,File,Type from UploadRecords WHERE Status = 0")
    rowcursor = cursor.fetchall()
    cursor.close()
    database.close()
    for i in rowcursor:
        RecordId = i[0]
        status = i[1]
        file_name = i[2]
        type = i[3]
        database = mycus()
        cursor = database.cursor()
        cursor.execute("UPDATE UploadRecords SET  Status = 2 WHERE RecordId = '" + str(RecordId) + "' ")
        database.commit()
        database.close()
        loc = ("/var/www/ampl_web/storage/uploads/" + str(file_name))
        wb = xlrd.open_workbook(loc)
        sheet = wb.sheet_by_index(0)
        if type == 'MSME':
            try:
                insert_MSME(sheet, RecordId)
            except Exception as e :
                message = 'Error occur in MSME '+str(e)+' Record id- '+str(RecordId)
                send_msg(str(message))

        else:
            try:
                insert_MFI(sheet, RecordId)
            except Exception as e:
                message = 'Error occur in MFI '+str(e)+' Record id- '+str(RecordId)
                send_msg(str(message))


while True:
    try:
        export()
    except Exception as e:
        message = 'Error occur in Main' + str(e)
        send_msg(str(message))
    time.sleep(10)
    print('recheck after 10 sec')
