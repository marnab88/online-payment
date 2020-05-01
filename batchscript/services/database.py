import MySQLdb

def mycus():
    database = MySQLdb.connect (
    host="localhost",
    user = "root",
    passwd = "colourfade",
    db = "onlineportal")
    return database
