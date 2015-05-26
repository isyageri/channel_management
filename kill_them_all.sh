ps -ef |grep sqlplus  |awk '{print "kill -9", $2}' > kill_all.sh;sh kill_all.sh
