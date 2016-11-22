import smtplib
from email.mime.text import MIMEText

class sendGmail:
    username, password = 'sist.kudolab@gmail.com', 'kudo0401'

    def __init__(self, to, sub, body):
        host, port = 'smtp.gmail.com', 465
        msg = MIMEText(body)
        msg['Subject'] = sub
        msg['From'] = self.username
        msg['To'] = to

        smtp = smtplib.SMTP_SSL(host, port)
        smtp.ehlo()
        smtp.login(self.username, self.password)
        smtp.mail(self.username)
        smtp.rcpt(to)
        smtp.data(msg.as_string())
        smtp.quit()

to = 'koyamaryoma@gmail.com'
sub = 'python smtplib'
body = 'hello world'
sendGmail(to, sub, body)

to = 'yukisnow0704@gmail.com'
sub = 'python smtplib'
body = 'hello world'
sendGmail(to, sub, body)