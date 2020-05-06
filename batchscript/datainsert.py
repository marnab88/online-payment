from services.datainsertMFI import insert_MFI
from services.datainsertMSME import insert_MSME
from services.database import *
import xlrd


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
        sql = "UPDATE UploadRecords SET Status = 2  WHERE RecordId = %s "
        val = (RecordId)
        cursor.execute(sql, val)
        database.commit()
        database.close()
        loc = ("/var/www/html/onlineportal/storage/uploads/" + str(file_name))
        wb = xlrd.open_workbook(loc)
        sheet = wb.sheet_by_index(0)
        if type == 'MSME':
            insert_MSME(sheet, RecordId)
        else:
            insert_MFI(sheet, RecordId)


while True:
    try:
        export()
    except Exception as e:
        print(e)
    time.sleep(10)
