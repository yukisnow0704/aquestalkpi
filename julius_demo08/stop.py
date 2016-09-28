import time

count = 0
while True:
	time.sleep(1);
	count += 1
	if count % 10 == 0:
		print('10sec sleep now!!')