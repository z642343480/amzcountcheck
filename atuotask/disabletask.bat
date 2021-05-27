rem SCHTASKS /delete /TN usasyncdata /F
rem SCHTASKS /delete /TN uksyncdata /F
rem SCHTASKS /delete /TN desyncdata /F
rem SCHTASKS /delete /TN jpsyncdata /F
rem SCHTASKS /delete /TN espsyncdata /F
rem SCHTASKS /delete /TN itsyncdata /F
rem SCHTASKS /delete /TN frsyncdata /F
rem SCHTASKS /delete /TN mxsyncdata /F
rem SCHTASKS /delete /TN casyncdata /F


schtasks /change /tn usasyncdata /disable
schtasks /change /tn uksyncdata /disable
schtasks /change /tn desyncdata /disable
schtasks /change /tn jpsyncdata /disable
schtasks /change /tn espsyncdata /disable
schtasks /change /tn itsyncdata /disable
schtasks /change /tn frsyncdata /disable
schtasks /change /tn mxsyncdata /disable
schtasks /change /tn casyncdata /disable