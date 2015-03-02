import MySQLdb
import json
import urllib2
import time

class Connection:
    def __init__(self):
        

         # connect
        db = MySQLdb.connect(user = 'killermillergb',
                             passwd = 'Towcester1',
                             host = '50.62.209.147',
                             db = 'killermillergb_',
                             port = 3306 )
        c=db.cursor()
        UpdateSummoners(c)


        db.commit()
        db.close()



class UpdateSummoners:
    def __init__(self, c):

        c.execute("SELECT summoner_id FROM users")
        start = int (time.time())
        startCycle = start
        request = 0
        for summoner in c:
             summonerID = int(summoner[0])
             
             currentTimeCycle = int(time.time()) - startCycle
             
             if(request <10):
                 self.updateSummonersStats(summonerID, c, start)
                 request =+ 1
             else:
                 if (currentTimeCycle>10):
                     request=0
                     startCycle = int (time.time())
                 else:
                     time.sleep(11-currentTimeCycle)
             print "Current Cycle is: %(currentTimeCycle)s"%locals()
            # print end
        
        executionTime = int(time.time() - start)
        print "Time to execute update: %(executionTime)s"%locals()
    
             
    def updateSummonersStats(self, summonerID, c, start):
        apiKey = '2d33b014-b236-4d80-88e4-60567ae5026c'        
        url = 'https://euw.api.pvp.net/api/lol/euw/v2.2/matchhistory/' +str(summonerID)+ '?api_key='+apiKey
        matchHistroy = json.loads(urllib2.urlopen(url).read(40000))
        for match in matchHistroy['matches']:
            matchID = match['matchId']
            
            if(self.checkPreviousMatch(matchID, summonerID, c)):
                for participant in match['participants']:
                    creepTotal = participant['stats']['minionsKilled']
                    creepPermin = participant['timeline']['creepsPerMinDeltas']
                    creep10 = creepPermin['zeroToTen']*10
                    creep20 = 0
                    creep30 = 0
                    creepEnd = 0
                    
                    if 'tenToTwenty' in creepPermin:
                        creep20 = creepPermin['tenToTwenty']*10
                    else:
                        creep20 = 0

                    if 'twentyToThirty' in creepPermin:
                        creep30 = creepPermin['twentyToThirty']*10
                    else:
                        creep30 = 0
                            
                    if 'thirtyToEnd' in creepPermin:
                        creepEnd = creepPermin['thirtyToEnd']*10
                    else:
                        creepEnd = 0
                        
                  
                    
                    print int(time.time() - start)
                    sql =  "INSERT INTO matches (match_id, summoner_id, creeps, creeps10, creeps20, creeps30, creeps40)VALUES (" + \
                    "%(matchID)s , %(summonerID)s ,  %(creepTotal)s," %locals()   + \
                    "%(creep10)s , %(creep20)s , %(creep30)s ,  %(creepEnd)s)" %locals()
                   
                    c.execute(sql)
                    print int(time.time() - start)
                    
 
    def checkPreviousMatch(self,matchID, summonerID, c):
 
        #sql = "SELECT * FROM matches WHERE match_id ="+ str(matchID)+ " AND summoner_id ="+ str(summonerID) 
        sql = "SELECT EXISTS ( SELECT * FROM matches WHERE match_id =" + \
            "%(matchID)s AND summoner_id = %(summonerID)s)" %locals()
        if c.execute(sql):
            return True
        else:
            return True
            

       
             
#class TimeKeeper:

       
 
if __name__ == '__main__':
    config = Connection()

