import requests
import mysql.connector
import urllib.parse
import json
import datetime
import time
import sys
import urllib

key='dda89bd7de98c135306a92146667cd1a3e943'
def mycus():
    mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        passwd="colourfade",
        database="onlineportal"
    )
    return mydb


def send_msg(message):
	message = urllib.parse.quote(message+str('>>>> from testserver'))
	API_ENDPOINT = 'https://api.telegram.org/bot1285906317:AAHND-_fixUAexGtxLpZlv_2g-MrS9k8UwQ/sendMessage?chat_id=-421475178&text=' + message
	r = requests.post(url=API_ENDPOINT)
	x = r.json()




def sms_integration(Mid,RecordId,Type,LoanAccountNo,MobileNo,Amount,ClientName):
    if(len(MobileNo)==10):
        longurl="http://128.199.184.65/onlineportal/site/login?id="+str(RecordId)+"&typ="+str(Type)+"&l="+str(LoanAccountNo)
        longurl=urllib.parse.quote_plus(longurl)
        url = ("https://cutt.ly/api/api.php?key=%s&short=%s"%(key,longurl))
        response = requests.post(url=url)
        data = response.json()
        TinyUrl=data['url']['shortLink']
        mydb = mycus()
        mycursor = mydb.cursor()
        mycursor.execute("UPDATE MsmeExcelData SET TinnyUrl = '" + str(TinyUrl) + "' WHERE Mid = '" + str(Mid) + "'")
        mydb.commit()
        mydb.close()
        maskLoan='XXXX'+LoanAccountNo[-6:]
        msg=("Dear %s, EMI of Rs. %s for Annapurna loan a/c no. %s is due. Make online payment at %s ."%(str(ClientName),str(Amount),str(maskLoan),str(TinyUrl)))
        print(msg)
        msg = urllib.parse.quote(msg)
        
    
        url="http://enterprise.smsgupshup.com/GatewayAPI/rest?send_to="+str(MobileNo)+"&method=sendMessage&userid=2000183786&password=Afpl@786&v=1.1&msg_type=TEXT&msg="+str(msg)
        response = requests.post(url=url)
        print(response.text)
        resp=response.text
        mydb = mycus()
        mycursor = mydb.cursor()
        que=("UPDATE MsmeExcelData SET SmsStatus = 1, SmsAlert='%s' WHERE Mid = '%s' "%(str(resp),Mid))
        mycursor.execute(que)
        mydb.commit()
        mydb.close()
def checksms():
    mydb = mycus()
    mycursor = mydb.cursor()
    mycursor.execute("SELECT Mid,RecordId,LoanAccountNo,ClientName,MobileNo,DemandDate,Type,(LastMonthDue+CurrentMonthDue+LatePenalty) FROM MsmeExcelData WHERE SmsStatus=2 ORDER BY Mid ASC")
    rowcursor = mycursor.fetchall()
    if(rowcursor):
        for i in rowcursor:
            Mid = i[0]
            RecordId = i[1]
            LoanAccountNo = i[2]
            ClientName = i[3]
            MobileNo = i[4]
            DemandDate = i[5]
            Amount=i[7]
            DemandDate = datetime.datetime.strptime(DemandDate, '%Y-%m-%d').strftime('%d-%m-%Y')
            Type = i[6]
            sms_integration(Mid,RecordId,Type,LoanAccountNo,MobileNo,Amount,ClientName)
            time.sleep(1)
        que =("UPDATE UploadRecords SET SmsStatus = 1 WHERE RecordId = '%s' "%(str(RecordId)))
        mycursor.execute(que)
        mydb.commit()
    mydb.close()
while True:
    try:
        checksms()
    except Exception as e:
        message = 'Error occur in SMS Integration-' + str(e)
        send_msg(str(message))
    time.sleep(60)