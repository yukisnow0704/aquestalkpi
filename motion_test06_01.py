GPIO.setmode(GPIO.BCM)
GPIO.setup(18, GPIO.IN)

try:
        while True:        
                print GPIO.input(18)
                if GPIO.input(18)==1:
	                os.system("/home/pi/source/aquestalkpi/motion_test02.sh")
	            	
except KeyboardInterrupt:
        pass

GPIO.cleanup()