import MySQLdb

def mycus():
    database = MySQLdb.connect (
    host="10.16.3.196",
    user = "admin",
    passwd = "xSQPJKXRT2nq",
    db = "amplweb_db")
    return database

