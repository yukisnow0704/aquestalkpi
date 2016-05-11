import os
import RPi.GPIO as GPIO
from time import sleep

GPIO.setmode(GPIO.BCM)
GPIO.setup(18, GPIO.IN)

try:
        while True:        
                print GPIO.input(18)
                if GPIO.input(18)==1:
	                os.system("/home/pi/source/aquestalkpi/motion_test02.sh")
	            	
                sleep(1)

except KeyboardInterrupt:
        pass

GPIO.cleanup()