import os
import RPi.GPIO as GPIO
from time import sleep

GPIO.setmode(GPIO.BCM)
GPIO.setup(7, GPIO.IN)
GPIO.setup(8, GPIO.IN)

try:
        while True:        
                print GPIO.input(7)
                print GPIO.input(8)
                if GPIO.input(7)==1:
	                os.system("/home/pi/source/aquestalkpi/motion_test02.sh")
	            	
                sleep(1)

except KeyboardInterrupt:
        pass

GPIO.cleanup()