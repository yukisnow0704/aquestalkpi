def reading(sensor):
	import time
	import RPi.GPIO as GPIO
	GPIO.setwarings(False)

	GPIO.setmode(GPIO.BOARD)
	TRIG=8
	ECHO=7

	if sensor == 0:
		GPIO.setup(TRIG, GPIO.OUT)
		GPIO.setup(TRIG, GPIO.IN)
		GPIO.output(TRIG, GPIO.LOW)
		time.sleep(0.3)

		GPIO.output(TRIG, True)
		time.sleep(0.00001)
		GPIO.output(TRIG, False)
		while GPIO.input(ECHO)	:
			signaloff = time.time()

		timespassed = signalon - signaloff
		distance = timespassed * 17000
		return distance
		GPIO.cleanup()
	else:
		print "Incorrect usonic () function varible."

	print readign(0)