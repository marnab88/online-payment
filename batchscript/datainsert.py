from services.datainsertMFI import insert_MFI
from services.datainsertMSME import insert_MSME
from services.database import *
import xlrd

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
    loc = ("/var/www/html/onlineportal/storage/uploads/" + str(file_name))
    wb = xlrd.open_workbook(loc)
    sheet = wb.sheet_by_index(0)
    if type == 'MSME':
        insert_MSME(sheet,RecordId)
    else:
        insert_MFI(sheet,RecordId)

